<?php

namespace App\Livewire;

use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Commodity;
use App\Models\Inquiry;
use App\Models\InquiryMessage;
use App\Models\Listing;
use App\Models\Notification;
use App\Models\Product;
use App\Models\User;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class MunicipalMarketplaceDashboard extends Component
{
    use WithPagination;

    // View mode: 'overview', 'price_index', 'harvest_registry', 'trade_inquiries'
    public string $viewMode = 'overview';

    // Search & Filter state for Harvest Registry
    public string $search = '';

    public string $categoryFilter = 'All';

    public string $barangayFilter = 'All';

    // Filter for Price Index
    public string $priceCategoryFilter = 'All';

    // Add Product/Listing Modal state (UC-03)
    public bool $showAddProductModal = false;

    public string $new_name = '';

    public string $new_category = 'Grains & Cereals';

    public ?float $new_quantity = null;

    public string $new_unit = 'kg';

    public ?float $new_price = null;

    public string $new_barangay = 'Linotan';

    public ?string $new_harvest_date = null;

    public string $new_pickup_location = '';

    public string $new_description = '';

    // Pre-order Inquiry Modal state (UC-05)
    public bool $showInquiryModal = false;

    public ?int $selectedProductId = null;

    public ?float $inquiry_quantity = 10;

    public ?string $inquiry_pickup_date = null;

    public string $inquiry_message = '';

    // Edit Product/Listing Modal state
    public bool $showEditProductModal = false;

    public ?int $editingListingId = null;

    public string $edit_title = '';

    public ?float $edit_price_per_unit = null;

    public ?float $edit_available_quantity = null;

    public string $edit_status = 'active';

    public string $edit_description = '';

    public function mount(?string $viewMode = null): void
    {
        if ($viewMode) {
            $this->viewMode = $viewMode;
        }

        $user = Auth::user();
        if ($user && $user->barangay) {
            $this->new_barangay = $user->barangay;
        }
        $this->new_harvest_date = now()->format('Y-m-d');
        $this->inquiry_pickup_date = now()->addDays(2)->format('Y-m-d');
        $this->clearDashboardCache();
    }

    public function updatedSearch(): void
    {
        $this->resetPage('harvestPage');
        $this->resetPage('overviewHarvestPage');
    }

    public function updatedCategoryFilter(): void
    {
        $this->resetPage('harvestPage');
        $this->resetPage('overviewHarvestPage');
    }

    public function updatedBarangayFilter(): void
    {
        $this->resetPage('harvestPage');
        $this->resetPage('overviewHarvestPage');
    }

    public function updatedPriceCategoryFilter(): void
    {
        $this->resetPage('pricePage');
        $this->resetPage('overviewPricePage');
    }

    /**
     * Paginate an in-memory collection using Livewire's current page resolver
     */
    protected function paginateCollection($items, int $perPage = 10, string $pageName = 'page'): LengthAwarePaginator
    {
        if (! $items instanceof Collection) {
            $items = is_array($items) ? collect($items) : collect();
        }

        $page = Paginator::resolveCurrentPage($pageName);
        $sliced = $items->forPage($page, $perPage)->values();

        return new LengthAwarePaginator($sliced, $items->count(), $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }

    /**
     * Compute Active Harvest Listings (UC-03 / D2 Repository -> LISTINGS table)
     */
    #[Computed]
    public function harvestProducts()
    {
        $perPage = $this->viewMode === 'harvest_registry' ? 8 : 5;
        $pageName = $this->viewMode === 'harvest_registry' ? 'harvestPage' : 'overviewHarvestPage';

        return Listing::query()
            ->with(['farmer.farmerProfile', 'commodity.category', 'primaryImage'])
            ->where('status', 'active')
            ->when($this->categoryFilter !== 'All', function ($query) {
                $query->whereHas('commodity.category', function ($cq) {
                    $cq->where('name', $this->categoryFilter);
                });
            })
            ->when($this->barangayFilter !== 'All', function ($query) {
                $query->whereHas('farmer.farmerProfile', function ($fq) {
                    $fq->where('farm_location', $this->barangayFilter);
                });
            })
            ->when(! empty(trim($this->search)), function ($query) {
                $term = '%'.trim($this->search).'%';
                $query->where(function ($sub) use ($term) {
                    $sub->where('title', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhereHas('farmer', function ($f) use ($term) {
                            $f->where('full_name', 'like', $term);
                        })
                        ->orWhereHas('commodity', function ($c) use ($term) {
                            $c->where('name', 'like', $term);
                        });
                });
            })
            ->latest('listing_id')
            ->paginate($perPage, ['*'], $pageName);
    }

    /**
     * Invalidate dashboard transient cache on write operations
     */
    public function clearDashboardCache(): void
    {
        cache()->forget('tabonet_price_index_summary_All');
        foreach (Product::CATEGORIES as $cat) {
            cache()->forget("tabonet_price_index_summary_{$cat}");
        }
        if ($userId = Auth::id()) {
            cache()->forget("tabonet_kpis_user_{$userId}");
        }
    }

    /**
     * Compute Municipal Spot Price Index Aggregations (UC-06 / D3 Repository -> PRICE_RECORDS / LISTINGS)
     * Formula: P_avg = Sum(P) / N
     */
    #[Computed]
    public function priceIndexSummary(): Collection
    {
        $cacheKey = "tabonet_price_index_summary_{$this->priceCategoryFilter}";

        $cached = cache()->remember($cacheKey, 20, function () {
            $records = Listing::query()
                ->with(['commodity.category', 'farmer.farmerProfile'])
                ->where('status', 'active')
                ->when($this->priceCategoryFilter !== 'All', function ($query) {
                    $query->whereHas('commodity.category', function ($cq) {
                        $cq->where('name', $this->priceCategoryFilter);
                    });
                })
                ->get();

            if ($records->isEmpty()) {
                return [];
            }

            return $records->groupBy(fn ($item) => $item->commodity?->name ?? $item->title)->map(function ($items, $name) {
                $first = $items->first();
                $prices = $items->pluck('price_per_unit')->map(fn ($p) => (float) $p);
                $count = $prices->count();
                $sum = $prices->sum();
                $avg = $count > 0 ? $sum / $count : 0;
                $min = $prices->min();
                $max = $prices->max();

                $trend = 'Spot Stable';
                if ($min !== $max) {
                    $diffPercent = round((($avg - $min) / $min) * 100, 1);
                    $trend = $diffPercent > 0 ? "+{$diffPercent}% Spread" : 'Spot Equilibrium';
                }

                return [
                    'name' => $name,
                    'category' => $first->commodity?->category?->name ?? 'Agricultural',
                    'unit' => $first->commodity?->unit_of_measure ?? 'kg',
                    'p_avg' => round($avg, 2),
                    'p_min' => round($min, 2),
                    'p_max' => round($max, 2),
                    'samples' => $count,
                    'barangays' => $items->map(fn ($it) => $it->farmer?->farmerProfile?->farm_location ?? 'Linotan')->unique()->values()->all(),
                    'trend' => $trend,
                ];
            })->values()->all();
        });

        if (! is_array($cached)) {
            cache()->forget($cacheKey);
            $cached = [];
        }

        return collect($cached);
    }

    /**
     * Compute Role-Adaptive Key Performance Indicators
     */
    #[Computed]
    public function kpis(): array
    {
        $userId = Auth::id() ?? 0;
        $cacheKey = "tabonet_kpis_user_{$userId}";

        return cache()->remember($cacheKey, 20, function () {
            $totalListings = Listing::where('status', 'active')->count();

            // Rice average spot market
            $riceAvg = Listing::where('status', 'active')
                ->where('title', 'like', '%Rice%')
                ->avg('price_per_unit');

            $user = Auth::user();
            $inquiriesCount = 0;
            $myListingsCount = 0;
            $myInventoryValue = 0.0;

            if ($user) {
                if ($user->isFarmer()) {
                    $inquiriesCount = Inquiry::whereHas('listing', fn ($q) => $q->where('farmer_id', $user->user_id))
                        ->where('status', 'pending')
                        ->count();

                    $farmerListings = Listing::where('farmer_id', $user->user_id)->where('status', 'active')->get();
                    $myListingsCount = $farmerListings->count();
                    $myInventoryValue = (float) $farmerListings->sum(fn ($l) => (float) $l->price_per_unit * (float) $l->available_quantity);
                } else {
                    $inquiriesCount = Inquiry::where('buyer_id', $user->user_id)->count();
                }
            }

            $producersCount = User::where('role', 'farmer')->count();

            return [
                'total_listings' => $totalListings,
                'rice_avg' => $riceAvg ? round($riceAvg, 2) : 52.00,
                'inquiries_count' => $inquiriesCount,
                'producers_count' => $producersCount,
                'my_listings_count' => $myListingsCount,
                'my_inventory_value' => round($myInventoryValue, 2),
            ];
        });
    }

    /**
     * Compute Logged-in Farmer's Own Harvest Listings
     */
    #[Computed]
    public function myHarvestProducts()
    {
        $user = Auth::user();
        if (! $user) {
            return collect();
        }

        return Listing::query()
            ->with(['commodity.category'])
            ->where('farmer_id', $user->user_id)
            ->where('status', '!=', 'archived')
            ->latest('listing_id')
            ->paginate(5, ['*'], 'myHarvestPage');
    }

    /**
     * Compute User Trade Inquiries (UC-05 / D4 Repository -> INQUIRIES table)
     */
    #[Computed]
    public function userInquiries()
    {
        $user = Auth::user();
        if (! $user) {
            return collect();
        }

        $perPage = $this->viewMode === 'trade_inquiries' ? 8 : 5;
        $pageName = $this->viewMode === 'trade_inquiries' ? 'inquiryPage' : 'overviewInquiryPage';

        if ($user->isFarmer()) {
            return Inquiry::query()
                ->with(['buyer', 'listing.commodity', 'messages'])
                ->whereHas('listing', fn ($q) => $q->where('farmer_id', $user->user_id))
                ->latest('inquiry_id')
                ->paginate($perPage, ['*'], $pageName);
        }

        if ($user->isAdmin()) {
            return Inquiry::query()
                ->with(['buyer', 'listing.farmer', 'listing.commodity', 'messages'])
                ->latest('inquiry_id')
                ->paginate($perPage, ['*'], $pageName);
        }

        return Inquiry::query()
            ->with(['listing.farmer', 'listing.commodity', 'messages'])
            ->where('buyer_id', $user->user_id)
            ->latest('inquiry_id')
            ->paginate($perPage, ['*'], $pageName);
    }

    /**
     * Compute Paginated Municipal Spot Price Index for Tables
     */
    #[Computed]
    public function paginatedPriceIndex()
    {
        $perPage = $this->viewMode === 'price_index' ? 8 : 4;
        $pageName = $this->viewMode === 'price_index' ? 'pricePage' : 'overviewPricePage';

        return $this->paginateCollection($this->priceIndexSummary, $perPage, $pageName);
    }

    /**
     * Compute Pending Farmer Accreditations for Administrator
     */
    #[Computed]
    public function pendingProducers()
    {
        if (! Auth::user()?->isAdmin()) {
            return collect();
        }

        return User::where('role', 'farmer')
            ->where('verification_status', '!=', 'verified')
            ->with('farmerProfile')
            ->latest()
            ->get();
    }

    /**
     * Open the Add Harvest Listing modal (Strict RBAC: Farmer or Admin)
     */
    public function openAddProductModal(): void
    {
        $this->authorize('create', Listing::class);

        $user = Auth::user();
        $this->new_barangay = $user->barangay ?? 'Linotan';
        $this->new_harvest_date = now()->format('Y-m-d');
        $this->showAddProductModal = true;
    }

    /**
     * Action: Add New Harvest Listing (UC-03)
     * RBAC Protected: Handled by ProductPolicy@create
     */
    public function createProduct(): void
    {
        $this->authorize('create', Listing::class);

        $validated = $this->validate([
            'new_name' => ['required', 'string', 'max:255'],
            'new_category' => ['required', 'string'],
            'new_quantity' => ['required', 'numeric', 'min:0.1'],
            'new_unit' => ['required', 'string'],
            'new_price' => ['required', 'numeric', 'min:0.5'],
            'new_barangay' => ['required', 'string'],
            'new_harvest_date' => ['nullable', 'date'],
            'new_pickup_location' => ['nullable', 'string', 'max:255'],
            'new_description' => ['nullable', 'string', 'max:1000'],
        ]);

        $category = Category::firstOrCreate(
            ['name' => $validated['new_category']],
            ['description' => $validated['new_category'].' category']
        );

        $commodity = Commodity::firstOrCreate(
            ['name' => $validated['new_name'], 'category_id' => $category->category_id],
            [
                'unit_of_measure' => $validated['new_unit'],
                'description' => $validated['new_description'] ?: $validated['new_name'],
            ]
        );

        $listing = Listing::create([
            'farmer_id' => Auth::user()->user_id,
            'commodity_id' => $commodity->commodity_id,
            'title' => $validated['new_name'],
            'description' => $validated['new_description'] ?: 'Freshly harvested produce from Cantilan.',
            'price_per_unit' => $validated['new_price'],
            'available_quantity' => $validated['new_quantity'],
            'status' => 'active',
        ]);

        AuditLog::log(
            Auth::user()->user_id,
            'listing_created',
            'Listing',
            $listing->listing_id,
            ['title' => $listing->title, 'price' => $listing->price_per_unit]
        );

        $this->clearDashboardCache();
        $this->showAddProductModal = false;
        $this->reset(['new_name', 'new_quantity', 'new_price', 'new_description']);

        Flux::toast(
            variant: 'success',
            text: "Harvest listing '{$listing->title}' successfully published! Spot price recorded in D3 Index."
        );
    }

    /**
     * Open Trade Inquiry Modal (UC-05)
     */
    public function openInquiryModal(int $productId): void
    {
        $listing = Listing::findOrFail($productId);
        $this->authorize('create', [Inquiry::class, $listing]);

        $this->selectedProductId = $productId;
        $this->inquiry_quantity = min(10, (float) $listing->available_quantity);
        $this->inquiry_pickup_date = now()->addDays(2)->format('Y-m-d');
        $this->inquiry_message = "Hello, I would like to place a trade pre-order for {$listing->title}. Please confirm availability for pickup.";
        $this->showInquiryModal = true;
    }

    /**
     * Submit Trade Inquiry Pre-Order (UC-05)
     * RBAC Protected: Handled by InquiryPolicy@create
     */
    public function submitInquiry(): void
    {
        $listing = Listing::findOrFail($this->selectedProductId);
        $this->authorize('create', [Inquiry::class, $listing]);

        $validated = $this->validate([
            'selectedProductId' => ['required', 'exists:listings,listing_id'],
            'inquiry_quantity' => ['required', 'numeric', 'min:0.1', 'max:'.(float) $listing->available_quantity],
            'inquiry_pickup_date' => ['required', 'date'],
            'inquiry_message' => ['nullable', 'string', 'max:1000'],
        ]);

        $inquiry = Inquiry::create([
            'listing_id' => $listing->listing_id,
            'buyer_id' => Auth::user()->user_id,
            'status' => 'pending',
        ]);

        $messageText = $validated['inquiry_message'] ?: "Trade Inquiry: Pre-order request for {$validated['inquiry_quantity']} {$listing->commodity?->unit_of_measure}. Target pickup: {$validated['inquiry_pickup_date']}.";

        InquiryMessage::create([
            'inquiry_id' => $inquiry->inquiry_id,
            'sender_id' => Auth::user()->user_id,
            'message' => $messageText,
            'is_read' => false,
            'sent_at' => now(),
        ]);

        Notification::create([
            'user_id' => $listing->farmer_id,
            'inquiry_id' => $inquiry->inquiry_id,
            'title' => 'New Trade Lead',
            'message' => (Auth::user()->full_name ?: Auth::user()->name)." placed a pre-order on {$listing->title}.",
            'is_read' => false,
        ]);

        AuditLog::log(
            Auth::user()->user_id,
            'inquiry_created',
            'Inquiry',
            $inquiry->inquiry_id,
            ['listing_id' => $listing->listing_id, 'quantity' => $validated['inquiry_quantity']]
        );

        $this->clearDashboardCache();
        $this->showInquiryModal = false;
        $this->selectedProductId = null;
        $this->reset(['inquiry_message']);

        Flux::toast(
            variant: 'success',
            text: "Pre-order inquiry dispatched directly to producer {$listing->farmer?->full_name}!"
        );
    }

    /**
     * Update Trade Inquiry Status (For Farmers: Accept / Decline)
     * RBAC Protected: Handled by InquiryPolicy@updateStatus
     */
    public function updateInquiryStatus(int $inquiryId, string $status): void
    {
        $inquiry = Inquiry::findOrFail($inquiryId);
        $this->authorize('updateStatus', $inquiry);

        $inquiry->update(['status' => $status]);

        AuditLog::log(
            Auth::user()->user_id,
            'inquiry_status_updated',
            'Inquiry',
            $inquiry->inquiry_id,
            ['status' => $status]
        );

        $this->clearDashboardCache();

        Flux::toast(
            variant: 'success',
            text: "Trade lead status updated to '{$status}'."
        );
    }

    /**
     * Administrator Action: Verify Smallholder Farmer DA-RSBSA Accreditation
     * RBAC Protected: Gate 'moderate-system'
     */
    public function verifyFarmer(int $userId): void
    {
        Gate::authorize('moderate-system');

        $farmer = User::where('role', 'farmer')->findOrFail($userId);
        $farmer->update(['verification_status' => 'verified']);

        AuditLog::log(
            Auth::user()->user_id,
            'user_verified',
            'User',
            $farmer->user_id,
            ['full_name' => $farmer->full_name]
        );

        $this->clearDashboardCache();

        Flux::toast(
            variant: 'success',
            text: "Smallholder producer {$farmer->full_name} DA-RSBSA accreditation verified."
        );
    }

    /**
     * Open the Edit Harvest Listing modal (Owner Farmer or Admin)
     */
    public function openEditProductModal(int $listingId): void
    {
        $listing = Listing::findOrFail($listingId);
        $this->authorize('update', $listing);

        $this->editingListingId = $listingId;
        $this->edit_title = $listing->title;
        $this->edit_price_per_unit = (float) $listing->price_per_unit;
        $this->edit_available_quantity = (float) $listing->available_quantity;
        $this->edit_status = $listing->status;
        $this->edit_description = $listing->description ?? '';
        $this->showEditProductModal = true;
    }

    /**
     * Update Harvest Listing (Owner Farmer or Admin)
     */
    public function updateProduct(): void
    {
        if (! $this->editingListingId) {
            return;
        }

        $listing = Listing::findOrFail($this->editingListingId);
        $this->authorize('update', $listing);

        $validated = $this->validate([
            'edit_title' => ['required', 'string', 'max:255'],
            'edit_price_per_unit' => ['required', 'numeric', 'min:0.5'],
            'edit_available_quantity' => ['required', 'numeric', 'min:0'],
            'edit_status' => ['required', 'in:active,sold_out,archived'],
            'edit_description' => ['nullable', 'string', 'max:1000'],
        ]);

        $listing->update([
            'title' => $validated['edit_title'],
            'price_per_unit' => $validated['edit_price_per_unit'],
            'available_quantity' => $validated['edit_available_quantity'],
            'status' => $validated['edit_status'],
            'description' => $validated['edit_description'],
        ]);

        AuditLog::log(
            Auth::user()->user_id,
            'listing_updated',
            'Listing',
            $listing->listing_id,
            ['title' => $listing->title, 'price' => $listing->price_per_unit, 'status' => $listing->status]
        );

        $this->clearDashboardCache();
        $this->showEditProductModal = false;
        $this->editingListingId = null;

        Flux::toast(
            variant: 'success',
            text: "Harvest listing '{$listing->title}' updated successfully."
        );
    }

    /**
     * Remove / Archive Listing (Owner Farmer or Admin)
     */
    public function removeListing(int $listingId): void
    {
        $this->archiveProduct($listingId);
    }

    /**
     * Archive/Remove Listing (Owner Farmer or Admin)
     * RBAC Protected: ProductPolicy@delete
     */
    public function archiveProduct(int $productId): void
    {
        $listing = Listing::findOrFail($productId);
        $this->authorize('delete', $listing);

        $listing->update(['status' => 'archived']);

        AuditLog::log(
            Auth::user()->user_id,
            'listing_archived',
            'Listing',
            $listing->listing_id,
            ['title' => $listing->title]
        );

        $this->clearDashboardCache();

        Flux::toast(
            variant: 'success',
            text: "Listing '{$listing->title}' removed from municipal marketplace."
        );
    }

    public function render(): View
    {
        return view('livewire.municipal-marketplace-dashboard');
    }
}

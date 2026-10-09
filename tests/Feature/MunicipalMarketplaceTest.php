<?php

namespace Tests\Feature;

use App\Livewire\MunicipalMarketplaceDashboard;
use App\Models\Inquiry;
use App\Models\Listing;
use App\Models\PriceRecord;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MunicipalMarketplaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_farmer_can_view_dashboard_overview(): void
    {
        $farmer = User::factory()->create([
            'role' => 'farmer',
            'barangay' => 'Linotan',
            'status' => 'verified',
        ]);

        Product::create([
            'user_id' => $farmer->id,
            'name' => 'Native Milled Rice',
            'category' => 'Grains & Cereals',
            'quantity' => 200,
            'unit' => 'kg',
            'price' => 52.00,
            'barangay' => 'Linotan',
            'status' => 'active',
        ]);

        Livewire::actingAs($farmer)
            ->test(MunicipalMarketplaceDashboard::class, ['viewMode' => 'overview'])
            ->assertSee('Native Milled Rice')
            ->assertSee('₱52.00')
            ->assertSee('Prevailing Spot Price Index')
            ->assertSee('Active Harvest Registry')
            ->assertSee('Trade Inquiries');
    }

    public function test_can_view_dedicated_price_index_page(): void
    {
        $farmer = User::factory()->create(['role' => 'farmer']);

        Product::create([
            'user_id' => $farmer->id,
            'name' => 'Native Milled Rice',
            'category' => 'Grains & Cereals',
            'quantity' => 100,
            'unit' => 'kg',
            'price' => 52.00,
            'barangay' => 'Linotan',
            'status' => 'active',
        ]);

        Livewire::actingAs($farmer)
            ->test(MunicipalMarketplaceDashboard::class, ['viewMode' => 'price_index'])
            ->assertSee('Municipal Commodity Spot Market Price Index')
            ->assertSee('Repository D3 • UC-06')
            ->assertSee('Mathematical Mean: P_avg = ΣP / N');
    }

    public function test_can_view_dedicated_harvest_registry_page(): void
    {
        $farmer = User::factory()->create(['role' => 'farmer', 'barangay' => 'Linotan']);

        Product::create([
            'user_id' => $farmer->id,
            'name' => 'Native Milled Rice',
            'category' => 'Grains & Cereals',
            'quantity' => 100,
            'unit' => 'kg',
            'price' => 52.00,
            'barangay' => 'Linotan',
            'status' => 'active',
        ]);

        Livewire::actingAs($farmer)
            ->test(MunicipalMarketplaceDashboard::class, ['viewMode' => 'harvest_registry'])
            ->assertSee('Active Harvest Produce Registry Across Cantilan')
            ->assertSee('Repository D2 • UC-03')
            ->assertSee('Native Milled Rice');
    }

    public function test_adding_harvest_listing_automatically_generates_price_snapshot_in_d3(): void
    {
        $farmer = User::factory()->create([
            'role' => 'farmer',
            'barangay' => 'Calagdaan',
            'status' => 'verified',
        ]);

        Livewire::actingAs($farmer)
            ->test(MunicipalMarketplaceDashboard::class)
            ->set('new_name', 'Highland Sweet Potato')
            ->set('new_category', 'Root Crops')
            ->set('new_quantity', 150)
            ->set('new_unit', 'kg')
            ->set('new_price', 35.00)
            ->set('new_barangay', 'Calagdaan')
            ->set('new_pickup_location', 'Calagdaan Purok 1')
            ->call('createProduct')
            ->assertHasNoErrors();

        // Assert Listing stored in Repository D2 (listings table)
        $this->assertDatabaseHas('listings', [
            'farmer_id' => $farmer->id,
            'title' => 'Highland Sweet Potato',
            'price_per_unit' => 35.00,
            'available_quantity' => 150,
            'status' => 'active',
        ]);

        // Assert PriceRecord snapshot automatically logged in Repository D3 (price_records table)
        $this->assertDatabaseHas('price_records', [
            'prevailing_price' => 35.00,
            'market_location' => 'Cantilan Public Market',
        ]);
    }

    public function test_buyer_cannot_create_harvest_listing_due_to_rbac(): void
    {
        $buyer = User::factory()->create([
            'role' => 'buyer',
            'barangay' => 'Poblacion',
        ]);

        Livewire::actingAs($buyer)
            ->test(MunicipalMarketplaceDashboard::class)
            ->set('new_name', 'Unauthorized Corn')
            ->set('new_category', 'Grains & Cereals')
            ->set('new_quantity', 100)
            ->set('new_unit', 'kg')
            ->set('new_price', 40.00)
            ->set('new_barangay', 'Poblacion')
            ->call('createProduct')
            ->assertForbidden();
    }

    public function test_buyer_can_submit_trade_inquiry_pre_order_to_farmer(): void
    {
        $farmer = User::factory()->create([
            'role' => 'farmer',
            'barangay' => 'Linotan',
        ]);

        $buyer = User::factory()->create([
            'role' => 'buyer',
            'barangay' => 'Poblacion',
        ]);

        $product = Product::create([
            'user_id' => $farmer->id,
            'name' => 'Freshwater Tilapia',
            'category' => 'Aquaculture',
            'quantity' => 80,
            'unit' => 'kg',
            'price' => 140.00,
            'barangay' => 'Linotan',
            'status' => 'active',
        ]);

        Livewire::actingAs($buyer)
            ->test(MunicipalMarketplaceDashboard::class)
            ->call('openInquiryModal', $product->id)
            ->set('inquiry_quantity', 25)
            ->set('inquiry_pickup_date', now()->addDays(3)->format('Y-m-d'))
            ->set('inquiry_message', 'Need 25kg for Friday restaurant order in Poblacion.')
            ->call('submitInquiry')
            ->assertHasNoErrors();

        // Assert Inquiry saved in Repository D4 (inquiries and inquiry_messages tables)
        $this->assertDatabaseHas('inquiries', [
            'buyer_id' => $buyer->id,
            'listing_id' => $product->id,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('inquiry_messages', [
            'sender_id' => $buyer->id,
            'message' => 'Need 25kg for Friday restaurant order in Poblacion.',
        ]);
    }

    public function test_farmer_cannot_inquire_on_own_produce_due_to_rbac(): void
    {
        $farmer = User::factory()->create([
            'role' => 'farmer',
            'barangay' => 'Linotan',
        ]);

        $product = Product::create([
            'user_id' => $farmer->id,
            'name' => 'Native Milled Rice',
            'category' => 'Grains & Cereals',
            'quantity' => 100,
            'unit' => 'kg',
            'price' => 52.00,
            'barangay' => 'Linotan',
            'status' => 'active',
        ]);

        Livewire::actingAs($farmer)
            ->test(MunicipalMarketplaceDashboard::class)
            ->call('openInquiryModal', $product->id)
            ->assertForbidden();
    }

    public function test_admin_can_verify_pending_smallholder_producer(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pendingFarmer = User::factory()->create([
            'role' => 'farmer',
            'status' => 'pending',
            'barangay' => 'Tigabong',
        ]);

        Livewire::actingAs($admin)
            ->test(MunicipalMarketplaceDashboard::class)
            ->call('verifyFarmer', $pendingFarmer->id)
            ->assertHasNoErrors();

        $this->assertEquals('verified', $pendingFarmer->fresh()->verification_status);
        $this->assertEquals('verified', $pendingFarmer->fresh()->status);
    }

    public function test_non_admin_cannot_verify_smallholder_producer(): void
    {
        $buyer = User::factory()->create(['role' => 'buyer']);
        $pendingFarmer = User::factory()->create([
            'role' => 'farmer',
            'status' => 'pending',
        ]);

        Livewire::actingAs($buyer)
            ->test(MunicipalMarketplaceDashboard::class)
            ->call('verifyFarmer', $pendingFarmer->id)
            ->assertForbidden();
    }

    public function test_spot_price_index_calculates_mathematical_average_formula(): void
    {
        $farmer1 = User::factory()->create(['role' => 'farmer', 'barangay' => 'Linotan']);
        $farmer2 = User::factory()->create(['role' => 'farmer', 'barangay' => 'Buntalid']);

        // Listing 1: 50.00
        Product::create([
            'user_id' => $farmer1->id,
            'name' => 'Dinorado Rice',
            'category' => 'Grains & Cereals',
            'quantity' => 100,
            'unit' => 'kg',
            'price' => 50.00,
            'barangay' => 'Linotan',
            'status' => 'active',
        ]);

        // Listing 2: 54.00
        Product::create([
            'user_id' => $farmer2->id,
            'name' => 'Dinorado Rice',
            'category' => 'Grains & Cereals',
            'quantity' => 100,
            'unit' => 'kg',
            'price' => 54.00,
            'barangay' => 'Buntalid',
            'status' => 'active',
        ]);

        // Expected P_avg = (50 + 54) / 2 = 52.00
        $component = Livewire::actingAs($farmer1)->test(MunicipalMarketplaceDashboard::class);
        $summary = $component->get('priceIndexSummary');

        $riceSummary = $summary->firstWhere('name', 'Dinorado Rice');
        $this->assertNotNull($riceSummary);
        $this->assertEquals(52.00, $riceSummary['p_avg']);
        $this->assertEquals(50.00, $riceSummary['p_min']);
        $this->assertEquals(54.00, $riceSummary['p_max']);
        $this->assertEquals(2, $riceSummary['samples']);
    }

    public function test_farmer_can_edit_own_harvest_listing(): void
    {
        $farmer = User::factory()->create(['role' => 'farmer', 'barangay' => 'Linotan']);

        $product = Product::create([
            'user_id' => $farmer->id,
            'name' => 'Organic Dinorado Rice',
            'category' => 'Grains & Cereals',
            'quantity' => 200,
            'unit' => 'kg',
            'price' => 52.00,
            'barangay' => 'Linotan',
            'status' => 'active',
        ]);

        Livewire::actingAs($farmer)
            ->test(MunicipalMarketplaceDashboard::class)
            ->call('openEditProductModal', $product->id)
            ->assertSet('edit_title', 'Organic Dinorado Rice')
            ->assertSet('edit_price_per_unit', 52.00)
            ->set('edit_title', 'Premium Organic Dinorado Rice')
            ->set('edit_price_per_unit', 56.00)
            ->set('edit_available_quantity', 180)
            ->set('edit_status', 'active')
            ->call('updateProduct')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('listings', [
            'listing_id' => $product->id,
            'title' => 'Premium Organic Dinorado Rice',
            'price_per_unit' => 56.00,
            'available_quantity' => 180,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action_type' => 'listing_updated',
            'target_id' => $product->id,
        ]);
    }

    public function test_buyer_cannot_edit_farmers_harvest_listing(): void
    {
        $farmer = User::factory()->create(['role' => 'farmer', 'barangay' => 'Linotan']);
        $buyer = User::factory()->create(['role' => 'buyer', 'barangay' => 'Poblacion']);

        $product = Product::create([
            'user_id' => $farmer->id,
            'name' => 'Organic Dinorado Rice',
            'category' => 'Grains & Cereals',
            'quantity' => 200,
            'unit' => 'kg',
            'price' => 52.00,
            'barangay' => 'Linotan',
            'status' => 'active',
        ]);

        Livewire::actingAs($buyer)
            ->test(MunicipalMarketplaceDashboard::class)
            ->call('openEditProductModal', $product->id)
            ->assertForbidden();
    }

    public function test_farmer_can_remove_own_harvest_listing(): void
    {
        $farmer = User::factory()->create(['role' => 'farmer', 'barangay' => 'Linotan']);

        $product = Product::create([
            'user_id' => $farmer->id,
            'name' => 'Batch To Archive',
            'category' => 'Grains & Cereals',
            'quantity' => 100,
            'unit' => 'kg',
            'price' => 45.00,
            'barangay' => 'Linotan',
            'status' => 'active',
        ]);

        Livewire::actingAs($farmer)
            ->test(MunicipalMarketplaceDashboard::class)
            ->call('removeListing', $product->id)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('listings', [
            'listing_id' => $product->id,
            'status' => 'archived',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action_type' => 'listing_archived',
            'target_id' => $product->id,
        ]);
    }

    public function test_admin_can_edit_and_remove_any_harvest_listing(): void
    {
        $farmer = User::factory()->create(['role' => 'farmer', 'barangay' => 'Linotan']);
        $admin = User::factory()->create(['role' => 'admin']);

        $product = Product::create([
            'user_id' => $farmer->id,
            'name' => 'Admin Supervised Batch',
            'category' => 'Grains & Cereals',
            'quantity' => 150,
            'unit' => 'kg',
            'price' => 48.00,
            'barangay' => 'Linotan',
            'status' => 'active',
        ]);

        // Admin Edit
        Livewire::actingAs($admin)
            ->test(MunicipalMarketplaceDashboard::class)
            ->call('openEditProductModal', $product->id)
            ->set('edit_title', 'Admin Modified Batch')
            ->set('edit_price_per_unit', 49.00)
            ->set('edit_available_quantity', 150)
            ->set('edit_status', 'active')
            ->call('updateProduct')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('listings', [
            'listing_id' => $product->id,
            'title' => 'Admin Modified Batch',
            'price_per_unit' => 49.00,
        ]);

        // Admin Remove
        Livewire::actingAs($admin)
            ->test(MunicipalMarketplaceDashboard::class)
            ->call('removeListing', $product->id)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('listings', [
            'listing_id' => $product->id,
            'status' => 'archived',
        ]);
    }

    public function test_buyer_sees_tailored_buyer_dashboard_overview(): void
    {
        $farmer = User::factory()->create(['role' => 'farmer', 'barangay' => 'Linotan']);
        $buyer = User::factory()->create(['role' => 'buyer', 'barangay' => 'Poblacion']);

        Product::create([
            'user_id' => $farmer->id,
            'name' => 'Dinorado Rice',
            'category' => 'Grains & Cereals',
            'quantity' => 150,
            'unit' => 'kg',
            'price' => 52.00,
            'barangay' => 'Linotan',
            'status' => 'active',
        ]);

        Livewire::actingAs($buyer)
            ->test(MunicipalMarketplaceDashboard::class, ['viewMode' => 'overview'])
            ->assertSee('Commercial Buyer')
            ->assertSee('My Pre-Orders')
            ->assertSee('Available Batches')
            ->assertSee('Fresh Produce Arrivals in Cantilan')
            ->assertSee('Pre-Order')
            ->assertDontSee('My Harvest Listings');
    }

    public function test_admin_sees_executive_municipal_dashboard_overview(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pendingFarmer = User::factory()->create([
            'role' => 'farmer',
            'status' => 'pending',
            'barangay' => 'Calagdaan',
        ]);

        Livewire::actingAs($admin)
            ->test(MunicipalMarketplaceDashboard::class, ['viewMode' => 'overview'])
            ->assertSee('Municipal Administrator')
            ->assertSee('Pending Producer Accreditations')
            ->assertSee('Active Municipal Harvest Listings')
            ->assertSee('Verify DA-RSBSA');
    }

    public function test_harvest_registry_table_paginates_listings_across_pages(): void
    {
        $farmer = User::factory()->create(['role' => 'farmer', 'barangay' => 'Linotan']);

        for ($i = 1; $i <= 15; $i++) {
            Product::create([
                'user_id' => $farmer->id,
                'name' => sprintf('Cassava Batch #%02d', $i),
                'category' => 'Root Crops',
                'quantity' => 100 + $i,
                'unit' => 'kg',
                'price' => 25.00 + $i,
                'barangay' => 'Linotan',
                'status' => 'active',
            ]);
        }

        $component = Livewire::actingAs($farmer)
            ->test(MunicipalMarketplaceDashboard::class, ['viewMode' => 'harvest_registry'])
            ->assertSee('Showing 1 to 8 of 15 harvest batches')
            ->assertSee('Cassava Batch #15')
            ->call('gotoPage', 2, 'harvestPage')
            ->assertSee('Showing 9 to 15 of 15 harvest batches')
            ->assertSee('Cassava Batch #01');

        // Testing search reset
        $component->set('search', 'Batch #12')
            ->assertSee('Cassava Batch #12')
            ->assertSee('Showing 1 to 1 of 1 harvest batches');
    }

    public function test_farmer_my_harvest_listings_table_paginates_on_overview(): void
    {
        $farmer = User::factory()->create(['role' => 'farmer', 'barangay' => 'Linotan']);

        for ($i = 1; $i <= 7; $i++) {
            Product::create([
                'user_id' => $farmer->id,
                'name' => sprintf('Eggplant Variety #%02d', $i),
                'category' => 'Vegetables',
                'quantity' => 50,
                'unit' => 'kg',
                'price' => 30.00,
                'barangay' => 'Linotan',
                'status' => 'active',
            ]);
        }

        Livewire::actingAs($farmer)
            ->test(MunicipalMarketplaceDashboard::class, ['viewMode' => 'overview'])
            ->assertSee('Showing 1 to 5 of 7 harvest listings')
            ->assertSee('Eggplant Variety #07')
            ->call('gotoPage', 2, 'myHarvestPage')
            ->assertSee('Showing 6 to 7 of 7 harvest listings')
            ->assertSee('Eggplant Variety #01');
    }

    public function test_trade_inquiries_table_paginates_correctly(): void
    {
        $farmer = User::factory()->create(['role' => 'farmer', 'barangay' => 'Linotan']);
        $buyer = User::factory()->create(['role' => 'buyer', 'barangay' => 'Poblacion']);

        $product = Product::create([
            'user_id' => $farmer->id,
            'name' => 'Carabao Mango',
            'category' => 'Fruits',
            'quantity' => 500,
            'unit' => 'kg',
            'price' => 85.00,
            'barangay' => 'Linotan',
            'status' => 'active',
        ]);

        for ($i = 1; $i <= 7; $i++) {
            $inquiry = Inquiry::create([
                'buyer_id' => $buyer->user_id,
                'listing_id' => $product->id,
                'status' => 'pending',
            ]);
            $inquiry->messages()->create([
                'sender_id' => $buyer->user_id,
                'message' => "Order inquiry #{$i} for 10 kg",
            ]);
        }

        Livewire::actingAs($farmer)
            ->test(MunicipalMarketplaceDashboard::class, ['viewMode' => 'overview'])
            ->assertSee('Showing 1 to 5 of 7 pre-orders')
            ->call('gotoPage', 2, 'overviewInquiryPage')
            ->assertSee('Showing 6 to 7 of 7 pre-orders');
    }

    public function test_farmer_can_prompt_remove_modal_and_cancel(): void
    {
        $farmer = User::factory()->create(['role' => 'farmer', 'barangay' => 'Linotan']);

        $product = Product::create([
            'user_id' => $farmer->id,
            'name' => 'Organic Squash',
            'category' => 'Vegetables',
            'quantity' => 150,
            'unit' => 'kg',
            'price' => 35.00,
            'barangay' => 'Linotan',
            'status' => 'active',
        ]);

        Livewire::actingAs($farmer)
            ->test(MunicipalMarketplaceDashboard::class, ['viewMode' => 'overview'])
            ->call('confirmRemoveListing', $product->id, 'Organic Squash')
            ->assertSet('showRemoveModal', true)
            ->assertSet('listingToRemoveId', $product->id)
            ->assertSet('listingToRemoveTitle', 'Organic Squash')
            ->call('cancelRemoveListing')
            ->assertSet('showRemoveModal', false)
            ->assertSet('listingToRemoveId', null);

        $listing = Listing::find($product->id);
        $this->assertEquals('active', $listing->status);
    }

    public function test_farmer_can_remove_listing_via_confirmation_modal(): void
    {
        $farmer = User::factory()->create(['role' => 'farmer', 'barangay' => 'Linotan']);

        $product = Product::create([
            'user_id' => $farmer->id,
            'name' => 'Organic Ampalaya',
            'category' => 'Vegetables',
            'quantity' => 80,
            'unit' => 'kg',
            'price' => 60.00,
            'barangay' => 'Linotan',
            'status' => 'active',
        ]);

        Livewire::actingAs($farmer)
            ->test(MunicipalMarketplaceDashboard::class, ['viewMode' => 'overview'])
            ->call('confirmRemoveListing', $product->id, 'Organic Ampalaya')
            ->call('executeRemoveListing')
            ->assertSet('showRemoveModal', false);

        $listing = Listing::find($product->id);
        $this->assertEquals('archived', $listing->status);
    }
}

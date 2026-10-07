<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Listing extends Model
{
    use HasFactory;

    protected $table = 'listings';
    protected $primaryKey = 'listing_id';

    protected $fillable = [
        'farmer_id',
        'commodity_id',
        'title',
        'description',
        'price_per_unit',
        'available_quantity',
        'status', // active, sold_out, archived, pending_review
    ];

    protected function casts(): array
    {
        return [
            'price_per_unit' => 'decimal:2',
            'available_quantity' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (Listing $listing) {
            if ($listing->wasRecentlyCreated || $listing->wasChanged('price_per_unit')) {
                $commodityListings = Listing::where('commodity_id', $listing->commodity_id)
                    ->where('status', 'active')
                    ->pluck('price_per_unit');

                $minPrice = $commodityListings->min() ?? $listing->price_per_unit;
                $maxPrice = $commodityListings->max() ?? $listing->price_per_unit;

                PriceRecord::create([
                    'commodity_id' => $listing->commodity_id,
                    'prevailing_price' => $listing->price_per_unit,
                    'min_price' => $minPrice,
                    'max_price' => $maxPrice,
                    'market_location' => 'Cantilan Public Market',
                    'recorded_date' => now()->toDateString(),
                ]);
            }
        });
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id', 'user_id');
    }

    public function commodity(): BelongsTo
    {
        return $this->belongsTo(Commodity::class, 'commodity_id', 'commodity_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ListingImage::class, 'listing_id', 'listing_id');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ListingImage::class, 'listing_id', 'listing_id')->where('is_primary', true);
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class, 'listing_id', 'listing_id');
    }

    public function moderationReviews(): HasMany
    {
        return $this->hasMany(ModerationReview::class, 'listing_id', 'listing_id');
    }

    // Compatibility accessors and mutators
    public function getIdAttribute()
    {
        return $this->attributes['listing_id'] ?? null;
    }

    public function getUserIdAttribute()
    {
        return $this->attributes['farmer_id'] ?? null;
    }

    public function setUserIdAttribute($value): void
    {
        $this->attributes['farmer_id'] = $value;
    }

    public function getNameAttribute(): string
    {
        return $this->attributes['title'] ?? ($this->commodity?->name ?? '');
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['title'] = $value;
    }

    public function getPriceAttribute()
    {
        return $this->attributes['price_per_unit'] ?? null;
    }

    public function setPriceAttribute($value): void
    {
        $this->attributes['price_per_unit'] = $value;
    }

    public function getQuantityAttribute()
    {
        return $this->attributes['available_quantity'] ?? null;
    }

    public function setQuantityAttribute($value): void
    {
        $this->attributes['available_quantity'] = $value;
    }

    public function getUnitAttribute(): string
    {
        return $this->commodity?->unit_of_measure ?? 'kg';
    }

    public function getCategoryAttribute(): string
    {
        return $this->commodity?->category?->name ?? 'Agricultural';
    }

    public function getBarangayAttribute(): string
    {
        return $this->farmer?->farmerProfile?->farm_location ?? 'Linotan';
    }
}

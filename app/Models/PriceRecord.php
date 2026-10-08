<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceRecord extends Model
{
    use HasFactory;

    protected $table = 'price_records';

    protected $primaryKey = 'price_record_id';

    protected $fillable = [
        'commodity_id',
        'prevailing_price',
        'min_price',
        'max_price',
        'market_location',
        'recorded_date',
    ];

    protected function casts(): array
    {
        return [
            'prevailing_price' => 'decimal:2',
            'min_price' => 'decimal:2',
            'max_price' => 'decimal:2',
            'recorded_date' => 'date',
            'created_at' => 'datetime',
        ];
    }

    public function commodity(): BelongsTo
    {
        return $this->belongsTo(Commodity::class, 'commodity_id', 'commodity_id');
    }

    // Accessors for backward compatibility
    public function getPriceAttribute()
    {
        return $this->prevailing_price;
    }

    public function getCommodityNameAttribute(): string
    {
        return $this->commodity?->name ?? 'Produce';
    }

    public function getCategoryAttribute(): string
    {
        return $this->commodity?->category?->name ?? 'Agricultural';
    }

    public function getUnitAttribute(): string
    {
        return $this->commodity?->unit_of_measure ?? 'kg';
    }

    public function getBarangayAttribute(): string
    {
        return $this->market_location;
    }
}

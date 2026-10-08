<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Commodity extends Model
{
    use HasFactory;

    protected $table = 'commodities';

    protected $primaryKey = 'commodity_id';

    protected $fillable = [
        'category_id',
        'name',
        'unit_of_measure',
        'description',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function priceRecords(): HasMany
    {
        return $this->hasMany(PriceRecord::class, 'commodity_id', 'commodity_id');
    }

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class, 'commodity_id', 'commodity_id');
    }

    public function latestPriceRecord(): HasOne
    {
        return $this->hasOne(PriceRecord::class, 'commodity_id', 'commodity_id')->latestOfMany('created_at');
    }
}

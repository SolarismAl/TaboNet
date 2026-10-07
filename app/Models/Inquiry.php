<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Inquiry extends Model
{
    use HasFactory;

    protected $table = 'inquiries';
    protected $primaryKey = 'inquiry_id';

    protected $fillable = [
        'listing_id',
        'buyer_id',
        'status', // pending, accepted, declined, completed
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class, 'listing_id', 'listing_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id', 'user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(InquiryMessage::class, 'inquiry_id', 'inquiry_id');
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(InquiryMessage::class, 'inquiry_id', 'inquiry_id')->latestOfMany('sent_at');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'inquiry_id', 'inquiry_id');
    }

    // Compatibility accessors and aliases
    public function getIdAttribute()
    {
        return $this->attributes['inquiry_id'] ?? null;
    }

    public function getProductIdAttribute()
    {
        return $this->attributes['listing_id'] ?? null;
    }

    public function setProductIdAttribute($value): void
    {
        $this->attributes['listing_id'] = $value;
    }

    public function getFarmerIdAttribute()
    {
        return $this->listing?->farmer_id;
    }

    public function getFarmerAttribute()
    {
        return $this->listing?->farmer;
    }

    public function getProductAttribute()
    {
        return $this->listing;
    }

    public function getMessageAttribute(): string
    {
        return $this->latestMessage?->message ?? '';
    }

    public function getQuantityAttribute()
    {
        if (preg_match('/(\d+(?:\.\d+)?)\s*(?:kg|kaing|sako|bunch|units?)/i', $this->message, $matches)) {
            return (float) $matches[1];
        }
        return $this->listing?->available_quantity ?? 10;
    }

    public function getPickupDateAttribute()
    {
        return $this->created_at ? $this->created_at->addDays(2) : now()->addDays(2);
    }
}

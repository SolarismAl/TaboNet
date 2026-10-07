<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmerProfile extends Model
{
    use HasFactory;

    protected $table = 'farmer_profiles';
    protected $primaryKey = 'profile_id';

    protected $fillable = [
        'user_id',
        'farm_name',
        'farm_location',
        'farm_type',
        'valid_id_url',
        'bio',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}

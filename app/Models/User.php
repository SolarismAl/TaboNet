<?php

namespace App\Models;

use App\Concerns\HasTeams;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Passkeys\Passkeys;

/**
 * @property int $user_id
 * @property string $role
 * @property string $full_name
 * @property string $name
 * @property string $email
 * @property string $password_hash
 * @property string|null $phone_number
 * @property string $verification_status
 * @property Carbon|null $email_verified_at
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Team|null $currentTeam
 * @property-read Collection<int, Team> $ownedTeams
 * @property-read Collection<int, Membership> $teamMemberships
 * @property-read Collection<int, Team> $teams
 * @property-read FarmerProfile|null $farmerProfile
 * @property-read BuyerProfile|null $buyerProfile
 */
class User extends Authenticatable implements MustVerifyEmail, PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasTeams, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

    protected $table = 'users';

    protected $primaryKey = 'user_id';

    public const BARANGAYS = [
        'Bugsukan',
        'Buntalid',
        'Cabangahan',
        'Cabas-an',
        'Calagdaan',
        'Consuelo',
        'General Island',
        'Linotan',
        'Lobo',
        'Magosilom',
        'Pag-Antayan',
        'Palasao',
        'Parang',
        'Poblacion',
        'San Pedro',
        'Tapi',
        'Tigabong',
    ];

    protected $fillable = [
        'role',
        'full_name',
        'name',
        'email',
        'password_hash',
        'password',
        'phone_number',
        'verification_status',
        'status',
        'email_verified_at',
        'current_team_id',
    ];

    protected $hidden = [
        'password_hash',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    // Fortify Authentication overrides
    public function getAuthIdentifierName(): string
    {
        return 'user_id';
    }

    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    public function getAuthPassword(): string
    {
        return (string) ($this->password_hash ?? '');
    }

    // Legacy attribute accessors and mutators
    public function getIdAttribute()
    {
        return $this->attributes['user_id'] ?? null;
    }

    public function getNameAttribute(): string
    {
        return $this->attributes['full_name'] ?? '';
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['full_name'] = $value;
    }

    public function getPasswordAttribute(): string
    {
        return $this->attributes['password_hash'] ?? '';
    }

    public function setPasswordAttribute($value): void
    {
        $this->attributes['password_hash'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    public function getStatusAttribute(): string
    {
        $status = $this->attributes['verification_status'] ?? 'pending';
        if ($status === 'pending' && ($this->role ?? '') === 'farmer') {
            return 'pending_verification';
        }

        return $status;
    }

    public function setStatusAttribute($value): void
    {
        if ($value === 'pending_verification') {
            $this->attributes['verification_status'] = 'pending';
        } else {
            $this->attributes['verification_status'] = $value;
        }
    }

    public ?string $temp_barangay = null;

    public ?string $temp_rsbsa = null;

    public function getBarangayAttribute(): ?string
    {
        return $this->farmerProfile?->farm_location
            ?? $this->buyerProfile?->delivery_address
            ?? $this->temp_barangay;
    }

    public function setBarangayAttribute($value): void
    {
        $this->temp_barangay = $value;
        if ($this->exists && $this->relationLoaded('farmerProfile') && $this->farmerProfile) {
            $this->farmerProfile->farm_location = $value;
            $this->farmerProfile->save();
        } elseif ($this->exists && $this->relationLoaded('buyerProfile') && $this->buyerProfile) {
            $this->buyerProfile->delivery_address = $value;
            $this->buyerProfile->save();
        }
    }

    public function getRsbsaNumberAttribute(): ?string
    {
        return $this->farmerProfile?->valid_id_url ?? $this->temp_rsbsa;
    }

    public function setRsbsaNumberAttribute($value): void
    {
        $this->temp_rsbsa = $value;
        if ($this->exists && $this->relationLoaded('farmerProfile') && $this->farmerProfile) {
            $this->farmerProfile->valid_id_url = $value;
            $this->farmerProfile->save();
        }
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        $initials = Str::initials($this->name ?: $this->full_name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }

    public function isFarmer(): bool
    {
        return $this->role === 'farmer';
    }

    public function isBuyer(): bool
    {
        return $this->role === 'buyer';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSuspended(): bool
    {
        return $this->verification_status === 'rejected';
    }

    // Relationships
    public function farmerProfile(): HasOne
    {
        return $this->hasOne(FarmerProfile::class, 'user_id', 'user_id');
    }

    public function buyerProfile(): HasOne
    {
        return $this->hasOne(BuyerProfile::class, 'user_id', 'user_id');
    }

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class, 'farmer_id', 'user_id');
    }

    public function buyerInquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class, 'buyer_id', 'user_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id', 'user_id');
    }

    public function moderationReviews(): HasMany
    {
        return $this->hasMany(ModerationReview::class, 'admin_id', 'user_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'admin_id', 'user_id');
    }

    public function passkeys(): HasMany
    {
        return $this->hasMany(Passkeys::passkeyModel(), 'user_id', 'user_id');
    }
}

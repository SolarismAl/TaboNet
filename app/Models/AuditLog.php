<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';
    protected $primaryKey = 'log_id';

    protected $fillable = [
        'admin_id',
        'action_type',
        'target_entity',
        'target_id',
        'details',
    ];

    protected function casts(): array
    {
        return [
            'details' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id', 'user_id');
    }

    public static function log(?int $adminId, string $actionType, string $targetEntity, int $targetId, ?array $details = null): self
    {
        return static::create([
            'admin_id' => $adminId,
            'action_type' => $actionType,
            'target_entity' => $targetEntity,
            'target_id' => $targetId,
            'details' => $details,
        ]);
    }
}

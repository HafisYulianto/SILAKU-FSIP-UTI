<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataApprovalRequest extends Model
{
    protected $fillable = [
        'type',
        'action',
        'status',
        'entity_id',
        'record_id',
        'alumni_id',
        'payload',
        'requester_id',
        'requester_name',
        'requester_role',
        'rejection_reason',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function entity(): BelongsTo
    {
        return $this->belongsTo(DynamicEntity::class, 'entity_id');
    }

    public function record(): BelongsTo
    {
        return $this->belongsTo(DynamicRecord::class, 'record_id');
    }

    public function alumni(): BelongsTo
    {
        return $this->belongsTo(Alumni::class, 'alumni_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Get a human-readable description of the request.
     */
    public function getDescriptionAttribute(): string
    {
        $actionLabel = $this->action === 'create' ? 'Tambah data' : 'Hapus data';
        $typeLabel = match($this->type) {
            'alumni' => 'Alumni',
            'record' => $this->entity?->name ?? 'Kategori',
            default => '-',
        };

        if ($this->action === 'create' && $this->type === 'alumni') {
            $nama = $this->payload['nama'] ?? '-';
            return "{$actionLabel} Alumni: \"{$nama}\"";
        }

        if ($this->action === 'delete' && $this->type === 'alumni') {
            $nama = $this->alumni?->nama ?? '-';
            return "{$actionLabel} Alumni: \"{$nama}\"";
        }

        if ($this->action === 'delete' && $this->type === 'record') {
            return "{$actionLabel} pada \"{$typeLabel}\"";
        }

        if ($this->action === 'create' && $this->type === 'record') {
            return "{$actionLabel} pada \"{$typeLabel}\"";
        }

        return "{$actionLabel} {$typeLabel}";
    }
}

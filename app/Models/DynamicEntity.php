<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DynamicEntity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'root_category',
        'parent_id',
        'created_by',
        'icon',
        'is_active',
        'sort_order',
        'approval_status',
        'rejection_reason',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($entity) {
            if (empty($entity->slug)) {
                $entity->slug = Str::slug($entity->name);
            }
            // Ensure unique slug
            $originalSlug = $entity->slug;
            $count = 1;
            while (static::where('slug', $entity->slug)->exists()) {
                $entity->slug = $originalSlug . '-' . $count++;
            }
        });
    }

    public function fields()
    {
        return $this->hasMany(DynamicField::class, 'entity_id')->orderBy('sort_order');
    }

    public function records()
    {
        return $this->hasMany(DynamicRecord::class, 'entity_id');
    }

    public function parent()
    {
        return $this->belongsTo(DynamicEntity::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(DynamicEntity::class, 'parent_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getRecordCountAttribute(): int
    {
        return $this->records()->count();
    }

    public function getAggregatableFields()
    {
        return $this->fields()->where('is_aggregatable', true)->get();
    }

    public function getFilterableFields()
    {
        return $this->fields()->where('is_filterable', true)->get();
    }

    public function getTableFields()
    {
        return $this->fields()->where('show_in_table', true)->get();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('approval_status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->whereIn('approval_status', ['pending', 'pending_delete']);
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('root_category', $category);
    }

    public function scopeRootOnly($query)
    {
        return $query->whereNull('parent_id');
    }

    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }

    public function isPendingDelete(): bool
    {
        return $this->approval_status === 'pending_delete';
    }

    public function isRejected(): bool
    {
        return $this->approval_status === 'rejected';
    }

    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }
}

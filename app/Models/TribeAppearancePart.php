<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TribeAppearancePart extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tribe_id',
        'part_type',
        'name',
        'image_url',
        'is_default',
        'is_active',
        'display_order',
        'description',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the tribe that owns this appearance part
     */
    public function tribe()
    {
        return $this->belongsTo(Tribe::class);
    }

    /**
     * Scope: Get parts by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('part_type', $type);
    }

    /**
     * Scope: Get active parts only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get default parts
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope: Get parts for specific tribe
     */
    public function scopeForTribe($query, $tribeId)
    {
        return $query->where('tribe_id', $tribeId);
    }

    /**
     * Get full image URL
     */
    public function getFullImageUrlAttribute()
    {
        if (filter_var($this->image_url, FILTER_VALIDATE_URL)) {
            return $this->image_url;
        }
        return asset('storage/' . $this->image_url);
    }
}

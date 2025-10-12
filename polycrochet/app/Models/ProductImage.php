<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'path',
        'disk',
        'alt_text',
        'position',
        'is_primary',
        'metadata',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'position' => 'integer',
        'is_primary' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Relationship: owning product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the accessible URL for the stored image.
     */
    public function getUrlAttribute(): ?string
    {
        if (! $this->path) {
            return null;
        }

        if ($this->disk === 'supabase') {
            $base = rtrim(config('services.supabase.public_url'), '/');

            return $base ? $base . '/' . ltrim($this->path, '/') : null;
        }

        return Storage::disk($this->disk ?? config('filesystems.default'))->url($this->path);
    }
}

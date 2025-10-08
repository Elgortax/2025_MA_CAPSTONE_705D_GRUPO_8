<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'region_id',
        'commune_id',
        'street',
        'number',
        'apartment',
        'reference',
        'postal_code',
        'is_default',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_default' => 'bool',
    ];

    /**
     * Owner of the address.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Region related to the address.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Commune related to the address.
     */
    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class);
    }

    /**
     * Scope to retrieve the default address.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}

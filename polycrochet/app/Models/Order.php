<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'order_number',
        'user_id',
        'status',
        'items_total',
        'shipping_total',
        'total',
        'currency',
        'paid_at',
        'shipped_at',
        'billing_data',
        'shipping_data',
        'metadata',
    ];

    protected $casts = [
        'items_total' => 'decimal:2',
        'shipping_total' => 'decimal:2',
        'total' => 'decimal:2',
        'billing_data' => 'array',
        'shipping_data' => 'array',
        'metadata' => 'array',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', 'pagado');
    }
}

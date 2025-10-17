<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pendiente';
    public const STATUS_PAID = 'pagado';
    public const STATUS_IN_PRODUCTION = 'en_produccion';
    public const STATUS_SHIPPED = 'enviado';
    public const STATUS_CANCELLED = 'cancelado';

    public const STATUSES = [
        self::STATUS_PENDING => 'Pendiente',
        self::STATUS_PAID => 'Pagado',
        self::STATUS_IN_PRODUCTION => 'En producción',
        self::STATUS_SHIPPED => 'Enviado',
        self::STATUS_CANCELLED => 'Cancelado',
    ];

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

    public function notes(): HasMany
    {
        return $this->hasMany(OrderNote::class)->latest();
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function getStatusLabelAttribute(): string
    {
        return Arr::get(self::STATUSES, $this->status, Str::headline($this->status));
    }

    public function getShippingNameAttribute(): ?string
    {
        return Arr::get($this->shipping_data, 'name') ?? optional($this->user)->name;
    }

    public function getShippingAddressAttribute(): string
    {
        $street = Arr::get($this->shipping_data, 'street');
        $number = Arr::get($this->shipping_data, 'number');
        $apartment = Arr::get($this->shipping_data, 'apartment');

        return trim(collect([$street, $number, $apartment])->filter()->implode(' '));
    }

    public function getShippingLocationAttribute(): string
    {
        return trim(collect([
            Arr::get($this->shipping_data, 'commune'),
            Arr::get($this->shipping_data, 'region'),
        ])->filter()->implode(', '));
    }

    public function getShippingPhoneAttribute(): ?string
    {
        return Arr::get($this->shipping_data, 'phone') ?? Arr::get($this->billing_data, 'phone');
    }

    public function addNote(string $note, ?User $author = null): OrderNote
    {
        return $this->notes()->create([
            'note' => $note,
            'user_id' => $author?->id,
        ]);
    }

    public function updateStatus(string $status): void
    {
        $validStatus = array_key_exists($status, self::STATUSES)
            ? $status
            : self::STATUS_PENDING;

        $attributes = ['status' => $validStatus];

        if ($validStatus === self::STATUS_PAID && ! $this->paid_at) {
            $attributes['paid_at'] = now();
        }

        if ($validStatus === self::STATUS_SHIPPED) {
            $attributes['shipped_at'] = $attributes['shipped_at'] ?? now();
        }

        $this->fill($attributes)->save();
    }
}


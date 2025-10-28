<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartManager
{
    /**
     * Cached collection of cart items for the current request.
     *
     * @var Collection<int, array<string, mixed>>|null
     */
    protected ?Collection $itemsCache = null;

    /**
     * Retrieve the list of items currently in the cart.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function items(): Collection
    {
        if ($this->itemsCache instanceof Collection) {
            return $this->itemsCache;
        }

        $rawItems = collect(Session::get('cart.items', []));

        if ($rawItems->isEmpty()) {
            return $this->itemsCache = collect();
        }

        $products = Product::query()
            ->active()
            ->with('primaryImage')
            ->whereIn('id', $rawItems->keys())
            ->get()
            ->keyBy('id');

        $this->itemsCache = $rawItems->map(function (array $item, int $productId) use ($products) {
            $product = $products->get($productId);

            if (! $product) {
                return null;
            }

            $quantity = max(1, min((int) ($item['quantity'] ?? 1), 10));
            $unitPrice = (float) $product->price;

            return [
                'product' => $product,
                'quantity' => $quantity,
                'price' => $unitPrice,
                'subtotal' => $unitPrice * $quantity,
            ];
        })->filter()->values();

        return $this->itemsCache;
    }

    /**
     * Cart subtotal (sum of items).
     */
    public function subtotal(): float
    {
        return $this->items()->sum('subtotal');
    }

    /**
     * Base shipping cost configured for the store.
     */
    public function shipping(): float
    {
        if ($this->items()->isEmpty()) {
            return 0.0;
        }

        /** @var \App\Support\SettingsStore $settings */
        $settings = app(SettingsStore::class);

        $rate = (float) ($settings->get('shipping.rate', 0) ?? 0);

        return max(0, $rate);
    }

    /**
     * Total amount (subtotal + shipping).
     */
    public function total(): float
    {
        if ($this->items()->isEmpty()) {
            return 0.0;
        }

        return $this->subtotal() + $this->shipping();
    }

    /**
     * Number of units in the cart.
     */
    public function count(): int
    {
        return (int) $this->items()->sum('quantity');
    }

    /**
     * Summary payload used by controllers and AJAX endpoints.
     */
    public function summary(): array
    {
        $items = $this->items();
        $subtotal = $this->subtotal();
        $shipping = $this->shipping();
        $total = $this->total();

        return [
            'items' => $items->map(function (array $item) {
                /** @var \App\Models\Product $product */
                $product = $item['product'];

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'url' => route('product.show', $product->slug),
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'price_formatted' => $this->formatAmount($item['price']),
                    'subtotal' => $item['subtotal'],
                    'subtotal_formatted' => $this->formatAmount($item['subtotal']),
                    'image' => $product->primaryImage?->url,
                ];
            })->values(),
            'subtotal' => $subtotal,
            'subtotal_formatted' => $this->formatAmount($subtotal),
            'shipping' => $shipping,
            'shipping_formatted' => $this->formatAmount($shipping),
            'total' => $total,
            'total_formatted' => $this->formatAmount($total),
            'count' => $this->count(),
        ];
    }

    /**
     * Remove all items from the cart.
     */
    public function clear(): void
    {
        Session::forget('cart.items');
        $this->forgetCachedItems();
    }

    /**
     * Forget any cached items for the current request.
     */
    public function forgetCachedItems(): void
    {
        $this->itemsCache = null;
    }

    /**
     * Format amount for human-readable display.
     */
    public function formatAmount(float $amount): string
    {
        return '$' . number_format($amount, 0, ',', '.');
    }

    /**
     * Format amount for PayPal API (two decimal places).
     */
    public function formatForPayments(float $amount): string
    {
        return number_format($amount, 2, '.', '');
    }
}

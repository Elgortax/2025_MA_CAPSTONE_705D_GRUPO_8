<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Show the cart page.
     */
    public function index(): View
    {
        $items = $this->cartItems();

        $subtotal = $items->sum('subtotal');
        $total = $subtotal;

        return view('pages.cart', [
            'items' => $items,
            'subtotal' => $subtotal,
            'total' => $total,
        ]);
    }

    /**
     * Add a product to the cart.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $product = Product::query()
            ->active()
            ->findOrFail($data['product_id']);

        $cart = session('cart.items', []);
        $currentQuantity = $cart[$data['product_id']]['quantity'] ?? 0;
        $cart[$data['product_id']]['quantity'] = min($currentQuantity + $data['quantity'], 10);

        session(['cart.items' => $cart]);

        if ($request->expectsJson()) {
            return response()->json($this->summaryPayload());
        }

        return redirect()
            ->route('cart')
            ->with('status', $product->name . ' agregado al carrito.');
    }

    /**
     * Update the quantity of an item in the cart.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $cart = session('cart.items', []);

        if (! array_key_exists($product->id, $cart)) {
            return $request->expectsJson()
                ? response()->json($this->summaryPayload(), 404)
                : redirect()->route('cart')->with('status', 'El producto no estaba en el carrito.');
        }

        $cart[$product->id]['quantity'] = $data['quantity'];
        session(['cart.items' => $cart]);

        if ($request->expectsJson()) {
            return response()->json($this->summaryPayload());
        }

        return redirect()
            ->route('cart')
            ->with('status', 'Cantidad actualizada.');
    }

    /**
     * Remove an item from the cart.
     */
    public function destroy(Request $request, Product $product)
    {
        $cart = session('cart.items', []);

        unset($cart[$product->id]);
        session(['cart.items' => $cart]);

        if ($request->expectsJson()) {
            return response()->json($this->summaryPayload());
        }

        return redirect()
            ->route('cart')
            ->with('status', 'Producto eliminado del carrito.');
    }

    /**
     * Return the cart summary as JSON.
     */
    public function summary(): JsonResponse
    {
        return response()->json($this->summaryPayload());
    }

    /**
     * Build the collection of cart items with product data.
     *
     * @return Collection<int, array<string, mixed>>
     */
    protected function cartItems(): Collection
    {
        $items = collect(session('cart.items', []));

        if ($items->isEmpty()) {
            return collect();
        }

        $products = Product::query()
            ->active()
            ->with('primaryImage')
            ->whereIn('id', $items->keys())
            ->get()
            ->keyBy('id');

        return $items->map(function (array $item, int $productId) use ($products) {
            $product = $products->get($productId);

            if (! $product) {
                return null;
            }

            $quantity = $item['quantity'] ?? 1;
            $price = (float) $product->price;

            return [
                'product' => $product,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $price * $quantity,
            ];
        })->filter()->values();
    }

    /**
     * Build a normalized payload of the cart summary.
     */
    protected function summaryPayload(): array
    {
        $items = $this->cartItems();

        $subtotal = $items->sum('subtotal');
        $total = $subtotal;

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
            'total' => $total,
            'total_formatted' => $this->formatAmount($total),
            'count' => $items->sum('quantity'),
        ];
    }

    /**
     * Format an amount for display.
     */
    protected function formatAmount(float $amount): string
    {
        return '$' . number_format($amount, 0, ',', '.');
    }
}

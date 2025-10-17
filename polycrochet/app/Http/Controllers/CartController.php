<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\CartManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartManager $cart
    ) {
    }

    /**
     * Show the cart page.
     */
    public function index(): View
    {
        $items = $this->cart->items();
        $subtotal = $this->cart->subtotal();
        $total = $this->cart->total();

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
        $this->cart->forgetCachedItems();

        if ($request->expectsJson()) {
            return response()->json($this->cart->summary());
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
        $this->cart->forgetCachedItems();

        if ($request->expectsJson()) {
            return response()->json($this->cart->summary());
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
        $this->cart->forgetCachedItems();

        if ($request->expectsJson()) {
            return response()->json($this->cart->summary());
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
        return response()->json($this->cart->summary());
    }
}

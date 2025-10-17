<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display the confirmation page for a given order.
     */
    public function show(Request $request, Order $order): View
    {
        $user = $request->user();

        if ($order->user_id && (! $user || ($user->id !== $order->user_id && ! $user->isAdmin()))) {
            abort(403);
        }

        $order->load(['items.product.primaryImage', 'user']);

        return view('pages.order-confirmation', [
            'order' => $order,
            'shipping' => $order->shipping_data ?? [],
        ]);
    }
}

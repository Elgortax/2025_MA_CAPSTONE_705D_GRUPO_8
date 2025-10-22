<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    /**
     * Display the authenticated user's order history.
     */
    public function history(Request $request): View
    {
        $user = $request->user();

        abort_unless($user, Response::HTTP_FORBIDDEN);

        $orders = $user->orders()
            ->withSum('items as total_units', 'quantity')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('pages.orders.index', [
            'orders' => $orders,
        ]);
    }

    /**
     * Display the order detail for the authenticated user.
     */
    public function historyShow(Request $request, Order $order): View
    {
        $user = $request->user();

        abort_unless($user, Response::HTTP_FORBIDDEN);

        if ($order->user_id !== $user->id) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $order->load(['items.product.primaryImage', 'user']);

        return view('pages.orders.show', [
            'order' => $order,
            'shipping' => $order->shipping_data ?? [],
        ]);
    }

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

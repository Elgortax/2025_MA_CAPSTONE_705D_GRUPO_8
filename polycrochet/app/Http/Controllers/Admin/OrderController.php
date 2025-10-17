<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOrderNoteRequest;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Models\OrderNote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query()
            ->with(['user'])
            ->withCount('items')
            ->latest();

        $search = trim((string) $request->query('q', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', '%' . $search . '%')
                            ->orWhere('last_name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        $status = trim((string) $request->query('status', ''));
        if ($status !== '') {
            $query->where('status', $status);
        }

        $orders = $query->paginate(12)->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'status' => $status,
            'search' => $search,
            'statuses' => Order::STATUSES,
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['items.product.primaryImage', 'user', 'notes.author']);

        return view('admin.orders.show', [
            'order' => $order,
            'statuses' => Order::STATUSES,
        ]);
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order)
    {
        $order->updateStatus($request->validated('status'));

        $payload = [
            'message' => 'Estado del pedido actualizado.',
            'status' => $order->status,
            'status_label' => $order->status_label,
        ];

        if ($request->expectsJson()) {
            return response()->json($payload);
        }

        return redirect()
            ->back()
            ->with('status', $payload['message']);
    }

    public function storeNote(StoreOrderNoteRequest $request, Order $order)
    {
        $note = $order->addNote(
            $request->validated('note'),
            $request->user()
        );

        $payload = [
            'message' => 'Nota agregada.',
            'note' => [
                'id' => $note->id,
                'note' => $note->note,
                'author' => $note->author?->name ?? 'Sistema',
                'created_at' => $note->created_at->diffForHumans(),
            ],
        ];

        if ($request->expectsJson()) {
            return response()->json($payload, 201);
        }

        return redirect()
            ->back()
            ->with('status', 'Nota interna agregada.');
    }

    public function destroyNote(Request $request, Order $order, OrderNote $note)
    {
        abort_unless($note->order_id === $order->id, 404);

        $note->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Nota eliminada.',
            ]);
        }

        return redirect()
            ->back()
            ->with('status', 'Nota eliminada.');
    }
}

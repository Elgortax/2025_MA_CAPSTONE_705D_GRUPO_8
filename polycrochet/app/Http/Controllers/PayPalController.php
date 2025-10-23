<?php

namespace App\Http\Controllers;

use App\Mail\Orders\OrderConfirmationCustomerMail;
use App\Mail\Orders\OrderConfirmationNotificationMail;
use App\Models\Order;
use App\Services\Payments\PayPalService;
use App\Support\CartManager;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;
use RuntimeException;

class PayPalController extends Controller
{
    public function __construct(
        protected CartManager $cart,
        protected PayPalService $paypal
    ) {
    }

    /**
     * Create a local order and redirect the user to PayPal for approval.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login')->with('status', 'Debes iniciar sesión para completar tu compra.');
        }

        $cartItems = $this->cart->items();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('status', 'Tu carrito está vacío.');
        }

        $address = $user->addresses()->where('is_default', true)->with(['region', 'commune'])->first();

        if (! $address) {
            return redirect()->route('account')->with('status', 'Necesitas registrar una dirección de envío antes de continuar.');
        }

        $order = null;

        try {
            $order = DB::transaction(function () use ($user, $address, $cartItems, $request) {
                $itemsTotal = $cartItems->sum('subtotal');
                $shippingTotal = 0;
                $total = $itemsTotal + $shippingTotal;

                $shippingData = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'street' => $address->street,
                    'number' => $address->number,
                    'apartment' => $address->apartment,
                    'reference' => $address->reference,
                    'postal_code' => $address->postal_code,
                    'commune' => $address->commune?->name,
                    'region' => $address->region?->name,
                    'country' => 'CL',
                ];

                $order = Order::create([
                    'uuid' => (string) Str::uuid(),
                    'order_number' => $this->generateOrderNumber(),
                    'user_id' => $user->id,
                    'status' => 'pendiente',
                    'items_total' => $itemsTotal,
                    'shipping_total' => $shippingTotal,
                    'total' => $total,
                    'currency' => 'CLP',
                    'billing_data' => [
                        'email' => $user->email,
                        'phone' => $user->phone,
                    ],
                    'shipping_data' => $shippingData,
                    'metadata' => [
                        'user_agent' => $request->userAgent(),
                        'ip' => $request->ip(),
                    ],
                ]);

                foreach ($cartItems as $item) {
                    /** @var \App\Models\Product $product */
                    $product = $item['product'];
                    $order->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'line_total' => $item['subtotal'],
                        'options' => [
                            'slug' => $product->slug,
                        ],
                    ]);
                }

                return $order->fresh(['items', 'user']);
            });

            $returnUrl = route('paypal.success', ['order' => $order->uuid], true);
            $cancelUrl = route('paypal.cancel', ['order' => $order->uuid], true);

            $paypalResponse = $this->paypal->createOrder($order, $returnUrl, $cancelUrl);

            $approvalLink = collect($paypalResponse['links'] ?? [])
                ->firstWhere('rel', 'approve')['href'] ?? null;

            if (! $approvalLink) {
                throw new RuntimeException('No se pudo obtener el enlace de aprobación de PayPal.');
            }

            $metadata = $order->metadata ?? [];
            Arr::set($metadata, 'paypal.order_id', $paypalResponse['id'] ?? null);
            Arr::set($metadata, 'paypal.status', $paypalResponse['status'] ?? null);
            Arr::set($metadata, 'paypal.links', $paypalResponse['links'] ?? []);
            Arr::set($metadata, 'paypal.currency', config('services.paypal.currency', 'USD'));
            Arr::set($metadata, 'paypal.conversion_rate', config('services.paypal.conversion_rate', 900));

            $order->update(['metadata' => $metadata]);

            return redirect()->away($approvalLink);
        } catch (RequestException $exception) {
            Log::error('Error comunicándose con PayPal', [
                'exception' => $exception->getMessage(),
                'response' => optional($exception->response)->json(),
            ]);

            if ($order) {
                $order->delete();
            }

            return redirect()
                ->route('paypal.error')
                ->with('status', 'No pudimos iniciar el pago con PayPal. Inténtalo nuevamente en unos minutos.');
        } catch (Throwable $exception) {
            Log::error('Error al preparar la orden para PayPal', [
                'exception' => $exception->getMessage(),
            ]);

            if ($order) {
                $order->delete();
            }

            return redirect()
                ->route('paypal.error')
                ->with('status', 'Ocurrió un problema al preparar tu pedido. Inténtalo nuevamente.');
        }
    }

    /**
     * Handle PayPal approval callback.
     */
    public function success(Request $request, string $order): RedirectResponse
    {
        $order = Order::query()
            ->where('uuid', $order)
            ->with('items', 'user')
            ->firstOrFail();

        if ($request->user()?->id !== $order->user_id) {
            return redirect()->route('paypal.error')->with('status', 'No pudimos validar tu sesión para completar el pago.');
        }

        $token = $request->query('token');

        if (! $token || $token !== data_get($order->metadata, 'paypal.order_id')) {
            return redirect()->route('paypal.error')->with('status', 'El identificador del pago no coincide.');
        }

        try {
            $capture = $this->paypal->captureOrder($token);

            $status = data_get($capture, 'status');
            $metadata = $order->metadata ?? [];
            Arr::set($metadata, 'paypal.capture', $capture);
            Arr::set($metadata, 'paypal.status', $status);

            $order->update([
                'status' => 'pagado',
                'paid_at' => now(),
                'metadata' => $metadata,
            ]);

            $this->cart->clear();

            $this->sendOrderEmails($order);

            return redirect()
                ->route('order.confirmation', ['order' => $order->uuid])
                ->with('status', 'Pago confirmado correctamente.');
        } catch (RequestException $exception) {
            Log::error('Error capturando pago PayPal', [
                'order_id' => $order->id,
                'exception' => $exception->getMessage(),
                'response' => optional($exception->response)->json(),
            ]);

            return redirect()
                ->route('paypal.error')
                ->with('status', 'PayPal no pudo confirmar el pago. Intenta nuevamente.');
        }
    }

    /**
     * Handle cancellation callback from PayPal.
     */
    public function cancel(Request $request, string $order): RedirectResponse
    {
        $order = Order::query()
            ->where('uuid', $order)
            ->first();

        if ($order) {
            $metadata = $order->metadata ?? [];
            Arr::set($metadata, 'paypal.cancelled_at', now()->toIso8601String());
            Arr::set($metadata, 'paypal.cancel_token', $request->query('token'));

            $order->update([
                'status' => 'cancelado',
                'metadata' => $metadata,
            ]);
        }

        return redirect()
            ->route('checkout')
            ->with('status', 'El pago fue cancelado. Puedes intentarlo nuevamente cuando quieras.');
    }

    /**
     * Display a generic error page for PayPal issues.
     */
    public function error(): View
    {
        return view('pages.paypal.error');
    }

    protected function generateOrderNumber(): string
    {
        $prefix = 'PC';
        $sequence = now()->format('Ymd');
        $random = strtoupper(Str::random(5));

        return "{$prefix}-{$sequence}-{$random}";
    }

    /**
     * Send confirmation emails to the customer and PolyCrochet team.
     */
    protected function sendOrderEmails(Order $order): void
    {
        $customerEmail = $order->billing_data['email'] ?? $order->user?->email;

        if ($customerEmail) {
            rescue(function () use ($order, $customerEmail) {
                Mail::to($customerEmail)->send(new OrderConfirmationCustomerMail($order));
            }, report: false);
        }

        $notificationsAddress = config('services.mail.notifications');

        if ($notificationsAddress) {
            rescue(function () use ($order, $notificationsAddress) {
                $mailer = Mail::to($notificationsAddress);

                $supportAddress = config('services.mail.support');
                if ($supportAddress && $supportAddress !== $notificationsAddress) {
                    $mailer->cc($supportAddress);
                }

                $mailer->send(new OrderConfirmationNotificationMail($order));
            }, report: false);
        }
    }
}

<?php

namespace App\Services\Payments;

use App\Models\Order;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use RuntimeException;

class PayPalService
{
    public function __construct(
        protected HttpFactory $http
    ) {
    }

    /**
     * Create a PayPal order for the given local order.
     *
     * @return array<string, mixed>
     *
     * @throws RequestException
     */
    public function createOrder(Order $order, string $returnUrl, string $cancelUrl): array
    {
        $currency = $this->paypalCurrency();
        $itemsPayload = [];
        $itemsTotal = 0.0;

        foreach ($order->items as $item) {
            $lineTotal = $this->convertForPayPal($item->line_total);
            $unitAmount = $item->quantity > 0 ? round($lineTotal / $item->quantity, 2) : $lineTotal;
            $lineTotal = $unitAmount * $item->quantity;
            $itemsTotal += $lineTotal;

            $entry = [
                'name' => Str::limit($item->product_name, 127),
                'unit_amount' => [
                    'currency_code' => $currency,
                    'value' => $this->formatAmount($unitAmount),
                ],
                'quantity' => (string) $item->quantity,
            ];

            if ($item->product_sku) {
                $entry['sku'] = Str::limit($item->product_sku, 127);
            }

            $itemsPayload[] = $entry;
        }

        $itemsTotal = round($itemsTotal, 2);
        $shippingTotal = round($this->convertForPayPal($order->shipping_total), 2);
        $total = round($itemsTotal + $shippingTotal, 2);

        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => $order->order_number,
                    'amount' => [
                        'currency_code' => $currency,
                        'value' => $this->formatAmount($total),
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => $currency,
                                'value' => $this->formatAmount($itemsTotal),
                            ],
                            'shipping' => [
                                'currency_code' => $currency,
                                'value' => $this->formatAmount($shippingTotal),
                            ],
                        ],
                    ],
                    'items' => $itemsPayload,
                ],
            ],
            'application_context' => [
                'brand_name' => config('app.name', 'PolyCrochet'),
                'landing_page' => 'LOGIN',
                'user_action' => 'PAY_NOW',
                'shipping_preference' => 'SET_PROVIDED_ADDRESS',
                'return_url' => $returnUrl,
                'cancel_url' => $cancelUrl,
            ],
        ];

        if ($order->shipping_data) {
            $shipping = $order->shipping_data;
            Arr::set($payload, 'purchase_units.0.shipping', [
                'name' => [
                    'full_name' => Arr::get($shipping, 'name', ''),
                ],
                'address' => [
                    'address_line_1' => trim(Arr::get($shipping, 'street') . ' ' . Arr::get($shipping, 'number')),
                    'address_line_2' => Arr::get($shipping, 'apartment'),
                    'admin_area_2' => Arr::get($shipping, 'commune'),
                    'admin_area_1' => Arr::get($shipping, 'region'),
                    'postal_code' => Arr::get($shipping, 'postal_code', '0000000'),
                    'country_code' => Arr::get($shipping, 'country', 'CL'),
                ],
            ]);
        }

        $response = $this->paypalRequest()
            ->post('/v2/checkout/orders', $payload)
            ->throw();

        return $response->json();
    }

    /**
     * Capture a PayPal order after user approval.
     *
     * @return array<string, mixed>
     *
     * @throws RequestException
     */
    public function captureOrder(string $paypalOrderId): array
    {
        return $this->paypalRequest()
            ->withBody('{}', 'application/json')
            ->post("/v2/checkout/orders/{$paypalOrderId}/capture")
            ->throw()
            ->json();
    }

    /**
     * Retrieve an order from PayPal (used to verify status).
     *
     * @return array<string, mixed>
     *
     * @throws RequestException
     */
    public function fetchOrder(string $paypalOrderId): array
    {
        return $this->paypalRequest()
            ->get("/v2/checkout/orders/{$paypalOrderId}")
            ->throw()
            ->json();
    }

    /**
     * Format amount with two decimals for PayPal.
     */
    public function formatAmount(float $amount): string
    {
        return number_format($amount, 2, '.', '');
    }

    protected function paypalCurrency(): string
    {
        return strtoupper(config('services.paypal.currency', 'USD'));
    }

    protected function paypalConversionRate(): float
    {
        $rate = (float) config('services.paypal.conversion_rate', 1);

        return $rate > 0 ? $rate : 1;
    }

    protected function convertForPayPal(float $amount): float
    {
        $rate = $this->paypalConversionRate();

        return $amount / $rate;
    }

    /**
     * Build a preconfigured HTTP client for PayPal calls.
     */
    protected function paypalRequest()
    {
        $baseUri = config('services.paypal.base_uri');

        if (! $baseUri) {
            throw new RuntimeException('PayPal base URI is not configured.');
        }

        return $this->http->baseUrl($baseUri)
            ->withToken($this->accessToken())
            ->acceptJson()
            ->asJson();
    }

    /**
     * Retrieve an OAuth token from PayPal (cached).
     */
    protected function accessToken(): string
    {
        $clientId = config('services.paypal.client_id');
        $secret = config('services.paypal.secret');
        $baseUri = config('services.paypal.base_uri');

        if (! $clientId || ! $secret || ! $baseUri) {
            throw new RuntimeException('PayPal credentials are not configured.');
        }

        $cacheKey = 'paypal.access_token';

        if ($token = Cache::get($cacheKey)) {
            return $token;
        }

        $response = $this->http->asForm()
            ->withBasicAuth($clientId, $secret)
            ->acceptJson()
            ->post(rtrim($baseUri, '/') . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ])
            ->throw();

        $accessToken = $response->json('access_token');
        $expiresIn = (int) $response->json('expires_in', 480);

        if (! $accessToken) {
            throw new RuntimeException('Unable to obtain PayPal access token.');
        }

        $ttl = max(60, $expiresIn - 60);
        Cache::put($cacheKey, $accessToken, now()->addSeconds($ttl));

        return $accessToken;
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Support\SettingsStore;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct(
        protected SettingsStore $settings
    ) {
    }

    /**
     * Display the settings form.
     */
    public function edit(): View
    {
        $settings = $this->settings;

        return view('admin.settings.index', [
            'paypalSettings' => [
                'client_id' => $settings->get('paypal.client_id', config('services.paypal.client_id')),
                'secret' => $settings->get('paypal.secret'),
                'base_uri' => $settings->get('paypal.base_uri', config('services.paypal.base_uri')),
                'currency' => $settings->get('paypal.currency', config('services.paypal.currency', 'USD')),
                'conversion_rate' => $settings->get('paypal.conversion_rate', config('services.paypal.conversion_rate', 900)),
            ],
            'shippingSettings' => [
                'method' => $settings->get('shipping.method', 'Chilexpress'),
                'rate' => (float) $settings->get('shipping.rate', 0),
            ],
            'storeSettings' => [
                'name' => $settings->get('store.name', config('app.name', 'PolyCrochet')),
                'support_email' => $settings->get('store.support_email', config('services.mail.support')),
                'checkout_message' => $settings->get('store.checkout_message', 'Gracias por apoyar el trabajo hecho a mano.'),
            ],
        ]);
    }

    /**
     * Update settings storage.
     */
    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->settings->set([
            'paypal.client_id' => $data['paypal_client_id'] ?: null,
            'paypal.secret' => $data['paypal_secret'] ?: null,
            'paypal.base_uri' => $data['paypal_base_uri'] ?: null,
            'paypal.currency' => $data['paypal_currency'] ? strtoupper($data['paypal_currency']) : null,
            'paypal.conversion_rate' => $data['paypal_conversion_rate'] ? (float) $data['paypal_conversion_rate'] : null,
            'shipping.method' => $data['shipping_method'] ?: null,
            'shipping.rate' => $data['shipping_rate'] !== null ? (float) $data['shipping_rate'] : null,
            'store.name' => $data['store_name'],
            'store.support_email' => $data['support_email'] ?: null,
            'store.checkout_message' => $data['checkout_message'] ?: null,
        ]);

        $this->settings->refresh();

        return redirect()
            ->route('admin.settings.edit')
            ->with('status', 'Configuración actualizada con éxito.');
    }
}


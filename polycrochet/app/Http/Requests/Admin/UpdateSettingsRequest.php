<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        return [
            'paypal_client_id' => ['nullable', 'string', 'max:255'],
            'paypal_secret' => ['nullable', 'string', 'max:255'],
            'paypal_base_uri' => ['nullable', 'url', 'max:255'],
            'paypal_currency' => ['nullable', 'string', 'size:3'],
            'paypal_conversion_rate' => ['nullable', 'numeric', 'min:1'],

            'shipping_method' => ['nullable', 'string', 'max:255'],
            'shipping_rate' => ['nullable', 'numeric', 'min:0'],

            'store_name' => ['required', 'string', 'max:255'],
            'support_email' => ['nullable', 'email', 'max:255'],
            'checkout_message' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'paypal_client_id' => 'PayPal Client ID',
            'paypal_secret' => 'PayPal Secret',
            'paypal_base_uri' => 'URL base de PayPal',
            'paypal_currency' => 'moneda PayPal',
            'paypal_conversion_rate' => 'tasa de conversión',
            'shipping_method' => 'método de envío',
            'shipping_rate' => 'tarifa base de envío',
            'store_name' => 'nombre de la tienda',
            'support_email' => 'correo de soporte',
            'checkout_message' => 'mensaje para el checkout',
        ];
    }
}

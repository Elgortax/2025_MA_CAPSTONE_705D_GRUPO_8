@extends('layouts.admin')
@section('title', 'Configuración | PolyCrochet')
@section('page_heading', 'Configuración')

@section('content')
  <section class="space-y-6">
    @if (session('status'))
      <div class="rounded-2xl border border-emerald-400/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
        {{ session('status') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="rounded-2xl border border-rose-400/40 bg-rose-500/10 px-4 py-3 text-sm text-rose-200">
        <p class="font-semibold">Revisa los campos marcados:</p>
        <ul class="mt-2 list-disc space-y-1 pl-5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <header>
      <h1 class="text-2xl font-semibold text-white">Preferencias generales</h1>
      <p class="mt-2 text-sm text-slate-400">Define opciones de pagos, envíos e información de la tienda.</p>
    </header>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
      @csrf
      @method('PUT')

      <div class="grid gap-6 lg:grid-cols-2">
        <article class="space-y-4 rounded-2xl border border-slate-800 bg-slate-900/60 p-6">
          <header>
            <h2 class="text-lg font-semibold text-white">PayPal Sandbox</h2>
            <p class="mt-1 text-xs text-slate-400">Tus credenciales se almacenan de forma segura. Actualiza los datos cuando Resend cambie tus claves.</p>
          </header>
          <div class="space-y-4 text-sm">
            <label class="block text-slate-300">Client ID
              <input type="text" name="paypal_client_id" value="{{ old('paypal_client_id', $paypalSettings['client_id']) }}" class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-slate-200 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </label>
            <label class="block text-slate-300">Client Secret
              <input type="text" name="paypal_secret" value="{{ old('paypal_secret', $paypalSettings['secret']) }}" class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-slate-200 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </label>
            <label class="block text-slate-300">URL base API
              <input type="text" name="paypal_base_uri" value="{{ old('paypal_base_uri', $paypalSettings['base_uri']) }}" class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-slate-200 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="https://api-m.sandbox.paypal.com">
            </label>
            <div class="grid gap-4 lg:grid-cols-2">
              <label class="block text-slate-300">Moneda
                <input type="text" name="paypal_currency" value="{{ old('paypal_currency', $paypalSettings['currency']) }}" class="mt-1 w-full uppercase rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-slate-200 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" maxlength="3" placeholder="USD">
              </label>
              <label class="block text-slate-300">Tasa de conversión
                <input type="number" name="paypal_conversion_rate" step="0.01" min="0" value="{{ old('paypal_conversion_rate', $paypalSettings['conversion_rate']) }}" class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-slate-200 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="900">
              </label>
            </div>
          </div>
        </article>

        <article class="space-y-4 rounded-2xl border border-slate-800 bg-slate-900/60 p-6">
          <header>
            <h2 class="text-lg font-semibold text-white">Envíos</h2>
            <p class="mt-1 text-xs text-slate-400">Estos valores se usan como referencia para tus órdenes.</p>
          </header>
          <div class="space-y-4 text-sm">
            <label class="block text-slate-300">Método principal
              <input type="text" name="shipping_method" value="{{ old('shipping_method', $shippingSettings['method']) }}" class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-slate-200 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Chilexpress">
            </label>
            <label class="block text-slate-300">Tarifa base (CLP)
              <input type="number" name="shipping_rate" step="100" min="0" value="{{ old('shipping_rate', $shippingSettings['rate']) }}" class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-slate-200 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="4500">
            </label>
          </div>
        </article>
      </div>

      <article class="space-y-4 rounded-2xl border border-slate-800 bg-slate-900/60 p-6">
        <header>
          <h2 class="text-lg font-semibold text-white">Información de la tienda</h2>
          <p class="mt-1 text-xs text-slate-400">Personaliza cómo te ven tus clientes durante el checkout.</p>
        </header>
        <div class="grid gap-4 text-sm lg:grid-cols-3">
          <label class="lg:col-span-2 text-slate-300">Nombre comercial
            <input type="text" name="store_name" value="{{ old('store_name', $storeSettings['name']) }}" required class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-slate-200 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
          </label>
          <label class="text-slate-300">Correo soporte
            <input type="email" name="support_email" value="{{ old('support_email', $storeSettings['support_email']) }}" class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-slate-200 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="soporte@polycrochet.cl">
          </label>
          <label class="lg:col-span-3 text-slate-300">Mensaje para el checkout
            <textarea name="checkout_message" rows="3" class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-slate-200 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Gracias por apoyar el trabajo hecho a mano.">{{ old('checkout_message', $storeSettings['checkout_message']) }}</textarea>
          </label>
        </div>
      </article>

      <div class="flex justify-end">
        <button type="submit" class="rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-400 focus-visible:ring-offset-slate-900">
          Guardar cambios
        </button>
      </div>
    </form>
  </section>
@endsection

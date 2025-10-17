@extends('layouts.app')
@section('title', 'Confirmación de pedido | PolyCrochet')

@php
  use Illuminate\Support\Str;

  $formatCurrency = fn (float $value) => '$' . number_format($value, 0, ',', '.');
  $shippingName = $shipping['name'] ?? optional($order->user)->name;
  $shippingLines = collect([
    $shipping['street'] ?? null,
    $shipping['number'] ?? null,
    $shipping['apartment'] ?? null,
  ])->filter()->implode(' ');
  $locationLine = collect([
    $shipping['commune'] ?? null,
    $shipping['region'] ?? null,
  ])->filter()->implode(', ');
@endphp

@section('content')
  <section class="mx-auto max-w-4xl space-y-10">
    <header class="space-y-4 text-center">
      <h1 class="text-3xl font-bold text-rose-700">¡Gracias por tu compra!</h1>
      <p class="text-sm text-slate-600">
        Tu pedido <span class="font-semibold text-slate-900">{{ $order->order_number }}</span> está en marcha.
        Te enviaremos actualizaciones al correo {{ $order->billing_data['email'] ?? $order->user?->email }}.
      </p>
      @if (session('status'))
        <div class="mx-auto inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-5 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-emerald-600">
          {{ session('status') }}
        </div>
      @endif
    </header>

    <div class="grid gap-6 lg:grid-cols-[1.4fr,1fr]">
      <article class="space-y-5 rounded-[2.5rem] border border-rose-100 bg-white/95 p-6 shadow-lg shadow-rose-100/50">
        <h2 class="text-lg font-semibold text-rose-700">Resumen del pedido</h2>
        <ul class="space-y-4 text-sm text-slate-600">
          @foreach ($order->items as $item)
            <li class="flex items-center gap-4">
              @php
                $product = $item->product;
                $image = $product?->primaryImage?->url;
                $options = $item->options ?? [];
              @endphp
              <div class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-2xl border border-rose-100 bg-rose-50">
                @if ($image)
                  <img src="{{ $image }}" alt="{{ $item->product_name }}" class="h-full w-full object-cover">
                @else
                  <span class="flex h-full items-center justify-center text-base font-semibold text-rose-400">
                    {{ mb_substr($item->product_name, 0, 1) }}
                  </span>
                @endif
              </div>
              <div class="flex flex-1 items-center justify-between gap-3">
                <div>
                  <p class="text-sm font-semibold text-slate-900">{{ $item->product_name }}</p>
                  <p class="text-xs text-slate-500">Cantidad: {{ $item->quantity }}</p>
                </div>
                <span class="text-sm font-semibold text-slate-900">{{ $formatCurrency((float) $item->line_total) }}</span>
              </div>
            </li>
          @endforeach
        </ul>
        <div class="space-y-2 border-t border-rose-100 pt-4 text-sm text-slate-600">
          <div class="flex justify-between">
            <span>Subtotal</span>
            <span>{{ $formatCurrency((float) $order->items_total) }}</span>
          </div>
          <div class="flex justify-between">
            <span>Envío</span>
            <span>{{ $formatCurrency((float) $order->shipping_total) }}</span>
          </div>
          <div class="flex justify-between text-base font-semibold text-rose-700">
            <span>Total pagado</span>
            <span>{{ $formatCurrency((float) $order->total) }}</span>
          </div>
        </div>
      </article>

      <aside class="space-y-5 rounded-[2.5rem] border border-rose-100 bg-white/95 p-6 shadow-lg shadow-rose-100/50">
        <h2 class="text-lg font-semibold text-rose-700">Datos de envío</h2>
        <dl class="space-y-3 text-sm text-slate-600">
          <div>
            <dt class="text-xs uppercase tracking-[0.3em] text-rose-400">Destinatario</dt>
            <dd class="mt-1 font-semibold text-rose-700">{{ $shippingName }}</dd>
          </div>
          <div>
            <dt class="text-xs uppercase tracking-[0.3em] text-rose-400">Dirección</dt>
            <dd class="mt-1 font-medium text-slate-700">
              {{ $shippingLines ?: 'Sin dirección registrada' }}<br>
              {{ $locationLine }}
            </dd>
            @if (! empty($shipping['reference']))
              <dd class="mt-1 text-xs text-slate-500">Referencia: {{ $shipping['reference'] }}</dd>
            @endif
          </div>
          <div>
            <dt class="text-xs uppercase tracking-[0.3em] text-rose-400">Contacto</dt>
            <dd class="mt-1 font-medium text-slate-700">
              {{ $order->billing_data['email'] ?? $order->user?->email }}<br>
              {{ $order->billing_data['phone'] ?? $order->user?->phone ?? 'Sin teléfono' }}
            </dd>
          </div>
          <div>
            <dt class="text-xs uppercase tracking-[0.3em] text-rose-400">Estado del pedido</dt>
            <dd class="mt-1 inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
              {{ Str::headline($order->status) }}
            </dd>
          </div>
          <div>
            <dt class="text-xs uppercase tracking-[0.3em] text-rose-400">Método de pago</dt>
            <dd class="mt-1 font-medium text-slate-700">PayPal</dd>
            @if ($order->paid_at)
              <dd class="text-xs text-slate-500">Pagado el {{ $order->paid_at->translatedFormat('d \\d\\e F Y \\a \\l\\a\\s H:i') }}</dd>
            @endif
          </div>
        </dl>
      </aside>
    </div>

    <div class="flex flex-wrap justify-center gap-4">
      <a href="{{ route('catalog') }}" class="rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-6 py-3 text-sm font-semibold text-white shadow shadow-rose-200/60 transition hover:from-rose-500 hover:to-amber-400">
        Seguir comprando
      </a>
      <a href="{{ route('account') }}" class="rounded-full border border-rose-200 px-6 py-3 text-sm font-semibold text-rose-500 transition hover:border-rose-300 hover:bg-rose-50">
        Ir a mi cuenta
      </a>
    </div>
  </section>
@endsection

@extends('layouts.app')
@section('title', "Pedido {$order->order_number} | PolyCrochet")

@php
  $formatCurrency = fn (float $value) => '$' . number_format($value, 0, ',', '.');
  $statusTimeline = [
    'pendiente' => [
      'label' => 'Pedido recibido',
      'description' => 'Hemos registrado tu orden y estamos validando el pago.',
    ],
    'pagado' => [
      'label' => 'Pago confirmado',
      'description' => 'Tu pago quedó registrado correctamente.',
    ],
    'en_produccion' => [
      'label' => 'En producción',
      'description' => 'Nuestro equipo está preparando cada pieza artesanal con cariño.',
    ],
    'enviado' => [
      'label' => 'En camino',
      'description' => 'Tu pedido fue despachado; pronto llegará a destino.',
    ],
    'entregado' => [
      'label' => 'Entregado',
      'description' => 'Tu pedido llegó a tus manos. ¡Que lo disfrutes!',
    ],
    'cancelado' => [
      'label' => 'Pedido cancelado',
      'description' => 'Si necesitas ayuda para reactivar o aclarar el estado, escríbenos.',
    ],
  ];
  $statusOrder = array_keys($statusTimeline);
  $currentStatus = $order->status;
  $currentIndex = array_search($currentStatus, $statusOrder, true);
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
  <section class="mx-auto max-w-5xl space-y-10">
    <header class="space-y-3 text-center">
      <h1 class="text-3xl font-bold text-rose-700">Detalle del pedido</h1>
      <p class="text-sm text-slate-600">
        Pedido <span class="font-semibold text-slate-900">{{ $order->order_number }}</span> — creado el {{ $order->created_at->translatedFormat('d \\d\\e F Y - H:i') }}.
      </p>
      <div class="inline-flex items-center gap-2 rounded-full border border-rose-200 bg-white px-4 py-1 text-xs font-semibold uppercase tracking-[0.3em] text-rose-500">
        Estado actual: {{ $order->status_label }}
      </div>
    </header>

    <article class="space-y-4 rounded-[2.5rem] border border-rose-100 bg-white/95 p-6 shadow-lg shadow-rose-100/50">
      <h2 class="text-lg font-semibold text-rose-700">Seguimiento del estado</h2>
      <ol class="relative space-y-4 border-l border-rose-100 pl-6 text-sm text-slate-600">
        @foreach ($statusTimeline as $statusKey => $meta)
          @php
            $stepIndex = array_search($statusKey, $statusOrder, true);
            $isCurrent = $currentIndex !== false && $stepIndex === $currentIndex;
            $isComplete = $currentIndex !== false && $stepIndex < $currentIndex;
            $dotClasses = $isCurrent
              ? 'bg-rose-500 border-rose-400'
              : ($isComplete ? 'bg-emerald-500 border-emerald-400' : 'bg-white border-rose-200');
            $titleClasses = $isCurrent
              ? 'text-rose-700 font-semibold'
              : ($isComplete ? 'text-emerald-700 font-semibold' : 'text-slate-700 font-medium');
            $descriptionClasses = $isCurrent || $isComplete ? 'text-slate-500' : 'text-slate-400';

            if ($statusKey === 'cancelado') {
              $dotClasses = $isCurrent ? 'bg-rose-500 border-rose-400' : 'bg-white border-rose-200';
              $titleClasses = $isCurrent ? 'text-rose-700 font-semibold' : 'text-slate-600 font-medium';
              $descriptionClasses = 'text-slate-400';
            }
          @endphp
          <li class="relative pl-6">
            <span class="absolute left-[-0.7rem] top-1 block h-3 w-3 rounded-full border {{ $dotClasses }}"></span>
            <p class="{{ $titleClasses }}">{{ $meta['label'] }}</p>
            <p class="text-xs {{ $descriptionClasses }}">{{ $meta['description'] }}</p>
          </li>
        @endforeach
      </ol>
    </article>

    <div class="grid gap-6 lg:grid-cols-[1.4fr,1fr]">
      <article class="space-y-5 rounded-[2.5rem] border border-rose-100 bg-white/95 p-6 shadow-lg shadow-rose-100/50">
        <h2 class="text-lg font-semibold text-rose-700">Productos incluidos</h2>
        <ul class="space-y-4 text-sm text-slate-600">
          @foreach ($order->items as $item)
            @php
              $product = $item->product;
              $image = $product?->primaryImage?->url;
            @endphp
            <li class="flex items-center gap-4">
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
            <span>Total</span>
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
            <dt class="text-xs uppercase tracking-[0.3em] text-rose-400">Pago</dt>
            <dd class="mt-1 font-medium text-slate-700">PayPal</dd>
            @if ($order->paid_at)
              <dd class="text-xs text-slate-500">Pagado el {{ $order->paid_at->translatedFormat('d \\d\\e F Y \\a \\l\\a\\s H:i') }}</dd>
            @endif
          </div>
        </dl>
      </aside>
    </div>

    <div class="flex flex-wrap justify-center gap-4">
      <a href="{{ route('orders.history') }}" class="rounded-full border border-rose-200 px-6 py-3 text-sm font-semibold text-rose-500 transition hover:border-rose-300 hover:bg-rose-50">
        Volver a mis pedidos
      </a>
      <a href="{{ route('catalog') }}" class="rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-6 py-3 text-sm font-semibold text-white shadow shadow-rose-200/60 transition hover:from-rose-500 hover:to-amber-400">
        Seguir comprando
      </a>
    </div>
  </section>
@endsection

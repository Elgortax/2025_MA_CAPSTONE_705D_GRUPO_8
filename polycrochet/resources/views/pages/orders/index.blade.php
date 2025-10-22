@extends('layouts.app')
@section('title', 'Mis pedidos | PolyCrochet')

@php
  $formatCurrency = fn (float $value) => '$' . number_format($value, 0, ',', '.');
  $statusClasses = [
    'pendiente' => 'border-amber-200 bg-amber-50 text-amber-600',
    'pagado' => 'border-emerald-200 bg-emerald-50 text-emerald-600',
    'en_produccion' => 'border-blue-200 bg-blue-50 text-blue-600',
    'enviado' => 'border-sky-200 bg-sky-50 text-sky-600',
    'entregado' => 'border-emerald-200 bg-emerald-50 text-emerald-600',
    'cancelado' => 'border-rose-200 bg-rose-50 text-rose-600',
  ];
@endphp

@section('content')
  <section class="mx-auto max-w-5xl space-y-8">
    <header class="space-y-2 text-center">
      <h1 class="text-3xl font-bold text-rose-700">Mis pedidos</h1>
      <p class="text-sm text-slate-600">
        Revisa el historial de compras, el estado de tus pedidos y vuelve a descargar los resúmenes cuando quieras.
      </p>
    </header>

    @if ($orders->isEmpty())
      <article class="rounded-[2.5rem] border border-rose-100 bg-white/95 p-10 text-center shadow-xl shadow-rose-100/60">
        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-rose-50 text-3xl text-rose-400">
          🧶
        </div>
        <h2 class="mt-6 text-xl font-semibold text-rose-600">Aún no has realizado compras</h2>
        <p class="mt-2 text-sm text-slate-500">
          Explora el catálogo y descubre ramos, muñecas y amigurumis listos para sorprender.
        </p>
        <a href="{{ route('catalog') }}"
           class="mt-6 inline-flex items-center justify-center rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-6 py-3 text-sm font-semibold text-white shadow shadow-rose-200/60 transition hover:from-rose-500 hover:to-amber-400">
          Ir al catálogo
        </a>
      </article>
    @else
      <div class="space-y-5">
        @foreach ($orders as $order)
          @php
            $status = $order->status;
            $badgeClass = $statusClasses[$status] ?? 'border-slate-200 bg-slate-50 text-slate-600';
          @endphp
          <article class="space-y-4 rounded-[2.5rem] border border-rose-100 bg-white/95 p-6 shadow-lg shadow-rose-100/50">
            <header class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
              <div>
                <h2 class="text-lg font-semibold text-rose-700">{{ $order->order_number }}</h2>
                <p class="text-sm text-slate-500">Creado el {{ $order->created_at->translatedFormat('d \\d\\e F Y - H:i') }}</p>
              </div>
              <span class="inline-flex items-center gap-2 rounded-full border px-4 py-1 text-xs font-semibold uppercase tracking-[0.25em] {{ $badgeClass }}">
                {{ $order->status_label }}
              </span>
            </header>

            <dl class="grid gap-4 text-sm text-slate-600 sm:grid-cols-2">
              <div class="rounded-2xl border border-rose-100 bg-white/95 p-4">
                <dt class="text-xs uppercase tracking-[0.25em] text-rose-400">Total</dt>
                <dd class="mt-1 text-base font-semibold text-rose-700">{{ $formatCurrency((float) $order->total) }}</dd>
              </div>
              <div class="rounded-2xl border border-rose-100 bg-white/95 p-4">
                <dt class="text-xs uppercase tracking-[0.25em] text-rose-400">Productos</dt>
                <dd class="mt-1 text-base font-semibold text-rose-700">
                  {{ (int) ($order->total_units ?? $order->items_sum_quantity ?? 0) }} unidades
                </dd>
              </div>
            </dl>

            <div class="flex flex-wrap items-center gap-3">
              <a href="{{ route('orders.history.show', ['order' => $order->uuid]) }}"
                 class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-6 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-white shadow shadow-rose-200/60 transition hover:from-rose-500 hover:to-amber-400">
                Ver detalle
              </a>
              <a href="{{ route('order.confirmation', ['order' => $order->uuid]) }}"
                 class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-6 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-rose-500 transition hover:border-rose-300 hover:bg-rose-50">
                Ver comprobante
              </a>
            </div>
          </article>
        @endforeach
      </div>

      <div>
        {{ $orders->links() }}
      </div>
    @endif
  </section>
@endsection

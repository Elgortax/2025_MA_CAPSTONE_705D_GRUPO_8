@extends('layouts.app')
@section('title', 'Carrito | PolyCrochet')

@php
  use Illuminate\Support\Str;
@endphp

@section('content')
  <section class="grid gap-10 lg:grid-cols-[2fr,1fr]" data-cart-page>
    <div class="space-y-4">
      <h1 class="text-2xl font-bold text-rose-700">Tu carrito</h1>

      @if (session('status'))
        <div class="rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-sm text-rose-600 shadow-sm shadow-rose-100/60">
          {{ session('status') }}
        </div>
      @endif

      @if ($items->isEmpty())
        <div class="rounded-[2.5rem] border border-rose-100 bg-white/90 p-6 text-center shadow-lg shadow-rose-100/40">
          <p class="text-sm text-slate-600">Tu carrito está vacío. Inspírate en nuestro catálogo y agrega tus favoritos.</p>
          <a href="{{ route('catalog') }}" class="mt-4 inline-flex items-center gap-2 rounded-full border border-rose-200 px-6 py-3 text-sm font-semibold text-rose-500 transition hover:border-rose-300 hover:bg-rose-50">Ver catálogo</a>
        </div>
      @else
        @foreach ($items as $item)
          @php
            /** @var \App\Models\Product $product */
            $product = $item['product'];
          @endphp
          <article class="flex flex-col gap-4 rounded-[2.5rem] border border-rose-100 bg-white/90 p-5 shadow-lg shadow-rose-100/40 sm:flex-row sm:items-center" data-cart-item data-product-id="{{ $product->id }}">
            <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-2xl border border-rose-100">
              @if ($product->primaryImage?->url)
                <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}" class="h-full w-full object-cover" />
              @else
                <div class="flex h-full items-center justify-center bg-rose-50 text-2xl font-semibold text-rose-400">
                  {{ Str::upper(Str::substr($product->name, 0, 1)) }}
                </div>
              @endif
            </div>
            <div class="flex flex-1 flex-col gap-3">
              <div class="flex flex-wrap items-baseline justify-between gap-2">
                <h2 class="text-lg font-semibold text-rose-600">
                  <a href="{{ route('product.show', $product->slug) }}" class="hover:text-rose-500">{{ $product->name }}</a>
                </h2>
                <span class="text-base font-semibold text-slate-800">${{ number_format($item['price'], 0, ',', '.') }}</span>
              </div>
              @if ($product->summary)
                <p class="text-sm text-slate-500">{{ $product->summary }}</p>
              @endif
              <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500">
                <form method="POST" action="{{ route('cart.update', $product) }}" class="flex items-center gap-2" data-cart-update-form>
                  @csrf
                  @method('PATCH')
                  <label class="text-slate-500">
                    Cantidad
                    <input type="number" name="quantity" min="1" max="10" value="{{ $item['quantity'] }}" class="ml-2 h-9 w-16 rounded-full border border-rose-200 px-2 text-center focus:border-rose-400 focus:outline-none focus:ring-1 focus:ring-rose-300" data-cart-quantity>
                  </label>
                </form>
                <form method="POST" action="{{ route('cart.destroy', $product) }}" data-cart-remove-form>
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="text-rose-500 transition hover:text-rose-600" data-cart-remove>Eliminar</button>
                </form>
              </div>
            </div>
            <div class="text-right text-sm font-semibold text-rose-600">
              Subtotal<br>
              <span data-cart-item-subtotal>${{ number_format($item['subtotal'], 0, ',', '.') }}</span>
            </div>
          </article>
        @endforeach
      @endif

      @if ($items->isNotEmpty())
        <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-rose-500 hover:text-rose-600">Seguir explorando</a>
      @endif
    </div>

    <aside class="space-y-4 rounded-[2.5rem] border border-rose-100 bg-white/90 p-6 shadow-lg shadow-rose-100/40">
      <h2 class="text-lg font-semibold text-rose-600">Resumen</h2>
      <dl class="space-y-2 text-sm text-slate-600">
        <div class="flex justify-between">
          <dt>Subtotal</dt>
          <dd data-cart-subtotal>${{ number_format($subtotal, 0, ',', '.') }}</dd>
        </div>
        <div class="flex justify-between text-base font-semibold text-rose-600">
          <dt>Total</dt>
          <dd data-cart-total>${{ number_format($total, 0, ',', '.') }}</dd>
        </div>
      </dl>
      <a href="{{ route('checkout') }}" class="inline-flex w-full justify-center rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-6 py-3 text-sm font-semibold text-white shadow shadow-rose-200/50 transition hover:from-rose-500 hover:to-amber-400 {{ $items->isEmpty() ? 'pointer-events-none opacity-50' : '' }}">
        Proceder al checkout
      </a>
      <p class="text-xs text-slate-400">El total final se confirmará durante el pago en PayPal.</p>
    </aside>
  </section>
@endsection

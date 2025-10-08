@extends('layouts.app')
@section('title', 'Carrito | PolyCrochet')

@section('content')
  <section class="grid gap-8 lg:grid-cols-[2fr,1fr]">
    <div class="space-y-4">
      <h1 class="text-2xl font-bold">Tu carrito</h1>

      @php
        $cartItems = [
          [
            'name' => 'Ramo Animalitos · Gatito pastel',
            'image' => 'resources/images/ramos/ramo_gato/ramo_rosa_gato.jpg',
            'options' => 'Color: rosa pastel · Tamaño: standard',
            'price' => '$39.990',
            'quantity' => 1,
          ],
          [
            'name' => 'Set de girasoles con abejas',
            'image' => 'resources/images/girasol/girasol_abejas.jpg',
            'options' => 'Pack 5 unidades · Mensaje bordado',
            'price' => '$22.900',
            'quantity' => 1,
          ],
        ];
      @endphp

      @foreach ($cartItems as $item)
        <article class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center">
          <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-xl border border-gray-100">
            <img src="{{ Vite::asset($item['image']) }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover" />
          </div>
          <div class="flex flex-1 flex-col gap-2">
            <div class="flex flex-wrap items-baseline justify-between gap-2">
              <h2 class="text-lg font-semibold">{{ $item['name'] }}</h2>
              <span class="text-base font-semibold text-gray-900">{{ $item['price'] }}</span>
            </div>
            <p class="text-sm text-gray-600">{{ $item['options'] }}</p>
            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500">
              <label class="flex items-center gap-2">
                <span>Cantidad</span>
                <input type="number" min="1" value="{{ $item['quantity'] }}" class="h-9 w-16 rounded-md border border-gray-300 px-2 text-center focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
              </label>
              <button type="button" class="text-red-500 hover:text-red-400">Eliminar</button>
            </div>
          </div>
        </article>
      @endforeach

      <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-500">Seguir explorando</a>
    </div>

    <aside class="space-y-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
      <h2 class="text-lg font-semibold">Resumen</h2>
      <dl class="space-y-2 text-sm text-gray-600">
        <div class="flex justify-between">
          <dt>Subtotal</dt>
          <dd>$62.890</dd>
        </div>
        <div class="flex justify-between">
          <dt>Envío estimado</dt>
          <dd>$4.500</dd>
        </div>
        <div class="flex justify-between font-semibold text-gray-900">
          <dt>Total</dt>
          <dd>$67.390</dd>
        </div>
      </dl>
      <a href="{{ route('checkout') }}" class="inline-flex w-full justify-center rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow hover:bg-blue-500">Proceder al checkout</a>
      <p class="text-xs text-gray-400">El total final se confirmará durante el pago en PayPal.</p>
    </aside>
  </section>
@endsection

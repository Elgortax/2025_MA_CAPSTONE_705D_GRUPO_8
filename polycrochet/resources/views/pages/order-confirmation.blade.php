@extends('layouts.app')
@section('title', 'Confirmación de pedido | PolyCrochet')

@section('content')
  <section class="mx-auto max-w-3xl space-y-8">
    <header class="space-y-2 text-center">
      <h1 class="text-3xl font-bold">¡Gracias por tu compra!</h1>
      <p class="text-gray-600">Tu pedido <span class="font-semibold text-gray-900">PC-10294</span> está en marcha. Te enviaremos actualizaciones por correo.</p>
    </header>

    <div class="grid gap-6 lg:grid-cols-2">
      <article class="space-y-3 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold">Resumen del pedido</h2>
        <ul class="space-y-3 text-sm text-gray-600">
          @foreach ([
            [
              'name' => 'Ramo Animalitos · Gatito pastel',
              'image' => 'resources/images/ramos/ramo_gato/ramo_rosa_gato.jpg',
              'price' => '$39.990',
            ],
            [
              'name' => 'Set de girasoles con abejas',
              'image' => 'resources/images/girasol/girasol_abejas.jpg',
              'price' => '$22.900',
            ],
          ] as $item)
            <li class="flex items-center gap-3">
              <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-lg border border-gray-100">
                <img src="{{ Vite::asset($item['image']) }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover" />
              </div>
              <div class="flex flex-1 items-center justify-between">
                <span class="text-sm font-medium text-gray-700">{{ $item['name'] }}</span>
                <span class="text-sm font-semibold text-gray-900">{{ $item['price'] }}</span>
              </div>
            </li>
          @endforeach
        </ul>
        <div class="border-t pt-3 text-sm">
          <div class="flex justify-between"><span>Envío</span><span>$4.500</span></div>
          <div class="flex justify-between font-semibold text-gray-900"><span>Total</span><span>$67.390</span></div>
        </div>
      </article>

      <article class="space-y-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold">Datos de envío</h2>
        <p class="text-sm text-gray-600">Camila Rojas<br>Av. Siempre Viva 742<br>Santiago, Región Metropolitana<br>+56 9 1234 5678</p>
        <p class="text-sm text-gray-600">Método de pago: PayPal</p>
        <p class="text-sm text-gray-600">Tiempo estimado de confección: 5 a 7 días hábiles.</p>
      </article>
    </div>

    <div class="flex flex-wrap justify-center gap-4">
      <a href="{{ route('catalog') }}" class="rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-500">Seguir comprando</a>
      <a href="{{ route('profile') }}" class="rounded-full border border-gray-300 px-6 py-3 text-sm font-semibold text-gray-700 hover:border-blue-400 hover:text-blue-600">Ver mis pedidos</a>
    </div>
  </section>
@endsection

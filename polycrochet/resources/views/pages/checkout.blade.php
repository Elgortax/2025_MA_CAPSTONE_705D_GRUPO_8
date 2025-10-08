@extends('layouts.app')
@section('title', 'Checkout | PolyCrochet')

@section('content')
  <section class="space-y-8">
    <header>
      <h1 class="text-2xl font-bold">Finaliza tu compra</h1>
      <p class="mt-2 text-sm text-gray-600">Completa tus datos para enviar tu pedido y procesar el pago seguro con PayPal.</p>
    </header>

    <div class="grid gap-8 lg:grid-cols-[2fr,1fr]">
      <form class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
          <h2 class="text-lg font-semibold">Información de contacto</h2>
          <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <label class="text-sm font-medium text-gray-700">Nombre completo
              <input type="text" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Ej. Camila Rojas" />
            </label>
            <label class="text-sm font-medium text-gray-700">Correo electrónico
              <input type="email" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="tu@correo.cl" />
            </label>
            <label class="text-sm font-medium text-gray-700">Teléfono de contacto
              <input type="tel" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="+56 9 ..." />
            </label>
            <label class="flex items-center gap-2 text-sm text-gray-600">
              <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
              Crear una cuenta con estos datos
            </label>
          </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
          <h2 class="text-lg font-semibold">Dirección de envío</h2>
          <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <label class="text-sm font-medium text-gray-700">Dirección
              <input type="text" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Av. Siempre Viva 742" />
            </label>
            <label class="text-sm font-medium text-gray-700">Ciudad
              <input type="text" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Santiago" />
            </label>
            <label class="text-sm font-medium text-gray-700">Región
              <input type="text" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Región Metropolitana" />
            </label>
            <label class="text-sm font-medium text-gray-700">Código postal
              <input type="text" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="8320000" />
            </label>
          </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
          <h2 class="text-lg font-semibold">Pago con PayPal</h2>
          <p class="mt-2 text-sm text-gray-600">Serás redirigido a la pasarela segura de PayPal para completar el pago. Al terminar, volverás automáticamente a PolyCrochet.</p>
          <button type="button" class="mt-4 inline-flex w-full justify-center rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow hover:bg-blue-500">Ir a PayPal</button>
        </div>
      </form>

      <aside class="space-y-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold">Resumen de compra</h2>
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
          ] as $summary)
            <li class="flex items-center gap-3">
              <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-lg border border-gray-100">
                <img src="{{ Vite::asset($summary['image']) }}" alt="{{ $summary['name'] }}" class="h-full w-full object-cover" />
              </div>
              <div class="flex flex-1 items-center justify-between">
                <span class="text-sm font-medium text-gray-700">{{ $summary['name'] }}</span>
                <span class="text-sm font-semibold text-gray-900">{{ $summary['price'] }}</span>
              </div>
            </li>
          @endforeach
        </ul>
        <dl class="space-y-2 text-sm text-gray-600">
          <div class="flex justify-between"><dt>Subtotal</dt><dd>$62.890</dd></div>
          <div class="flex justify-between"><dt>Envío</dt><dd>$4.500</dd></div>
          <div class="flex justify-between font-semibold text-gray-900"><dt>Total</dt><dd>$67.390</dd></div>
        </dl>
        <p class="text-xs text-gray-400">Al continuar aceptas nuestras políticas y términos.</p>
      </aside>
    </div>
  </section>
@endsection

@extends('layouts.app')
@section('title', 'Perfil | PolyCrochet')

@section('content')
  <section class="space-y-8">
    <header class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-3xl font-bold">Hola, Camila</h1>
        <p class="text-sm text-gray-600">Administra tus datos personales y revisa tus pedidos más recientes.</p>
      </div>
      <a href="{{ route('account') }}" class="rounded-full border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:border-blue-400 hover:text-blue-600">Cerrar sesión</a>
    </header>

    <div class="grid gap-6 lg:grid-cols-3">
      <article class="lg:col-span-2 space-y-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold">Datos personales</h2>
        <form class="grid gap-4 sm:grid-cols-2">
          <label class="text-sm font-medium text-gray-700">Nombre
            <input type="text" value="Camila" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
          </label>
          <label class="text-sm font-medium text-gray-700">Apellido
            <input type="text" value="Rojas" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
          </label>
          <label class="text-sm font-medium text-gray-700">Correo
            <input type="email" value="camila@ejemplo.cl" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
          </label>
          <label class="text-sm font-medium text-gray-700">Teléfono
            <input type="tel" value="+56 9 1234 5678" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
          </label>
          <div class="sm:col-span-2 text-right">
            <button type="button" class="rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-500">Guardar cambios</button>
          </div>
        </form>
      </article>

      <aside class="space-y-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold">Mis pedidos</h2>
        <ul class="space-y-3 text-sm text-gray-600">
          @foreach ([
            [
              'code' => 'PC-10294',
              'status' => 'En confección',
              'status_color' => 'emerald',
              'date' => '20 septiembre 2025',
              'total' => '$67.390',
              'image' => 'resources/images/ramos/ramo_gato/ramo_rosa_gato.jpg',
            ],
            [
              'code' => 'PC-10210',
              'status' => 'Entregado',
              'status_color' => 'gray',
              'date' => '02 agosto 2025',
              'total' => '$32.500',
              'image' => 'resources/images/duo/duo_rojo_blanco.jpg',
            ],
          ] as $order)
            <li class="flex items-center gap-3">
              <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-lg border border-gray-100">
                <img src="{{ Vite::asset($order['image']) }}" alt="{{ $order['code'] }}" class="h-full w-full object-cover" />
              </div>
              <div class="flex-1 space-y-1">
                <div class="flex items-center justify-between">
                  <span class="text-sm font-semibold text-gray-900">{{ $order['code'] }}</span>
                  @php
                    $badge = $order['status_color'] === 'emerald' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-700';
                  @endphp
                  <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $badge }}">{{ $order['status'] }}</span>
                </div>
                <p class="text-xs text-gray-500">{{ $order['date'] }} · {{ $order['total'] }}</p>
              </div>
            </li>
          @endforeach
        </ul>
        <a href="{{ route('order.confirmation') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-500">Ver detalles</a>
      </aside>
    </div>
  </section>
@endsection

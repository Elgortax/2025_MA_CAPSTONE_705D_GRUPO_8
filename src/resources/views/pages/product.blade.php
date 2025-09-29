@extends('layouts.app')
@section('title', 'Detalle de producto | PolyCrochet')

@section('content')
  <article class="grid gap-10 lg:grid-cols-2 lg:items-start">
    <div class="space-y-6">
      <div class="aspect-square overflow-hidden rounded-3xl bg-gradient-to-br from-rose-200 via-amber-200 to-sky-200"></div>
      <div class="grid grid-cols-4 gap-3">
        @for ($i = 1; $i <= 4; $i++)
          <div class="aspect-square rounded-xl border border-gray-200 bg-white"></div>
        @endfor
      </div>
    </div>

    <div class="space-y-6">
      <div>
        <p class="text-sm uppercase tracking-wide text-blue-600">Colección cápsula</p>
        <h1 class="text-3xl font-bold">Bufanda acolchada multicolor</h1>
      </div>
      <p class="text-gray-600">Cada pieza es tejida a mano con hilo hipoalergénico. Personaliza tu paleta de colores y largo para crear la bufanda perfecta para tu estilo.</p>
      <div class="flex items-center gap-4">
        <span class="text-3xl font-semibold text-gray-900">$24.990</span>
        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Disponible</span>
      </div>
      <form class="space-y-4">
        <div>
          <label class="text-sm font-semibold text-gray-700">Color principal</label>
          <div class="mt-2 flex gap-2">
            <button type="button" class="h-10 w-10 rounded-full border-2 border-blue-500 bg-blue-500"></button>
            <button type="button" class="h-10 w-10 rounded-full border border-transparent bg-rose-300"></button>
            <button type="button" class="h-10 w-10 rounded-full border border-transparent bg-emerald-300"></button>
          </div>
        </div>
        <div>
          <label class="text-sm font-semibold text-gray-700" for="size">Tamaño</label>
          <select id="size" class="mt-2 w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <option value="standard">Estándar (1.6 m)</option>
            <option value="long">Largo (2 m)</option>
            <option value="custom">Personalizado</option>
          </select>
        </div>
        <button type="button" class="w-full rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow hover:bg-blue-500">Añadir al carrito</button>
      </form>
      <section class="space-y-4">
        <h2 class="text-lg font-semibold">Detalles</h2>
        <ul class="list-disc space-y-2 pl-5 text-sm text-gray-600">
          <li>Tejido con lana reciclada y algodón orgánico.</li>
          <li>Lavable a mano con agua fría.</li>
          <li>Tiempo de confección estimado: 5 a 7 días hábiles.</li>
        </ul>
      </section>
    </div>
  </article>
@endsection

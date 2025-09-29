@extends('layouts.app')
@section('title', 'Catálogo | PolyCrochet')

@section('content')
  <section class="space-y-8">
    <header class="flex flex-col gap-4 border-b pb-6 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <p class="text-sm uppercase tracking-wide text-blue-600">Colección destacada</p>
        <h1 class="text-3xl font-bold">Explora nuestro catálogo de crochet</h1>
        <p class="mt-2 text-gray-600">Personaliza colores, tamaños y descubre piezas únicas tejidas a mano.</p>
      </div>
      <div class="flex gap-3">
        <select class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
          <option value="">Ordenar por</option>
          <option value="recent">Más recientes</option>
          <option value="price_low">Menor precio</option>
          <option value="price_high">Mayor precio</option>
        </select>
        <select class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
          <option value="">Categoría</option>
          <option value="decor">Decoración</option>
          <option value="juguetes">Juguetes</option>
          <option value="moda">Moda</option>
        </select>
      </div>
    </header>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      @for ($i = 1; $i <= 6; $i++)
        <article class="flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
          <div class="h-48 bg-gradient-to-br from-rose-200 to-sky-200"></div>
          <div class="flex flex-1 flex-col gap-3 p-4">
            <h2 class="text-lg font-semibold">Producto {{ $i }}</h2>
            <p class="text-sm text-gray-600">Descripción breve del producto con sus principales atributos.</p>
            <div class="mt-auto flex items-center justify-between">
              <span class="text-base font-semibold text-gray-900">$19.990</span>
              <a href="{{ route('product.show') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-500">Ver detalle</a>
            </div>
          </div>
        </article>
      @endfor
    </div>
  </section>
@endsection

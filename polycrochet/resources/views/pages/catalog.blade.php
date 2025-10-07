@extends('layouts.app')
@section('title', 'Catálogo | PolyCrochet')

@section('content')
  <section class="space-y-10">
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
          <option value="ramos">Ramos</option>
          <option value="flores">Flores sueltas</option>
          <option value="amigurumis">Amigurumis</option>
          <option value="munecas">Muñecas</option>
        </select>
      </div>
    </header>

    @php
      $products = [
        [
          'name' => 'Ramo Animalitos · Gatito pastel',
          'image' => 'resources/images/ramos/ramo_gato/ramo_azul_gatito.jpg',
          'price' => '$39.990',
          'description' => 'Bouquet con gatito tejido y flores intercambiables. Personaliza colores y mensaje.',
          'tags' => ['Personalizable', 'Favorito'],
        ],
        [
          'name' => 'Ramo Animalitos · Pollito sol',
          'image' => 'resources/images/ramos/ramo_pollo/girasol_pollo/ramo_girasol_pollo.jpg',
          'price' => '$36.500',
          'description' => 'Ramo con pollito y girasoles tejidos a mano. Ideal para cumpleaños y baby showers.',
          'tags' => ['Hecho a pedido'],
        ],
        [
          'name' => 'Set de girasoles con abejas',
          'image' => 'resources/images/girasol/girasol_abejas.jpg',
          'price' => '$22.900',
          'description' => 'Cinco girasoles con pequeñas abejitas crochet para alegrar cualquier rincón.',
          'tags' => ['Set 5 unidades'],
        ],
        [
          'name' => 'Duo floral tonos rosados',
          'image' => 'resources/images/duo/duo_rosa.jpg',
          'price' => '$18.900',
          'description' => 'Pareja de flores en tonos pastel. Combina distintos colores para crear tu arreglo.',
          'tags' => ['Combina colores'],
        ],
        [
          'name' => 'Muñeca crochet · Jardín',
          'image' => 'resources/images/munecas/muneca_girasol/muneca_flores2.jpg',
          'price' => '$29.500',
          'description' => 'Muñeca personalizada con vestido floral y accesorios tejidos. Elige cabellos y tonos.',
          'tags' => ['Personalizable'],
        ],
        [
          'name' => 'Amigurumis duo de abejitas',
          'image' => 'resources/images/animales/abejas.png',
          'price' => '$21.900',
          'description' => 'Pareja de abejitas amigables con detalles bordados. Perfecto para decorar o regalar.',
          'tags' => ['Edición limitada'],
        ],
      ];
    @endphp

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      @foreach ($products as $product)
        <article class="group flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
          <div class="aspect-[4/3] overflow-hidden">
            <img src="{{ Vite::asset($product['image']) }}" alt="{{ $product['name'] }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
          </div>
          <div class="flex flex-1 flex-col gap-3 p-5">
            <div class="flex flex-wrap items-center gap-2 text-xs font-semibold text-blue-600">
              @foreach ($product['tags'] as $tag)
                <span class="rounded-full bg-blue-50 px-2 py-1 text-blue-600">{{ $tag }}</span>
              @endforeach
            </div>
            <h2 class="text-lg font-semibold text-gray-900">{{ $product['name'] }}</h2>
            <p class="text-sm text-gray-600">{{ $product['description'] }}</p>
            <div class="mt-auto flex items-center justify-between">
              <span class="text-base font-semibold text-gray-900">{{ $product['price'] }}</span>
              <a href="{{ route('product.show') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-500">Ver detalle</a>
            </div>
          </div>
        </article>
      @endforeach
    </div>
  </section>
@endsection

@extends('layouts.app')
@section('title', 'Catálogo | PolyCrochet')

@php
  use Illuminate\Support\Str;
@endphp

@section('content')
  <section class="space-y-10">
    <header class="flex flex-col gap-4 border-b pb-6 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <p class="text-sm uppercase tracking-wide text-blue-600">Colección destacada</p>
        <h1 class="text-3xl font-bold">Explora nuestro catálogo de crochet</h1>
        <p class="mt-2 text-gray-600">Personaliza colores, tamaños y descubre piezas únicas tejidas a mano.</p>
      </div>
      <form method="GET" action="{{ route('catalog') }}" class="flex flex-wrap gap-3">
        <label class="flex items-center gap-2 rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 shadow-sm focus-within:border-blue-500 focus-within:outline-none focus-within:ring-1 focus-within:ring-blue-500">
          <span class="sr-only">Buscar</span>
          <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35M17.5 10.75a6.75 6.75 0 1 1-13.5 0 6.75 6.75 0 0 1 13.5 0Z" />
          </svg>
          <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Buscar productos" class="w-full border-none bg-transparent text-sm text-gray-700 placeholder:text-gray-500 focus:outline-none" />
        </label>
        <select name="sort" class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
          <option value="">Ordenar por</option>
          <option value="recent" @selected(($filters['sort'] ?? '') === 'recent')>Más recientes</option>
          <option value="price_low" @selected(($filters['sort'] ?? '') === 'price_low')>Menor precio</option>
          <option value="price_high" @selected(($filters['sort'] ?? '') === 'price_high')>Mayor precio</option>
        </select>
        <select name="category" class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
          <option value="">Categoría</option>
          @foreach ($categories as $category)
            <option value="{{ $category['value'] }}" @selected(($filters['category'] ?? '') === $category['value'])>{{ $category['label'] }}</option>
          @endforeach
        </select>
        <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">Aplicar</button>
      </form>
    </header>

    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
      @forelse ($products as $product)
        @php
          $primaryImage = $product->primaryImage;
          $imageUrl = $primaryImage?->url;
          $tags = collect($product->metadata['tags'] ?? [])->take(2);
          $price = number_format((float) $product->price, 0, ',', '.');
        @endphp
        <article class="flex flex-col overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md">
          <div class="relative h-60 w-full overflow-hidden bg-gray-100">
            @if ($imageUrl)
              <img src="{{ $imageUrl }}" alt="{{ $product->primaryImage?->alt_text ?? 'Producto PolyCrochet' }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" />
            @else
              <div class="flex h-full w-full items-center justify-center text-3xl font-semibold text-gray-400">
                {{ Str::upper(Str::substr($product->name, 0, 1)) }}
              </div>
            @endif
            @if ($tags->isNotEmpty())
              <div class="absolute left-4 top-4 flex flex-wrap gap-2">
                @foreach ($tags as $tag)
                  <span class="rounded-full bg-white/90 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-600">{{ $tag }}</span>
                @endforeach
              </div>
            @endif
          </div>
          <div class="flex flex-1 flex-col gap-4 p-6">
            <header>
              <p class="text-xs uppercase tracking-wide text-blue-600">{{ $product->category ? Str::headline($product->category) : 'Colección PolyCrochet' }}</p>
              <h2 class="mt-1 text-xl font-semibold text-gray-900">{{ $product->name }}</h2>
              <p class="mt-2 text-sm text-gray-600">{{ $product->summary ?? Str::limit($product->description, 120) }}</p>
            </header>
            <div class="mt-auto flex items-center justify-between">
              <span class="text-lg font-semibold text-gray-900">${{ $price }}</span>
              <a href="{{ route('product.show') }}" class="rounded-full border border-blue-600 px-4 py-2 text-sm font-semibold text-blue-600 hover:bg-blue-50">Ver detalles</a>
            </div>
          </div>
        </article>
      @empty
        <div class="col-span-full rounded-3xl border border-dashed border-gray-300 bg-white p-12 text-center">
          <h2 class="text-xl font-semibold text-gray-800">Sin resultados</h2>
          <p class="mt-2 text-sm text-gray-600">No encontramos productos con los filtros aplicados. Intenta limpiar la búsqueda o explora otra categoría.</p>
          <a href="{{ route('catalog') }}" class="mt-4 inline-flex items-center rounded-full border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Limpiar filtros</a>
        </div>
      @endforelse
    </div>

    {{ $products->appends(request()->query())->links() }}
  </section>
@endsection

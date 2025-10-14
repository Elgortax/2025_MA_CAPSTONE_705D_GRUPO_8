@extends('layouts.app')
@section('title', 'Catálogo | PolyCrochet')

@php
  use Illuminate\Support\Str;
@endphp

@section('content')
  <section class="space-y-12">
    <header class="overflow-hidden rounded-[3rem] border border-rose-100/70 bg-white/85 px-6 py-10 shadow-inner shadow-rose-100/50 sm:px-10 lg:px-14">
      <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
        <div class="space-y-3">
          <span class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.25em] text-rose-500">Catálogo actualizado</span>
          <h1 class="text-3xl font-bold text-rose-700 sm:text-4xl">Explora piezas tejidas a mano listas para inspirar</h1>
          <p class="max-w-2xl text-sm text-slate-600 sm:text-base">Personaliza colores, tamaños y mensajes bordados. Cada pieza incluye asesoría personalizada y un toque de magia PolyCrochet.</p>
        </div>
        <div class="flex flex-col gap-3 rounded-2xl border border-rose-100 bg-white/90 p-5 text-sm text-slate-500 shadow">
          <div class="flex items-center justify-between gap-6">
            <span class="uppercase tracking-[0.3em] text-rose-400">Activos</span>
            <span class="text-xl font-semibold text-rose-600">{{ $products->total() }}</span>
          </div>
          <div class="flex items-center justify-between gap-6">
            <span class="uppercase tracking-[0.3em] text-rose-400">Tiempo</span>
            <span class="text-xl font-semibold text-rose-600">7 días promedio</span>
          </div>
        </div>
      </div>

      <form method="GET" action="{{ route('catalog') }}" class="mt-8 grid gap-4 lg:grid-cols-[minmax(0,1fr),minmax(0,0.6fr),minmax(0,0.6fr),auto]">
        <label class="flex items-center gap-3 rounded-full border border-rose-200 bg-white/80 px-4 py-3 text-sm text-slate-600 shadow-inner shadow-rose-100 focus-within:border-rose-400 focus-within:bg-white">
          <svg class="h-4 w-4 text-rose-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35M17.5 10.75a6.75 6.75 0 1 1-13.5 0 6.75 6.75 0 0 1 13.5 0Z" />
          </svg>
          <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Buscar amigurumis, ramos, flores..." class="w-full border-none bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none" />
        </label>
        <select name="sort" class="rounded-full border border-rose-200 bg-white/80 px-4 py-3 text-sm text-slate-600 focus:border-rose-400 focus:bg-white focus:outline-none">
          <option value="">Ordenar por</option>
          <option value="recent" @selected(($filters['sort'] ?? '') === 'recent')>Más recientes</option>
          <option value="price_low" @selected(($filters['sort'] ?? '') === 'price_low')>Precio menor</option>
          <option value="price_high" @selected(($filters['sort'] ?? '') === 'price_high')>Precio mayor</option>
        </select>
        <select name="category" class="rounded-full border border-rose-200 bg-white/80 px-4 py-3 text-sm text-slate-600 focus:border-rose-400 focus:bg-white focus:outline-none">
          <option value="">Categoría</option>
          @foreach ($categories as $category)
            <option value="{{ $category['value'] }}" @selected(($filters['category'] ?? '') === $category['value'])>{{ $category['label'] }}</option>
          @endforeach
        </select>
        <button type="submit" class="rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-6 py-3 text-sm font-semibold text-white shadow shadow-rose-200/50 transition hover:from-rose-500 hover:to-amber-400">Aplicar</button>
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
        <article class="group relative flex flex-col overflow-hidden rounded-[2.4rem] border border-rose-100 bg-white/85 p-6 shadow-lg shadow-rose-100/50 transition hover:-translate-y-1 hover:shadow-rose-200/70">
          <div class="relative overflow-hidden rounded-2xl border border-rose-100">
            @if ($imageUrl)
              <img src="{{ $imageUrl }}" alt="{{ $product->primaryImage?->alt_text ?? 'Producto PolyCrochet' }}" class="h-56 w-full rounded-2xl object-cover transition duration-500 group-hover:scale-105" />
            @else
              <div class="flex h-56 w-full items-center justify-center rounded-2xl bg-rose-50 text-3xl font-semibold text-rose-400">
                {{ Str::upper(Str::substr($product->name, 0, 1)) }}
              </div>
            @endif
            <div class="absolute inset-x-4 top-4 flex flex-wrap gap-2">
              @foreach ($tags as $tag)
                <span class="rounded-full border border-rose-100 bg-white/90 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.25em] text-rose-500">{{ $tag }}</span>
              @endforeach
            </div>
          </div>
          <div class="mt-6 flex flex-1 flex-col gap-4">
            <header class="space-y-1.5">
              <p class="text-xs uppercase tracking-[0.28em] text-rose-400">{{ $product->category ? Str::headline($product->category) : 'Colección PolyCrochet' }}</p>
              <h2 class="text-xl font-semibold text-rose-700">{{ $product->name }}</h2>
              <p class="text-sm text-slate-600">{{ $product->summary ?? Str::limit($product->description, 120) }}</p>
            </header>
              <div class="mt-auto flex items-center justify-between gap-4">
                <span class="text-lg font-semibold text-rose-600">${{ $price }}</span>
              <div class="flex flex-shrink-0 items-center gap-2">
                <a href="{{ route('product.show', $product->slug) }}" class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-rose-500 transition hover:border-rose-300 hover:text-rose-600">Ver detalle</a>
                <form method="POST" action="{{ route('cart.store') }}" class="flex" data-add-to-cart-form>
                  @csrf
                  <input type="hidden" name="product_id" value="{{ $product->id }}">
                  <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-white shadow shadow-rose-200/50 transition hover:from-rose-500 hover:to-amber-400" data-add-to-cart>
                    Agregar
                  </button>
                </form>
              </div>
            </div>
          </div>
        </article>
      @empty
        <div class="col-span-full rounded-[3rem] border border-dashed border-rose-200 bg-white/85 p-12 text-center">
          <h2 class="text-xl font-semibold text-rose-600">Sin resultados</h2>
          <p class="mt-2 text-sm text-slate-600">No encontramos productos con los filtros aplicados. Intenta limpiar la búsqueda o explora otra categoría.</p>
          <a href="{{ route('catalog') }}" class="mt-4 inline-flex items-center gap-2 rounded-full border border-rose-200 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-rose-500 transition hover:border-rose-300 hover:bg-rose-50">Limpiar filtros</a>
        </div>
      @endforelse
    </div>

    <div>
      {{ $products->appends(request()->query())->links() }}
    </div>
  </section>
@endsection

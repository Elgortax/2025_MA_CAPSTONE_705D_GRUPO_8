@extends('layouts.app')
@section('title', $product->name . ' | PolyCrochet')

@php
  use Illuminate\Support\Str;

  $primaryImage = $product->primaryImage;
  $galleryImages = $product->images->where('id', '!=', optional($primaryImage)->id);
  $categorySlug = $product->category ? Str::lower($product->category) : null;
@endphp

@section('content')
  <article class="grid gap-12 lg:grid-cols-[1.05fr,0.95fr] lg:items-start">
    <div class="space-y-6">
      <div class="relative overflow-hidden rounded-[3rem] border border-rose-100 bg-white/90 shadow-xl shadow-rose-100/50">
        @if ($primaryImage?->url)
          <img src="{{ $primaryImage->url }}" alt="{{ $primaryImage->alt_text ?? $product->name }}" class="h-full w-full object-cover" />
        @else
          <div class="flex h-full items-center justify-center bg-rose-50 p-12 text-5xl font-semibold text-rose-400">
            {{ Str::upper(Str::substr($product->name, 0, 1)) }}
          </div>
        @endif
        <div class="absolute inset-x-6 top-6 flex gap-2">
          @if ($product->category)
            <span class="rounded-full border border-rose-100 bg-white/90 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.25em] text-rose-500">{{ Str::headline($product->category) }}</span>
          @endif
          <span class="rounded-full border border-rose-100 bg-rose-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.25em] text-rose-600">Hecho a pedido</span>
        </div>
      </div>
      @if ($galleryImages->isNotEmpty())
        <div class="grid grid-cols-4 gap-3">
          @foreach ($galleryImages->take(8) as $image)
            <div class="overflow-hidden rounded-2xl border border-rose-100 bg-white/90 shadow">
              <img src="{{ $image->url }}" alt="{{ $image->alt_text ?? $product->name }}" class="h-full w-full object-cover transition duration-500 hover:scale-105" />
            </div>
          @endforeach
        </div>
      @endif
    </div>

    <div class="space-y-8">
      <div class="space-y-3">
        @if ($product->category)
          <p class="text-xs uppercase tracking-[0.28em] text-rose-400">{{ Str::headline($product->category) }}</p>
        @endif
        <h1 class="text-3xl font-bold text-rose-700 sm:text-4xl">{{ $product->name }}</h1>
        <p class="text-sm text-slate-600">{{ $product->description ?? 'Pieza tejida a mano lista para personalizar y regalar.' }}</p>
      </div>

      <div class="flex flex-wrap items-center gap-4">
        <span class="text-4xl font-semibold text-rose-600">${{ number_format((float) $product->price, 0, ',', '.') }}</span>
        <span class="rounded-full border border-rose-100 bg-white/90 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-rose-500">Edición artesanal limitada</span>
      </div>

      <form method="POST" action="{{ route('cart.store') }}" class="space-y-6" data-add-to-cart-form>
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <div class="space-y-3">
          <label class="text-xs font-semibold uppercase tracking-[0.28em] text-rose-400" for="quantity">Cantidad</label>
          <input id="quantity" name="quantity" type="number" value="1" min="1" max="10" class="w-24 rounded-2xl border border-rose-100 bg-white/90 px-4 py-2 text-sm text-slate-600 focus:border-rose-300 focus:bg-white focus:outline-none" />
        </div>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
          <span class="text-xs uppercase tracking-[0.25em] text-rose-400">Materiales sustentables y personalizados</span>
          <button type="submit" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-8 py-3 text-sm font-semibold text-white shadow shadow-rose-200/50 transition hover:from-rose-500 hover:to-amber-400" data-add-to-cart>Agregar al carrito</button>
        </div>
      </form>

      <section class="space-y-4 rounded-[2.5rem] border border-rose-100 bg-white/90 p-6 shadow">
        <h2 class="text-lg font-semibold text-rose-600">Detalles</h2>
        <ul class="space-y-3 text-sm text-slate-600">
          <li class="flex gap-3">
            <span class="mt-1 h-1.5 w-1.5 rounded-full bg-rose-300"></span>
            Incluye base firme para mantener la forma del ramo.
          </li>
          <li class="flex gap-3">
            <span class="mt-1 h-1.5 w-1.5 rounded-full bg-rose-300"></span>
            Tejido con algodón hipoalergénico y relleno reciclado.
          </li>
          <li class="flex gap-3">
            <span class="mt-1 h-1.5 w-1.5 rounded-full bg-rose-300"></span>
            Entrega con packaging reutilizable y tarjeta personalizable.
          </li>
        </ul>
      </section>

      @if ($categorySlug)
        <a href="{{ route('catalog', ['category' => $categorySlug]) }}" class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-6 py-3 text-sm font-semibold text-rose-500 transition hover:border-rose-300 hover:bg-rose-50">Ver más en {{ Str::headline($product->category) }}</a>
      @endif
    </div>
  </article>

  @if (isset($relatedProducts) && $relatedProducts->isNotEmpty())
    <section class="mt-16 space-y-6">
      <h2 class="text-2xl font-semibold text-rose-700">También te pueden gustar</h2>
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($relatedProducts as $related)
          @php
            $relatedImage = $related->primaryImage;
          @endphp
          <article class="group flex flex-col overflow-hidden rounded-[2rem] border border-rose-100 bg-white/85 p-5 shadow transition hover:-translate-y-1 hover:shadow-rose-200/60">
            <div class="overflow-hidden rounded-xl border border-rose-100">
              @if ($relatedImage?->url)
                <img src="{{ $relatedImage->url }}" alt="{{ $related->name }}" class="h-44 w-full object-cover transition duration-500 group-hover:scale-105" />
              @else
                <div class="flex h-44 items-center justify-center bg-rose-50 text-2xl font-semibold text-rose-400">
                  {{ Str::upper(Str::substr($related->name, 0, 1)) }}
                </div>
              @endif
            </div>
            <div class="mt-4 space-y-2">
              <p class="text-xs uppercase tracking-[0.2em] text-rose-400">{{ $related->category ? Str::headline($related->category) : 'PolyCrochet' }}</p>
              <h3 class="text-lg font-semibold text-rose-700">{{ $related->name }}</h3>
              <span class="text-sm font-semibold text-rose-600">${{ number_format((float) $related->price, 0, ',', '.') }}</span>
              <a href="{{ route('product.show', $related->slug) }}" class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.2em] text-rose-500 transition hover:text-rose-600">Ver detalle</a>
            </div>
          </article>
        @endforeach
      </div>
    </section>
  @endif
@endsection

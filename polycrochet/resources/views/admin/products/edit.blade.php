@extends('layouts.admin')
@section('title', 'Editar producto | PolyCrochet')
@section('page_heading', 'Editar producto')

@php
  use Illuminate\Support\Str;

  $primaryImage = $product->primaryImage;
  $imageUrl = $primaryImage?->url;
  $tags = collect($product->metadata['tags'] ?? [])->implode(', ');
@endphp

@section('content')
  <section class="space-y-6">
    @if (session('status'))
      <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
        {{ session('status') }}
      </div>
    @endif

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6 rounded-2xl border border-slate-800 bg-slate-900/60 p-6 text-slate-200">
      @csrf
      @method('PUT')

      <div class="grid gap-6 md:grid-cols-2">
        <div class="space-y-4">
          <div>
            <label for="name" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Nombre *</label>
            <input id="name" name="name" type="text" value="{{ old('name', $product->name) }}" required class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
            @error('name')
              <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
            @enderror
          </div>

          <div class="grid gap-4 sm:grid-cols-2">
            <div>
              <label for="sku" class="text-xs font-semibold uppercase tracking-wide text-slate-400">SKU</label>
              <input id="sku" name="sku" type="text" value="{{ old('sku', $product->sku) }}" class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="PC-00001" />
              @error('sku')
                <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label for="category" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Categoría</label>
              <input list="categories" id="category" name="category" value="{{ old('category', Str::headline($product->category ?? '')) }}" class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
              <datalist id="categories">
                @foreach ($categories as $category)
                  <option value="{{ Str::headline($category) }}"></option>
                @endforeach
              </datalist>
              @error('category')
                <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <div>
            <label for="summary" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Resumen</label>
            <input id="summary" name="summary" type="text" value="{{ old('summary', $product->summary) }}" class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Descripción corta para el catálogo" />
            @error('summary')
              <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="description" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Descripción</label>
            <textarea id="description" name="description" rows="6" class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Incluye detalles de materiales, tiempo de confección, personalización...">{{ old('description', $product->description) }}</textarea>
            @error('description')
              <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div class="space-y-4">
          <div class="grid gap-4 sm:grid-cols-2">
            <div>
              <label for="price" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Precio *</label>
              <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $product->price) }}" required class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
              @error('price')
                <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label for="stock" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Stock *</label>
              <input id="stock" name="stock" type="number" min="0" value="{{ old('stock', $product->stock) }}" required class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
              @error('stock')
                <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <div class="space-y-3">
            <label class="text-xs font-semibold uppercase tracking-wide text-slate-400">Imagen principal</label>
            @if ($imageUrl)
              <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-950">
                <img src="{{ $imageUrl }}" alt="Imagen de {{ $product->name }}" class="h-48 w-full object-cover" />
              </div>
            @else
              <div class="flex h-32 items-center justify-center rounded-2xl border border-dashed border-slate-700 bg-slate-950 text-sm text-slate-400">
                Sin imagen cargada
              </div>
            @endif
            <input id="primary_image" name="primary_image" type="file" accept="image/*" class="block w-full text-sm text-slate-200 file:mr-4 file:rounded-md file:border-0 file:bg-blue-600 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-blue-500" />
            <p class="text-xs text-slate-500">Sube una nueva imagen para reemplazar la actual (JPG/PNG, máx. 3 MB).</p>
            @error('primary_image')
              <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="tags" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Etiquetas</label>
            <input id="tags" name="tags" type="text" value="{{ old('tags', $tags) }}" class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Ej: personalizado, floral, regalo" />
            <p class="mt-1 text-xs text-slate-500">Separa las etiquetas con coma.</p>
            @error('tags')
              <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
            @enderror
          </div>

          <div class="flex items-center gap-3 rounded-xl border border-slate-800 bg-slate-950 px-4 py-3">
            <input type="hidden" name="is_active" value="0" />
            <label class="flex items-center gap-3 text-sm">
              <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded border-slate-700 text-emerald-500 focus:ring-emerald-400" {{ old('is_active', $product->is_active) ? 'checked' : '' }} />
              <span>Producto visible en catálogo</span>
            </label>
          </div>
        </div>
      </div>

      <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
          <p class="text-xs uppercase tracking-wide text-slate-500">Última actualización</p>
          <p class="text-sm text-slate-300">{{ $product->updated_at?->format('d/m/Y H:i') }}</p>
        </div>
        <div class="flex flex-wrap gap-3">
          <a href="{{ route('admin.products.index') }}" class="rounded-lg border border-slate-700 px-4 py-2 text-sm font-semibold text-slate-200 hover:border-slate-500">Volver</a>
          <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">Guardar cambios</button>
        </div>
      </div>
    </form>

    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="rounded-2xl border border-rose-500/40 bg-rose-500/10 p-6 text-sm text-rose-100">
      @csrf
      @method('DELETE')
      <h3 class="text-lg font-semibold text-white">Archivar producto</h3>
      <p class="mt-2 text-sm text-rose-200">
        Esta acción ocultará el producto del catálogo y del panel público. Podrás restaurarlo más adelante desde la base de datos.
      </p>
      <button type="submit" class="mt-4 inline-flex items-center rounded-lg border border-rose-400/60 px-4 py-2 text-sm font-semibold text-rose-100 hover:bg-rose-500/20">
        Archivar producto
      </button>
    </form>
  </section>
@endsection

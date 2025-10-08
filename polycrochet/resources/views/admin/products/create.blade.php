@extends('layouts.admin')
@section('title', 'Crear producto | PolyCrochet')
@section('page_heading', 'Nuevo producto')

@section('content')
  <section class="space-y-6">
    @if (session('status'))
      <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
        {{ session('status') }}
      </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 rounded-2xl border border-slate-800 bg-slate-900/60 p-6 text-slate-200">
      @csrf

      <div class="grid gap-6 md:grid-cols-2">
        <div class="space-y-4">
          <div>
            <label for="name" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Nombre *</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
            @error('name')
              <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
            @enderror
          </div>

          <div class="grid gap-4 sm:grid-cols-2">
            <div>
              <label for="sku" class="text-xs font-semibold uppercase tracking-wide text-slate-400">SKU</label>
              <input id="sku" name="sku" type="text" value="{{ old('sku') }}" class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="PC-00001" />
              @error('sku')
                <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label for="category" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Categoría</label>
              <input list="categories" id="category" name="category" value="{{ old('category') }}" class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
              <datalist id="categories">
                @foreach ($categories as $category)
                  <option value="{{ \Illuminate\Support\Str::headline($category) }}"></option>
                @endforeach
              </datalist>
              @error('category')
                <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <div>
            <label for="summary" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Resumen</label>
            <input id="summary" name="summary" type="text" value="{{ old('summary') }}" class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Descripción corta para el catálogo" />
            @error('summary')
              <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="description" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Descripción</label>
            <textarea id="description" name="description" rows="6" class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Incluye detalles de materiales, tiempo de confección, personalización...">{{ old('description') }}</textarea>
            @error('description')
              <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div class="space-y-4">
          <div class="grid gap-4 sm:grid-cols-2">
            <div>
              <label for="price" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Precio *</label>
              <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price') }}" required class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
              @error('price')
                <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label for="stock" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Stock *</label>
              <input id="stock" name="stock" type="number" min="0" value="{{ old('stock', 0) }}" required class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
              @error('stock')
                <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <div>
            <label for="primary_image" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Imagen principal *</label>
            <input id="primary_image" name="primary_image" type="file" accept="image/*" required class="mt-2 block w-full text-sm text-slate-200 file:mr-4 file:rounded-md file:border-0 file:bg-blue-600 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-blue-500" />
            <p class="mt-1 text-xs text-slate-500">Formatos JPG/PNG, máximo 3 MB.</p>
            @error('primary_image')
              <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="tags" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Etiquetas</label>
            <input id="tags" name="tags" type="text" value="{{ old('tags') }}" class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Ej: personalizado, floral, regalo" />
            <p class="mt-1 text-xs text-slate-500">Separa las etiquetas con coma.</p>
            @error('tags')
              <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
            @enderror
          </div>

          <div class="flex items-center gap-3 rounded-xl border border-slate-800 bg-slate-950 px-4 py-3">
            <input type="hidden" name="is_active" value="0" />
            <label class="flex items-center gap-3 text-sm">
              <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded border-slate-700 text-emerald-500 focus:ring-emerald-400" {{ old('is_active', 1) ? 'checked' : '' }} />
              <span>Publicar producto inmediatamente</span>
            </label>
          </div>
        </div>
      </div>

      <div class="flex justify-end gap-3">
        <a href="{{ route('admin.products.index') }}" class="rounded-lg border border-slate-700 px-4 py-2 text-sm font-semibold text-slate-200 hover:border-slate-500">Cancelar</a>
        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">Guardar producto</button>
      </div>
    </form>
  </section>
@endsection

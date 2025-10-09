@extends('layouts.admin')
@section('title', 'Productos | PolyCrochet')
@section('page_heading', 'Productos')

@php
  use Illuminate\Support\Str;
  use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
  <section class="space-y-6">
    @if (session('status'))
      <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
        {{ session('status') }}
      </div>
    @endif

    <div class="flex flex-wrap items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-semibold text-white">Gestión de productos</h1>
        <p class="text-sm text-slate-400">Administra fichas, categorías y disponibilidad.</p>
      </div>
      <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">
          Nuevo producto
        </a>
      </div>
    </div>

    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
      <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-4">
        <p class="text-xs uppercase tracking-wide text-slate-400">Total</p>
        <p class="mt-2 text-2xl font-semibold text-white">{{ number_format($metrics['total'] ?? 0, 0, ',', '.') }}</p>
      </div>
      <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-4">
        <p class="text-xs uppercase tracking-wide text-slate-400">Activos</p>
        <p class="mt-2 text-2xl font-semibold text-emerald-300">{{ number_format($metrics['active'] ?? 0, 0, ',', '.') }}</p>
      </div>
      <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-4">
        <p class="text-xs uppercase tracking-wide text-slate-400">Inactivos</p>
        <p class="mt-2 text-2xl font-semibold text-amber-300">{{ number_format($metrics['inactive'] ?? 0, 0, ',', '.') }}</p>
      </div>
      <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-4">
        <p class="text-xs uppercase tracking-wide text-slate-400">Archivados</p>
        <p class="mt-2 text-2xl font-semibold text-slate-300">{{ number_format($metrics['archived'] ?? 0, 0, ',', '.') }}</p>
      </div>
    </div>

    <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap items-end gap-3 rounded-2xl border border-slate-800 bg-slate-900/60 p-4">
      <div class="flex-1 min-w-[200px]">
        <label for="search" class="block text-xs font-medium uppercase tracking-wide text-slate-400">Buscar</label>
        <input id="search" name="search" type="search" value="{{ $filters['search'] ?? '' }}" placeholder="Nombre o SKU" class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-slate-100 placeholder:text-slate-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
      </div>
      <div>
        <label for="status" class="block text-xs font-medium uppercase tracking-wide text-slate-400">Estado</label>
        <select id="status" name="status" class="mt-1 rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-slate-100 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
          <option value="">Todos</option>
          <option value="published" @selected(($filters['status'] ?? '') === 'published')>Publicado</option>
          <option value="draft" @selected(($filters['status'] ?? '') === 'draft')>Borrador</option>
          <option value="archived" @selected(($filters['status'] ?? '') === 'archived')>Archivado</option>
        </select>
      </div>
      <div>
        <label for="category" class="block text-xs font-medium uppercase tracking-wide text-slate-400">Categoría</label>
        <select id="category" name="category" class="mt-1 rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-slate-100 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
          <option value="">Todas</option>
          @foreach ($categories as $category)
            <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ Str::headline($category) }}</option>
          @endforeach
        </select>
      </div>
      <div class="flex items-center gap-2">
        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">Aplicar</button>
        <a href="{{ route('admin.products.index') }}" class="text-sm font-semibold text-slate-300 hover:text-white">Limpiar</a>
      </div>
    </form>

    <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/60">
      <table class="min-w-full divide-y divide-slate-800 text-sm">
        <thead class="bg-slate-900/70 text-left text-xs uppercase tracking-wide text-slate-400">
          <tr>
            <th class="px-4 py-3">Producto</th>
            <th class="px-4 py-3">Categoría</th>
            <th class="px-4 py-3">Precio</th>
            <th class="px-4 py-3">Stock</th>
            <th class="px-4 py-3">Estado</th>
            <th class="px-4 py-3 text-right">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-800 text-slate-300">
          @forelse ($products as $product)
            @php
              $primaryImage = $product->primaryImage;
              $imageUrl = $primaryImage ? Storage::disk($primaryImage->disk)->url($primaryImage->path) : null;
              $price = number_format((float) $product->price, 0, ',', '.');
              $statusLabel = $product->trashed() ? 'Archivado' : ($product->is_active ? 'Publicado' : 'Borrador');
              $statusColor = $product->trashed() ? 'bg-slate-500/20 text-slate-300' : ($product->is_active ? 'bg-emerald-500/10 text-emerald-300' : 'bg-amber-500/10 text-amber-300');
            @endphp
            <tr>
              <td class="px-4 py-3">
                <div class="flex items-center gap-3">
                  <div class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-xl border border-slate-800 bg-slate-950">
                    @if ($imageUrl)
                      <img src="{{ $imageUrl }}" alt="Imagen de {{ $product->name }}" class="h-full w-full object-cover" />
                    @else
                      <span class="text-lg font-semibold text-slate-400">{{ Str::upper(Str::substr($product->name, 0, 1)) }}</span>
                    @endif
                  </div>
                  <div>
                    <p class="font-semibold text-white">{{ $product->name }}</p>
                    <p class="text-xs text-slate-400">SKU: {{ $product->sku ?? '—' }}</p>
                  </div>
                </div>
              </td>
              <td class="px-4 py-3">{{ $product->category ? Str::headline($product->category) : 'Sin categoría' }}</td>
              <td class="px-4 py-3">${{ $price }}</td>
              <td class="px-4 py-3">{{ $product->stock }}</td>
              <td class="px-4 py-3">
                <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $statusColor }}">{{ $statusLabel }}</span>
              </td>
              <td class="px-4 py-3 text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.products.edit', $product) }}" class="text-xs font-semibold text-blue-300 hover:text-blue-200">Editar</a>
                  <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('¿Eliminar el producto {{ $product->name }}? Esta acción no se puede deshacer.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs font-semibold text-rose-300 hover:text-rose-200">Eliminar</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-400">
                No hay productos para mostrar. Crea tu primer producto para comenzar a construir el catálogo.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{ $products->links() }}
  </section>
@endsection

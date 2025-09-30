@extends('layouts.admin')
@section('title', 'Productos | PolyCrochet')
@section('page_heading', 'Productos')

@section('content')
  <section class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-semibold text-white">Gestión de productos</h1>
        <p class="text-sm text-slate-400">Administra fichas, categorías y disponibilidad.</p>
      </div>
      <div class="flex gap-3">
        <button type="button" class="inline-flex items-center rounded-lg border border-slate-700 px-4 py-2 text-sm font-semibold text-slate-300 hover:border-blue-500 hover:text-blue-400">Importar cat�logo</button>
        <button type="button" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">Nuevo producto</button>
      </div>
    </div>

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
          @foreach ([
            ['Bufanda multicolor', 'Accesorios', '$24.990', 'Disponible', 'Publicado'],
            ['Deco mural boho', 'Decoración', '$18.500', 'Bajo', 'Publicado'],
            ['Amigurumi osito', 'Juguetes', '$15.900', 'Sin stock', 'Borrador']
          ] as $producto)
            <tr>
              <td class="px-4 py-3 font-semibold text-white">{{ $producto[0] }}</td>
              <td class="px-4 py-3">{{ $producto[1] }}</td>
              <td class="px-4 py-3">{{ $producto[2] }}</td>
              <td class="px-4 py-3">{{ $producto[3] }}</td>
              <td class="px-4 py-3">
                <span class="rounded-full bg-blue-500/10 px-2 py-0.5 text-xs font-semibold text-blue-300">{{ $producto[4] }}</span>
              </td>
              <td class="px-4 py-3 text-right">
                <button type="button" class="text-xs font-semibold text-blue-300 hover:text-blue-200">Editar</button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </section>
@endsection

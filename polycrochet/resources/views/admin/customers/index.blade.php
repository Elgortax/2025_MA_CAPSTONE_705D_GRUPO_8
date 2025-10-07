@extends('layouts.admin')
@section('title', 'Clientes | PolyCrochet')
@section('page_heading', 'Clientes')

@section('content')
  <section class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-semibold text-white">Clientes</h1>
        <p class="text-sm text-slate-400">Visualiza información, pedidos y segmenta campañas.</p>
      </div>
      <input type="search" placeholder="Buscar cliente" class="w-64 rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-xs text-slate-300 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/60">
      <table class="min-w-full divide-y divide-slate-800 text-sm">
        <thead class="bg-slate-900/70 text-left text-xs uppercase tracking-wide text-slate-400">
          <tr>
            <th class="px-4 py-3">Nombre</th>
            <th class="px-4 py-3">Correo</th>
            <th class="px-4 py-3">Pedidos</th>
            <th class="px-4 py-3">último pedido</th>
            <th class="px-4 py-3">Estado</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-800 text-slate-300">
          @foreach ([
            ['Camila Rojas', 'camila@ejemplo.cl', 6, '20/09/2025', 'Premium'],
            ['Luis Díaz', 'luis@ejemplo.cl', 2, '18/09/2025', 'Nuevo'],
            ['Valentina Soto', 'valentina@ejemplo.cl', 5, '15/09/2025', 'Activo']
          ] as $cliente)
            <tr>
              <td class="px-4 py-3 font-semibold text-white">{{ $cliente[0] }}</td>
              <td class="px-4 py-3">{{ $cliente[1] }}</td>
              <td class="px-4 py-3">{{ $cliente[2] }}</td>
              <td class="px-4 py-3">{{ $cliente[3] }}</td>
              <td class="px-4 py-3"><span class="rounded-full bg-purple-500/10 px-2 py-0.5 text-xs font-semibold text-purple-300">{{ $cliente[4] }}</span></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </section>
@endsection

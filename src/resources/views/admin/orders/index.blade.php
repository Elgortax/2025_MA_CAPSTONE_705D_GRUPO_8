@extends('layouts.admin')
@section('title', 'Pedidos | PolyCrochet')
@section('page_heading', 'Pedidos')

@section('content')
  <section class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-semibold text-white">Pedidos</h1>
        <p class="text-sm text-slate-400">Monitorea el avance de cada pedido y registra notas internas.</p>
      </div>
      <div class="flex gap-3">
        <select class="rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-xs text-slate-300 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
          <option value="">Estado</option>
          <option value="pending">Pendiente</option>
          <option value="paid">Pagado</option>
          <option value="crafting">En confección</option>
          <option value="shipped">Enviado</option>
        </select>
        <input type="search" placeholder="Buscar pedido o cliente" class="w-56 rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-xs text-slate-300 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
      </div>
    </div>

    <div class="space-y-4">
      @foreach ([
        ['PC-10294', 'Camila Rojas', '$47.990', 'Pagado', 'En confección'],
        ['PC-10295', 'Luis Díaz', '$38.500', 'Pagado', 'Preparando envío'],
        ['PC-10296', 'Valentina Soto', '$15.900', 'Pendiente', 'Esperando pago']
      ] as $pedido)
        <article class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6">
          <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
              <h2 class="text-lg font-semibold text-white">{{ $pedido[0] }}</h2>
              <p class="text-xs text-slate-400">Cliente: {{ $pedido[1] }} · Monto: {{ $pedido[2] }}</p>
            </div>
            <div class="flex gap-2 text-xs">
              <span class="rounded-full bg-emerald-500/10 px-2 py-0.5 font-semibold text-emerald-300">{{ $pedido[3] }}</span>
              <span class="rounded-full bg-blue-500/10 px-2 py-0.5 font-semibold text-blue-300">{{ $pedido[4] }}</span>
            </div>
          </div>
          <div class="mt-4 flex flex-wrap items-center justify-between gap-3 text-xs text-slate-400">
            <p>Actualizado hace 2 horas</p>
            <div class="flex gap-3">
              <button type="button" class="font-semibold text-blue-300 hover:text-blue-200">Actualizar estado</button>
              <button type="button" class="font-semibold text-slate-300 hover:text-slate-100">Agregar nota</button>
            </div>
          </div>
        </article>
      @endforeach
    </div>
  </section>
@endsection

@extends('layouts.admin')
@section('title', 'Dashboard | PolyCrochet')
@section('page_heading', 'Dashboard')

@section('content')
  <section class="grid gap-6 lg:grid-cols-3">
    <article class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6">
      <p class="text-sm text-slate-400">Ventas del mes</p>
      <p class="mt-2 text-3xl font-semibold text-white">$1.280.000</p>
      <p class="mt-1 text-xs text-emerald-400">+12% vs mes anterior</p>
    </article>
    <article class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6">
      <p class="text-sm text-slate-400">Pedidos activos</p>
      <p class="mt-2 text-3xl font-semibold text-white">24</p>
      <p class="mt-1 text-xs text-slate-400">12 en confección  8 en despacho  4 por confirmar pago</p>
    </article>
    <article class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6">
      <p class="text-sm text-slate-400">Clientes nuevos</p>
      <p class="mt-2 text-3xl font-semibold text-white">58</p>
      <p class="mt-1 text-xs text-slate-400">últimos 30 días</p>
    </article>
  </section>

  <section class="mt-8 grid gap-6 lg:grid-cols-[2fr,1fr]">
    <article class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6">
      <h2 class="text-lg font-semibold text-white">Pedidos recientes</h2>
      <ul class="mt-4 space-y-4 text-sm text-slate-300">
        <li class="flex justify-between border-b border-slate-800 pb-4">
          <div>
            <p class="font-semibold text-white">PC-10294</p>
            <p class="text-xs text-slate-400">Camila Rojas  Bufanda multicolor</p>
          </div>
          <span class="rounded-full bg-emerald-500/10 px-2 py-0.5 text-xs font-semibold text-emerald-400">Pagado</span>
        </li>
        <li class="flex justify-between border-b border-slate-800 pb-4">
          <div>
            <p class="font-semibold text-white">PC-10295</p>
            <p class="text-xs text-slate-400">Luis Díaz Deco mural</p>
          </div>
          <span class="rounded-full bg-amber-500/10 px-2 py-0.5 text-xs font-semibold text-amber-300">En confección</span>
        </li>
        <li class="flex justify-between">
          <div>
            <p class="font-semibold text-white">PC-10296</p>
            <p class="text-xs text-slate-400">Valentina Soto Amigurumi</p>
          </div>
          <span class="rounded-full bg-blue-500/10 px-2 py-0.5 text-xs font-semibold text-blue-300">Por pagar</span>
        </li>
      </ul>
    </article>
    <article class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6">
      <h2 class="text-lg font-semibold text-white">Tareas del taller</h2>
      <ul class="mt-4 space-y-3 text-sm text-slate-300">
        <li>Preparar paletas de color para la colección de verano.</li>
        <li>Confirmar medidas personalizadas de pedidos en curso.</li>
        <li>Actualizar inventario de hilos premium.</li>
      </ul>
    </article>
  </section>
@endsection

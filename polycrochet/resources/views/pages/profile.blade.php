@extends('layouts.app')
@section('title', 'Perfil | PolyCrochet')

@section('content')
  <section class="space-y-8">
    <header class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-3xl font-bold">Hola, Camila</h1>
        <p class="text-sm text-gray-600">Administra tus datos personales y revisa tus pedidos más recientes.</p>
      </div>
      <a href="{{ route('account') }}" class="rounded-full border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:border-blue-400 hover:text-blue-600">Cerrar sesión</a>
    </header>

    <div class="grid gap-6 lg:grid-cols-3">
      <article class="lg:col-span-2 space-y-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold">Datos personales</h2>
        <form class="grid gap-4 sm:grid-cols-2">
          <label class="text-sm font-medium text-gray-700">Nombre
            <input type="text" value="Camila" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
          </label>
          <label class="text-sm font-medium text-gray-700">Apellido
            <input type="text" value="Rojas" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
          </label>
          <label class="text-sm font-medium text-gray-700">Correo
            <input type="email" value="camila@ejemplo.cl" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
          </label>
          <label class="text-sm font-medium text-gray-700">Teléfono
            <input type="tel" value="+56 9 1234 5678" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
          </label>
          <div class="sm:col-span-2 text-right">
            <button type="button" class="rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-500">Guardar cambios</button>
          </div>
        </form>
      </article>

      <aside class="space-y-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold">Mis pedidos</h2>
        <ul class="space-y-3 text-sm text-gray-600">
          <li>
            <div class="flex justify-between">
              <span><strong>PC-10294</strong></span>
              <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-700">En confección</span>
            </div>
            <p class="text-xs text-gray-500">20 septiembre 2025  $47.990</p>
          </li>
          <li>
            <div class="flex justify-between">
              <span><strong>PC-10210</strong></span>
              <span class="rounded-full bg-gray-200 px-2 py-0.5 text-xs font-semibold text-gray-700">Entregado</span>
            </div>
            <p class="text-xs text-gray-500">02 agosto 2025  $32.500</p>
          </li>
        </ul>
        <a href="{{ route('order.confirmation') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-500">Ver detalles</a>
      </aside>
    </div>
  </section>
@endsection

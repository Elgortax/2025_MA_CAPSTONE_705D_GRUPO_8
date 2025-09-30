@extends('layouts.admin')
@section('title', 'Configuración | PolyCrochet')
@section('page_heading', 'Configuración')

@section('content')
  <section class="space-y-6">
    <header>
      <h1 class="text-2xl font-semibold text-white">Preferencias generales</h1>
      <p class="mt-2 text-sm text-slate-400">Define opciones de pagos, envíos e información de la tienda.</p>
    </header>

    <div class="grid gap-6 lg:grid-cols-2">
      <article class="space-y-4 rounded-2xl border border-slate-800 bg-slate-900/60 p-6">
        <h2 class="text-lg font-semibold text-white">PayPal Sandbox</h2>
        <form class="space-y-4 text-sm">
          <label class="block text-slate-300">Client ID
            <input type="text" class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-slate-200 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="sb-..." />
          </label>
          <label class="block text-slate-300">Client Secret
            <input type="password" class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-slate-200 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="������" />
          </label>
          <button type="button" class="rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-500">Guardar credenciales</button>
        </form>
      </article>

      <article class="space-y-4 rounded-2xl border border-slate-800 bg-slate-900/60 p-6">
        <h2 class="text-lg font-semibold text-white">Envíos</h2>
        <form class="space-y-4 text-sm">
          <label class="block text-slate-300">Método principal
            <select class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-slate-200 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
              <option>Chilexpress</option>
              <option>Starken</option>
              <option>Retiro en taller</option>
            </select>
          </label>
          <label class="block text-slate-300">Tarifa base
            <input type="number" class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-slate-200 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="4500" />
          </label>
          <button type="button" class="rounded-full border border-slate-700 px-6 py-3 text-sm font-semibold text-slate-200 hover:border-blue-500 hover:text-blue-300">Actualizar envíos</button>
        </form>
      </article>
    </div>

    <article class="space-y-4 rounded-2xl border border-slate-800 bg-slate-900/60 p-6">
      <h2 class="text-lg font-semibold text-white">Información de la tienda</h2>
      <form class="grid gap-4 text-sm lg:grid-cols-3">
        <label class="lg:col-span-2 text-slate-300">Nombre comercial
          <input type="text" value="PolyCrochet" class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-slate-200 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
        </label>
        <label class="text-slate-300">Correo soporte
          <input type="email" value="hola@polycrochet.cl" class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-slate-200 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
        </label>
        <label class="lg:col-span-3 text-slate-300">Mensaje para el checkout
          <textarea rows="3" class="mt-1 w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-slate-200 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">Gracias por apoyar el trabajo hecho a mano. Cada compra financia talleres gratuitos para mujeres emprendedoras.</textarea>
        </label>
        <div class="lg:col-span-3 text-right">
          <button type="button" class="rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-500">Guardar cambios</button>
        </div>
      </form>
    </article>
  </section>
@endsection

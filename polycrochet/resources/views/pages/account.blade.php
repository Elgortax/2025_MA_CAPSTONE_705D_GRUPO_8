@extends('layouts.app')
@section('title', 'Cuenta | PolyCrochet')

@section('content')
  <section class="grid gap-12 lg:grid-cols-2">
    <div class="space-y-6">
      <header class="space-y-2">
        <h1 class="text-3xl font-bold text-rose-700">Accede a tu cuenta</h1>
        <p class="text-sm text-slate-600">Gestiona tus pedidos, direcciones y preferencias desde un mismo lugar.</p>
      </header>
      <article class="space-y-4 rounded-[2.5rem] border border-rose-100 bg-white/90 p-6 shadow-lg shadow-rose-100/40">
        <div class="flex items-center gap-3">
          <div class="h-12 w-12 overflow-hidden rounded-full border border-rose-100">
            <img src="{{ Vite::asset('resources/images/animales/pollito.png') }}" alt="Amigurumi pollito" class="h-full w-full object-cover" />
          </div>
          <div>
            <h2 class="text-lg font-semibold text-rose-600">Iniciar sesión</h2>
            <p class="text-xs text-slate-500">Revisa tus pedidos y descarga comprobantes.</p>
          </div>
        </div>
        <form class="space-y-4">
          <label class="text-sm font-medium text-rose-600">Correo electrónico
            <input type="email" class="mt-1 w-full rounded-full border border-rose-200 px-4 py-2 text-sm text-slate-700 focus:border-rose-400 focus:outline-none focus:ring-1 focus:ring-rose-300" placeholder="tu@correo.cl" />
          </label>
          <label class="text-sm font-medium text-rose-600">Contraseña
            <input type="password" class="mt-1 w-full rounded-full border border-rose-200 px-4 py-2 text-sm text-slate-700 focus:border-rose-400 focus:outline-none focus:ring-1 focus:ring-rose-300" placeholder="••••••" />
          </label>
          <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 text-slate-600">
              <input type="checkbox" class="h-4 w-4 rounded border-rose-300 text-rose-500 focus:ring-rose-400" />
              Recordarme
            </label>
            <a href="#" class="font-semibold text-rose-500 hover:text-rose-600">¿Olvidaste tu contraseña?</a>
          </div>
          <button type="button" class="w-full rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-6 py-3 text-sm font-semibold text-white shadow shadow-rose-200/50 transition hover:from-rose-500 hover:to-amber-400">Ingresar</button>
        </form>
      </article>
    </div>

    <article class="space-y-5 rounded-[2.5rem] border border-rose-100 bg-white/90 p-6 shadow-lg shadow-rose-100/40">
      <div class="overflow-hidden rounded-[2rem]">
        <img src="{{ Vite::asset('resources/images/munecas/muneca_celeste/muneca_celeste2.jpg') }}" alt="Muñeca crochet tonos celestes" class="h-48 w-full object-cover" />
      </div>
      <div class="space-y-4">
        <h2 class="text-lg font-semibold text-rose-600">Crear una cuenta</h2>
        <p class="text-sm text-slate-600">Regístrate para guardar tus direcciones, recibir novedades y seguir tus pedidos fácilmente.</p>
        <form class="grid gap-4">
          <label class="text-sm font-medium text-rose-600">Nombre completo
            <input type="text" class="mt-1 w-full rounded-full border border-rose-200 px-4 py-2 text-sm text-slate-700 focus:border-rose-400 focus:outline-none focus:ring-1 focus:ring-rose-300" placeholder="Ej. Camila Rojas" />
          </label>
          <label class="text-sm font-medium text-rose-600">Correo electrónico
            <input type="email" class="mt-1 w-full rounded-full border border-rose-200 px-4 py-2 text-sm text-slate-700 focus:border-rose-400 focus:outline-none focus:ring-1 focus:ring-rose-300" placeholder="tu@correo.cl" />
          </label>
          <label class="text-sm font-medium text-rose-600">Contraseña
            <input type="password" class="mt-1 w-full rounded-full border border-rose-200 px-4 py-2 text-sm text-slate-700 focus:border-rose-400 focus:outline-none focus:ring-1 focus:ring-rose-300" placeholder="••••••" />
          </label>
          <label class="text-sm font-medium text-rose-600">Confirmar contraseña
            <input type="password" class="mt-1 w-full rounded-full border border-rose-200 px-4 py-2 text-sm text-slate-700 focus:border-rose-400 focus:outline-none focus:ring-1 focus:ring-rose-300" placeholder="••••••" />
          </label>
          <button type="button" class="w-full rounded-full border border-rose-200 px-6 py-3 text-sm font-semibold text-rose-500 transition hover:border-rose-300 hover:bg-rose-50">Crear cuenta</button>
        </form>
      </div>
    </article>
  </section>
@endsection

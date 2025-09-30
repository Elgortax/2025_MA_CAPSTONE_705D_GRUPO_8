@extends('layouts.app')
@section('title', 'Cuenta | PolyCrochet')

@section('content')
  <section class="grid gap-12 lg:grid-cols-2">
    <div class="space-y-6">
      <header>
        <h1 class="text-3xl font-bold">Accede a tu cuenta</h1>
        <p class="mt-2 text-sm text-gray-600">Gestiona tus pedidos, direcciones y preferencias desde un mismo lugar.</p>
      </header>
      <article class="space-y-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold">Iniciar sesión</h2>
        <form class="space-y-4">
          <label class="text-sm font-medium text-gray-700">Correo electrónico
            <input type="email" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="tu@correo.cl" />
          </label>
          <label class="text-sm font-medium text-gray-700">Contraseña
            <input type="password" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="������" />
          </label>
          <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 text-gray-600">
              <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
              Recordarme
            </label>
            <a href="#" class="font-semibold text-blue-600 hover:text-blue-500">¿Olvidaste tu contraseña?</a>
          </div>
          <button type="button" class="w-full rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-500">Ingresar</button>
        </form>
      </article>
    </div>

    <article class="space-y-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
      <h2 class="text-lg font-semibold">Crear una cuenta</h2>
      <p class="text-sm text-gray-600">Regístrate para guardar tus direcciones, recibir novedades y seguir tus pedidos fácilmente.</p>
      <form class="space-y-4">
        <label class="text-sm font-medium text-gray-700">Nombre completo
          <input type="text" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Ej. Camila Rojas" />
        </label>
        <label class="text-sm font-medium text-gray-700">Correo electrónico
          <input type="email" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="tu@correo.cl" />
        </label>
        <label class="text-sm font-medium text-gray-700">Contraseña
          <input type="password" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="������" />
        </label>
        <label class="text-sm font-medium text-gray-700">Confirmar contraseña
          <input type="password" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="������" />
        </label>
        <button type="button" class="w-full rounded-full border border-blue-600 px-6 py-3 text-sm font-semibold text-blue-600 hover:bg-blue-50">Crear cuenta</button>
      </form>
    </article>
  </section>
@endsection

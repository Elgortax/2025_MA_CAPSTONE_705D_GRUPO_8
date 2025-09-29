@extends('layouts.app')
@section('title', 'Inicio | PolyCrochet')

@section('content')
  <section class="space-y-12">
    <div class="grid gap-10 lg:grid-cols-[1.1fr,0.9fr] lg:items-center">
      <div class="space-y-6">
        <p class="text-sm uppercase tracking-wide text-blue-600">Hecho a mano en Chile</p>
        <h1 class="text-4xl font-bold sm:text-5xl">Crochet personalizado para regalar y disfrutar</h1>
        <p class="text-lg text-gray-600">Diseñamos piezas únicas que combinan color, textura y calidez. Elige tu estilo, personaliza detalles y recibe tu pedido listo para sorprender.</p>
        <div class="flex flex-wrap gap-3">
          <a href="{{ route('catalog') }}" class="inline-flex items-center rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-500">Ver catálogo</a>
          <a href="{{ route('nosotros') }}" class="inline-flex items-center rounded-full border border-gray-300 px-6 py-3 text-sm font-semibold text-gray-700 hover:border-blue-400 hover:text-blue-600">Conoce nuestra historia</a>
        </div>
        <dl class="grid gap-6 sm:grid-cols-3">
          <div>
            <dt class="text-3xl font-bold text-gray-900">+150</dt>
            <dd class="text-sm text-gray-600">Pedidos personalizados enviados</dd>
          </div>
          <div>
            <dt class="text-3xl font-bold text-gray-900">7 días</dt>
            <dd class="text-sm text-gray-600">Tiempo promedio de confección</dd>
          </div>
          <div>
            <dt class="text-3xl font-bold text-gray-900">100%</dt>
            <dd class="text-sm text-gray-600">Materiales sustentables</dd>
          </div>
        </dl>
      </div>
      <div class="relative">
        <div class="aspect-square rounded-[3rem] bg-gradient-to-br from-rose-200 via-amber-200 to-sky-200"></div>
        <div class="absolute -bottom-10 left-1/2 w-64 -translate-x-1/2 rounded-3xl border border-white bg-white/80 p-4 backdrop-blur shadow-lg">
          <p class="text-sm font-semibold text-gray-900">Colección primavera 2025</p>
          <p class="text-xs text-gray-600">Bufandas acolchadas, bolsos tonos pastel y deco infantil.</p>
        </div>
      </div>
    </div>

    <section class="space-y-6">
      <header class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <h2 class="text-2xl font-bold">Categorías favoritas</h2>
          <p class="text-sm text-gray-600">Descubre lo que la comunidad PolyCrochet está amando este mes.</p>
        </div>
        <a href="{{ route('catalog') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-500">Ver todo</a>
      </header>
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <article class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
          <h3 class="text-lg font-semibold">Decoración para el hogar</h3>
          <p class="mt-2 text-sm text-gray-600">Murales, cojines y mantas que llenan de color tus espacios.</p>
        </article>
        <article class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
          <h3 class="text-lg font-semibold">Amigurumis personalizados</h3>
          <p class="mt-2 text-sm text-gray-600">Regalos con historia: diseña personajes únicos y memorables.</p>
        </article>
        <article class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
          <h3 class="text-lg font-semibold">Accesorios de moda</h3>
          <p class="mt-2 text-sm text-gray-600">Gorros, bolsos y bufandas con tu paleta favorita.</p>
        </article>
      </div>
    </section>
  </section>
@endsection

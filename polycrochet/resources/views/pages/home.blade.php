@extends('layouts.app')
@section('title', 'Inicio | PolyCrochet')

@section('content')
  <section class="space-y-16">
    <div class="grid gap-10 lg:grid-cols-[1.05fr,0.95fr] lg:items-center">
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
        <div class="aspect-square overflow-hidden rounded-[3rem]">
          <img src="{{ Vite::asset('resources/images/ramos/ramo_gato/ramo_rosa_gato.jpg') }}" alt="Ramo crochet con gatito" class="h-full w-full object-cover" />
        </div>
        <div class="absolute -bottom-10 left-1/2 w-72 -translate-x-1/2 rounded-3xl border border-white bg-white/90 p-4 text-left shadow-xl lg:-bottom-12">
          <p class="text-sm font-semibold text-gray-900">Colección Animalitos</p>
          <p class="mt-1 text-xs text-gray-600">Ramos temáticos con gatitos, abejas y pollitos para celebrar a quien más quieres.</p>
        </div>
      </div>
    </div>

    <section class="space-y-8">
      <header class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <h2 class="text-2xl font-bold">Categorías favoritas</h2>
          <p class="text-sm text-gray-600">Descubre lo que la comunidad PolyCrochet está amando este mes.</p>
        </div>
        <a href="{{ route('catalog') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-500">Ver todo</a>
      </header>
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <article class="group overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
          <div class="aspect-video overflow-hidden">
            <img src="{{ Vite::asset('resources/images/ramos/ramo_pollo/girasol_pollo/ramo_pollito.jpg') }}" alt="Ramo crochet con pollitos" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
          </div>
          <div class="space-y-2 p-6">
            <h3 class="text-lg font-semibold">Ramos temáticos</h3>
            <p class="text-sm text-gray-600">Arma tu bouquet con pollitos, gatitos o abejas y combina los tonos que más te gusten.</p>
          </div>
        </article>
        <article class="group overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
          <div class="aspect-video overflow-hidden">
            <img src="{{ Vite::asset('resources/images/duo/duo_rosa.jpg') }}" alt="Flores crochet dúo color rosa" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
          </div>
          <div class="space-y-2 p-6">
            <h3 class="text-lg font-semibold">Flores personalizables</h3>
            <p class="text-sm text-gray-600">Elige el set ideal para tu espacio: dúo cromático, girasoles vibrantes o rosas delicadas.</p>
          </div>
        </article>
        <article class="group overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
          <div class="aspect-video overflow-hidden">
            <img src="{{ Vite::asset('resources/images/animales/abejas.png') }}" alt="Amigurumis de abejas" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
          </div>
          <div class="space-y-2 p-6">
            <h3 class="text-lg font-semibold">Amigurumis con historia</h3>
            <p class="text-sm text-gray-600">Personajes tejidos a mano: abejas, nutrias, búhos y más para sorprender con ternura.</p>
          </div>
        </article>
      </div>
    </section>
  </section>
@endsection

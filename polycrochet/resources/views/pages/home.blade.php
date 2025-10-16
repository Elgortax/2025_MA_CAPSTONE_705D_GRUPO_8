@extends('layouts.app')
@section('title', 'Inicio | PolyCrochet')

@section('content')
  <section class="space-y-20">
    <div class="overflow-hidden rounded-[3rem] border border-rose-100/70 bg-white/90 px-6 py-12 shadow-xl shadow-rose-100/50 sm:px-10 lg:px-16 lg:py-16">
      <div class="grid gap-12 lg:grid-cols-[1.2fr,0.8fr] lg:items-center">
        <div class="space-y-6">
          <span class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-rose-500">Nueva colección</span>
          <h1 class="text-4xl font-bold leading-tight text-rose-700 sm:text-5xl lg:text-6xl">La magia del crochet para regalar, coleccionar y decorar.</h1>
          <p class="max-w-xl text-base text-slate-600 sm:text-lg">Curamos piezas hechas a mano con texturas suaves, colores armónicos y detalles personalizables. Elige tus favoritos, recibe asesoría y disfruta de envíos ágiles a todo Chile.</p>
          <div class="flex flex-wrap gap-3">
            <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-7 py-3 text-sm font-semibold text-white shadow shadow-rose-200/50 transition hover:from-rose-500 hover:to-amber-400">Ver catálogo</a>
            <a href="{{ route('nosotros') }}" class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-7 py-3 text-sm font-semibold text-rose-500 transition hover:border-rose-300 hover:bg-rose-50">Conoce nuestra historia</a>
          </div>
          <dl class="grid gap-6 sm:grid-cols-3">
            <div class="rounded-2xl border border-rose-100 bg-white/90 p-4 shadow">
              <dt class="text-3xl font-semibold text-rose-600">+150</dt>
              <dd class="mt-1 text-xs uppercase tracking-[0.2em] text-slate-500">Pedidos personalizados</dd>
            </div>
            <div class="rounded-2xl border border-rose-100 bg-white/90 p-4 shadow">
              <dt class="text-3xl font-semibold text-rose-600">7 días</dt>
              <dd class="mt-1 text-xs uppercase tracking-[0.2em] text-slate-500">Promedio confección</dd>
            </div>
            <div class="rounded-2xl border border-rose-100 bg-white/90 p-4 shadow">
              <dt class="text-3xl font-semibold text-rose-600">100%</dt>
              <dd class="mt-1 text-xs uppercase tracking-[0.2em] text-slate-500">Materiales eco</dd>
            </div>
          </dl>
        </div>
        <div class="relative">
          <div class="absolute -left-12 top-10 hidden h-24 w-24 rounded-full bg-rose-200/50 blur-3xl lg:block"></div>
          <div class="absolute -right-10 bottom-10 hidden h-24 w-24 rounded-full bg-emerald-100/60 blur-3xl lg:block"></div>
          <div class="aspect-[4/5] overflow-hidden rounded-[3rem] border border-rose-100 shadow-2xl shadow-rose-100/50">
            <img src="{{ Vite::asset('resources/images/ramos/ramo_gato/ramo_rosa_gato.jpg') }}" alt="Ramo crochet con gatito" class="h-full w-full object-cover" />
          </div>
          <div class="absolute -bottom-10 left-1/2 w-[18rem] -translate-x-1/2 rounded-3xl border border-rose-100 bg-white/95 p-5 shadow-lg shadow-rose-100/50">
            <p class="text-sm font-semibold text-rose-600">Colección Animalitos</p>
            <p class="mt-2 text-xs text-slate-500">Bouquets con personajes que cuentan historias: gatitos, nutrias y pollitos soñadores.</p>
          </div>
        </div>
      </div>
    </div>

    <section class="space-y-10">
      <header class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <p class="text-xs uppercase tracking-[0.28em] text-rose-400">Descubre</p>
          <h2 class="text-3xl font-bold text-rose-700">Colecciones en tendencia</h2>
          <p class="text-sm text-slate-600">Lo que la comunidad PolyCrochet está amando esta temporada.</p>
        </div>
        <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.25em] text-rose-500 transition hover:text-rose-600">Ver todo</a>
      </header>

      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ([
          [
            'title' => 'Ramos temáticos',
            'description' => 'Gatitos, abejas o nutrias: arma combinaciones únicas con mensajes tejidos.',
            'image' => 'resources/images/ramos/ramo_pollo/girasol_pollo/ramo_pollito.jpg',
            'tag' => 'Más pedido',
          ],
          [
            'title' => 'Flores personalizables',
            'description' => 'Elige tonos y tamaños para crear arreglos que nunca se marchitan.',
            'image' => 'resources/images/duo/duo_rosa.jpg',
            'tag' => 'A tu medida',
          ],
          [
            'title' => 'Amigurumis con historia',
            'description' => 'Personajes tejidos a mano inspirados en tus momentos favoritos.',
            'image' => 'resources/images/animales/abejas.png',
            'tag' => 'Nuevo',
          ],
        ] as $collection)
          <article class="group relative overflow-hidden rounded-3xl border border-rose-100 bg-white/90 p-6 shadow-lg shadow-rose-100/40 transition hover:-translate-y-1 hover:shadow-rose-200/70">
            <div class="relative overflow-hidden rounded-2xl border border-rose-100">
              <img src="{{ Vite::asset($collection['image']) }}" alt="{{ $collection['title'] }}" class="h-48 w-full rounded-2xl object-cover transition duration-500 group-hover:scale-105" />
            </div>
            <div class="relative mt-6 space-y-3">
              <span class="inline-flex items-center rounded-full border border-rose-200 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.25em] text-rose-500">{{ $collection['tag'] }}</span>
              <h3 class="text-lg font-semibold text-rose-700">{{ $collection['title'] }}</h3>
              <p class="text-sm text-slate-600">{{ $collection['description'] }}</p>
            </div>
          </article>
        @endforeach
      </div>
    </section>

    <section class="space-y-6 rounded-[3rem] border border-rose-100 bg-white/90 p-8 shadow-inner shadow-rose-100/40 sm:p-12">
      <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
        <div class="max-w-2xl space-y-3">
          <h2 class="text-2xl font-semibold text-rose-700">PolyCrochet Premium</h2>
          <p class="text-sm text-slate-600">Recibe asesoría personalizada, seguimiento de pedidos en tiempo real y acceso anticipado a colecciones limitadas.</p>
        </div>
        <a href="{{ route('account') }}" class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-6 py-3 text-sm font-semibold text-rose-500 transition hover:border-rose-300 hover:bg-rose-50">Crear cuenta gratuita</a>
      </div>
    </section>
  </section>
@endsection

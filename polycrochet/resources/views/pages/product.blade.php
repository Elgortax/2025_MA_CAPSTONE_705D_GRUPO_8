@extends('layouts.app')
@section('title', 'Detalle de producto | PolyCrochet')

@section('content')
  <article class="grid gap-10 lg:grid-cols-2 lg:items-start">
    <div class="space-y-6">
      <div class="aspect-square overflow-hidden rounded-3xl border border-gray-200 bg-white shadow">
        <img src="{{ Vite::asset('resources/images/ramos/ramo_gato/ramo_azul_gatito.jpg') }}" alt="Ramo animalitos con gatito azul" class="h-full w-full object-cover" />
      </div>
      <div class="grid grid-cols-4 gap-3">
        @foreach ([
          'resources/images/ramos/ramo_gato/ramo_rosa_gato.jpg',
          'resources/images/ramos/ramo_gato/ramo_rosa_blanco.jpg',
          'resources/images/ramos/ramo_gato/ramo_azul_blanco.jpg',
          'resources/images/ramos/ramo_gato/ramo_rosa_gatito.jpg'
        ] as $thumb)
          <div class="aspect-square overflow-hidden rounded-xl border border-gray-200">
            <img src="{{ Vite::asset($thumb) }}" alt="Variación del ramo de gatito" class="h-full w-full object-cover" />
          </div>
        @endforeach
      </div>
    </div>

    <div class="space-y-6">
      <div>
        <p class="text-sm uppercase tracking-wide text-blue-600">Colección Animalitos</p>
        <h1 class="text-3xl font-bold">Ramo crochet Gatito & Flores pastel</h1>
      </div>
      <p class="text-gray-600">Un bouquet tejido completamente a mano que incluye un gatito protagonista y cinco flores suaves. Personaliza la paleta de colores, añade un mensaje bordado en la bufanda o integra un nuevo personaje. Ideal para cumpleaños, aniversarios o decoraciones tiernas.</p>
      <div class="flex items-center gap-4">
        <span class="text-3xl font-semibold text-gray-900">$39.990</span>
        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Hecho a pedido · 7 días</span>
      </div>
      <form class="space-y-4">
        <div>
          <label class="text-sm font-semibold text-gray-700">Color principal</label>
          <div class="mt-3 grid grid-cols-4 gap-2">
            @foreach ([
              ['label' => 'Pastel rosado', 'value' => 'rosa', 'swatch' => 'bg-rose-300'],
              ['label' => 'Pastel azul', 'value' => 'azul', 'swatch' => 'bg-sky-300'],
              ['label' => 'Blanco crema', 'value' => 'crema', 'swatch' => 'bg-amber-100'],
              ['label' => 'Mix personalizado', 'value' => 'mix', 'swatch' => 'bg-gradient-to-r from-rose-300 via-sky-300 to-amber-200'],
            ] as $option)
              <button type="button" class="flex flex-col items-center gap-2 rounded-xl border border-gray-200 p-3 text-xs font-semibold text-gray-600 hover:border-blue-400 hover:text-blue-600">
                <span class="h-8 w-8 rounded-full {{ $option['swatch'] }}"></span>
                {{ $option['label'] }}
              </button>
            @endforeach
          </div>
        </div>
        <div>
          <label class="text-sm font-semibold text-gray-700" for="size">Tamaño</label>
          <select id="size" class="mt-2 w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <option value="standard">Standard · 25 cm de diámetro</option>
            <option value="grande">Grande · 32 cm de diámetro</option>
            <option value="custom">Personalizado (indica en notas)</option>
          </select>
        </div>
        <div>
          <label class="text-sm font-semibold text-gray-700" for="notes">Notas especiales</label>
          <textarea id="notes" rows="3" class="mt-2 w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Cuenta la historia detrás del regalo, mensaje bordado, personaje extra..."></textarea>
        </div>
        <button type="button" class="w-full rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow hover:bg-blue-500">Añadir al carrito</button>
      </form>
      <section class="space-y-4">
        <h2 class="text-lg font-semibold">Detalles</h2>
        <ul class="list-disc space-y-2 pl-5 text-sm text-gray-600">
          <li>Incluye base rígida para mantener la forma del ramo.</li>
          <li>Tejido con algodón hipoalergénico y relleno reciclado.</li>
          <li>Envío seguro en caja eco-friendly con tarjeta personalizada.</li>
        </ul>
      </section>
    </div>
  </article>
@endsection

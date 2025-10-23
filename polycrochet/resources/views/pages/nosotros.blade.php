@extends('layouts.app')
@section('title', 'Nosotros | PolyCrochet')

@section('content')
  <article class="space-y-16">
    <section class="grid gap-10 rounded-[3rem] border border-rose-100/70 bg-white/80 p-8 shadow-xl shadow-rose-100/50 lg:grid-cols-[1.1fr,0.9fr] lg:items-center">
      <div class="space-y-5">
        <span class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-rose-500">Quiénes somos</span>
        <h1 class="text-3xl font-bold text-rose-700 sm:text-4xl">Tejemos historias, no solo productos</h1>
        <p class="text-sm text-slate-600 sm:text-base">PolyCrochet nace del amor por el tejido y la autoexpresión. Desde nuestro taller en Santiago diseñamos piezas personalizadas que abrazan momentos importantes: regalos, decoración y recuerdos con significado.</p>
        <p class="text-sm text-slate-600 sm:text-base">Seleccionamos fibras responsables, colaboramos con proveedores locales y co-creamos cada pedido junto a la persona que lo sueña. Así convertimos ideas en piezas únicas que transmiten emociones.</p>
      </div>
      <div class="overflow-hidden rounded-[2.5rem] border border-rose-100 shadow-lg">
        <img src="{{ asset('img/imagen 26.png') }}" alt="Equipo PolyCrochet trabajando en nuevas piezas" class="h-full w-full object-cover" />
      </div>
    </section>

    <section class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      @foreach ([
        ['title' => 'Nuestro taller', 'body' => 'Artesanas y diseñadoras textiles que reinventan el crochet tradicional con un estilo moderno y alegre.', 'color' => 'from-rose-100 via-rose-50 to-white', 'text' => 'text-rose-700'],
        ['title' => 'Nuestra visión', 'body' => 'Crear productos de alto impacto emocional, sostenibles y hechos a medida para cada momento especial.', 'color' => 'from-amber-100 via-amber-50 to-white', 'text' => 'text-amber-700'],
        ['title' => 'Compromiso', 'body' => 'Fibras recicladas, empaques reutilizables y una cadena justa que valore el trabajo hecho a mano.', 'color' => 'from-emerald-100 via-emerald-50 to-white', 'text' => 'text-emerald-700'],
      ] as $card)
        <article class="rounded-3xl border border-white/70 bg-gradient-to-br {{ $card['color'] }} p-6 shadow-md">
          <h2 class="text-lg font-semibold {{ $card['text'] }}">{{ $card['title'] }}</h2>
          <p class="mt-3 text-sm text-slate-600">{{ $card['body'] }}</p>
        </article>
      @endforeach
    </section>

    <section class="grid gap-8 lg:grid-cols-[0.9fr,1.1fr] lg:items-center">
      <div class="overflow-hidden rounded-[3rem] border border-rose-100 shadow-lg shadow-rose-100/40">
        <img src="{{ asset('img/imagen 12.png') }}" alt="Detalle de bouquet PolyCrochet" class="h-full w-full object-cover" />
      </div>
      <div id="contacto" class="space-y-5">
        <h2 class="text-2xl font-semibold text-rose-700">Conversemos tu idea</h2>
        <p class="text-sm text-slate-600 sm:text-base">¿Tienes un pedido personalizado o necesitas ayuda? Escríbenos y diseñemos junt@s una pieza inolvidable.</p>

        @if (session('contact.status'))
          <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-6 py-3 text-sm font-semibold text-emerald-700 shadow">
            {{ session('contact.status') }}
          </div>
        @endif

        @if ($errors->any())
          <div class="rounded-3xl border border-rose-200 bg-rose-50 px-6 py-4 text-sm text-rose-700 shadow">
            <p class="font-semibold">Revisa la información ingresada:</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <ul class="space-y-2 text-sm text-slate-600">
          <li><strong>Correo:</strong> <a href="mailto:contacto@polycrochet.cl" class="text-rose-500 hover:text-rose-600">contacto@polycrochet.cl</a></li>
          <li><strong>Teléfono:</strong> <a href="tel:+56959307085" class="text-rose-500 hover:text-rose-600">+56 9 5930 7085</a></li>
          <li><strong>Instagram:</strong> <a href="https://www.instagram.com/polycrochet_1975?igsh=MTRtZThpOWt5bGp4NQ==" class="text-rose-500 hover:text-rose-600" target="_blank" rel="noopener">@polycrochet_1975</a></li>
        </ul>

        <form action="{{ route('contact.store') }}" method="POST" class="grid gap-4 rounded-3xl border border-rose-100 bg-white/90 p-6 shadow-lg shadow-rose-100/40">
          @csrf
          <label class="text-sm font-medium text-rose-600">Nombre
            <input
              type="text"
              name="name"
              value="{{ old('name') }}"
              required
              class="mt-1 w-full rounded-full border border-rose-200 px-4 py-2 text-sm text-slate-700 focus:border-rose-400 focus:outline-none focus:ring-1 focus:ring-rose-300"
              placeholder="Tu nombre" />
            @error('name')
              <span class="mt-1 block text-xs text-rose-500">{{ $message }}</span>
            @enderror
          </label>

          <label class="text-sm font-medium text-rose-600">Correo
            <input
              type="email"
              name="email"
              value="{{ old('email') }}"
              required
              class="mt-1 w-full rounded-full border border-rose-200 px-4 py-2 text-sm text-slate-700 focus:border-rose-400 focus:outline-none focus:ring-1 focus:ring-rose-300"
              placeholder="tu@correo.cl" />
            @error('email')
              <span class="mt-1 block text-xs text-rose-500">{{ $message }}</span>
            @enderror
          </label>

          <label class="text-sm font-medium text-rose-600">Mensaje
            <textarea
              rows="4"
              name="message"
              required
              class="mt-1 w-full rounded-3xl border border-rose-200 px-4 py-3 text-sm text-slate-700 focus:border-rose-400 focus:outline-none focus:ring-1 focus:ring-rose-300"
              placeholder="Cuéntanos qué necesitas">{{ old('message') }}</textarea>
            @error('message')
              <span class="mt-1 block text-xs text-rose-500">{{ $message }}</span>
            @enderror
          </label>

          <button type="submit" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-6 py-3 text-sm font-semibold text-white shadow shadow-rose-200/50 transition hover:from-rose-500 hover:to-amber-400">Enviar mensaje</button>
        </form>
      </div>
    </section>

    <section id="faq" class="space-y-6">
      <h2 class="text-2xl font-semibold text-rose-700">Preguntas frecuentes</h2>
      <div class="space-y-4">
        @foreach ([
          ['q' => '¿Cuánto tarda mi pedido?', 'a' => 'La confección toma entre 5 y 10 días hábiles según la complejidad. Te avisamos en cada etapa para que sepas cuándo llegará.'],
          ['q' => '¿Puedo personalizar colores y tamaños?', 'a' => 'Sí, cada producto ofrece opciones de personalización. Escríbenos si buscas algo todavía más especial.'],
          ['q' => '¿Qué métodos de pago aceptan?', 'a' => 'Aceptamos PayPal y transferencias bancarias. Pronto sumaremos WebPay y otros medios locales.'],
        ] as $faq)
          <details class="overflow-hidden rounded-3xl border border-rose-100 bg-white/90 px-5 py-4 shadow">
            <summary class="cursor-pointer text-sm font-semibold text-rose-700 marker:text-rose-400">{{ $faq['q'] }}</summary>
            <p class="mt-3 text-sm text-slate-600">{{ $faq['a'] }}</p>
          </details>
        @endforeach
      </div>
    </section>

    <section id="politicas" class="space-y-6">
      <h2 class="text-2xl font-semibold text-rose-700">Políticas</h2>
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ([
          ['title' => 'Política de cambios', 'body' => 'Puedes solicitar ajustes hasta 7 días después de recibir tu pedido. Revisamos cada caso de manera personalizada.'],
          ['title' => 'Política de privacidad', 'body' => 'Protegemos tus datos siguiendo la normativa vigente y los usamos solo para gestionar pedidos y comunicaciones.'],
          ['title' => 'Términos y condiciones', 'body' => 'Al comprar aceptas nuestros tiempos de confección, condiciones de personalización, envíos y devoluciones.'],
        ] as $policy)
          <article class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow">
            <h3 class="text-lg font-semibold text-rose-600">{{ $policy['title'] }}</h3>
            <p class="mt-3 text-sm text-slate-600">{{ $policy['body'] }}</p>
          </article>
        @endforeach
      </div>
    </section>
  </article>
@endsection

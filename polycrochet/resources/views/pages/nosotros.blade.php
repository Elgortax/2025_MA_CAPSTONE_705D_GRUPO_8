@extends('layouts.app')
@section('title', 'Nosotros | PolyCrochet')

@section('content')
  <article class="space-y-14">
    <section class="grid gap-8 lg:grid-cols-[1.1fr,0.9fr] lg:items-center">
      <div class="space-y-4">
        <p class="text-sm uppercase tracking-wide text-blue-600">Qui�nes somos</p>
        <h1 class="text-3xl font-bold">Tejemos historias, no solo productos</h1>
        <p class="text-gray-600">PolyCrochet nace del amor por el tejido y la autoexpresi�n. Desde nuestro taller en Santiago dise�amos piezas personalizadas que abrazan momentos importantes: regalos, decoraci�n y recuerdos con significado.</p>
        <p class="text-gray-600">Trabajamos con materiales reciclados, seleccionamos proveedores locales y planificamos cada pedido junto a la persona que lo sue�a. As� convertimos cada idea en una pieza �nica.</p>
      </div>
      <div class="overflow-hidden rounded-[3rem] border border-gray-200 shadow-sm">
        <img src="{{ Vite::asset('resources/images/munecas/munecas2.jpg') }}" alt="Equipo PolyCrochet y mu�ecas tejidas" class="h-full w-full object-cover" />
      </div>
    </section>

    <section class="grid gap-6 rounded-3xl bg-white p-6 shadow-sm lg:grid-cols-3">
      <div class="rounded-2xl bg-blue-50 p-6">
        <h2 class="text-xl font-semibold text-blue-700">Nosotros</h2>
        <p class="mt-3 text-sm text-blue-900">Artesanas y dise�adores textiles que reinventan el crochet tradicional con un estilo moderno y alegre.</p>
      </div>
      <div class="rounded-2xl bg-amber-50 p-6">
        <h2 class="text-xl font-semibold text-amber-700">Nuestra visi�n</h2>
        <p class="mt-3 text-sm text-amber-900">Crear productos de alto impacto emocional, sostenibles y hechos a medida para cada persona o regalo especial.</p>
      </div>
      <div class="rounded-2xl bg-emerald-50 p-6">
        <h2 class="text-xl font-semibold text-emerald-700">Compromiso</h2>
        <p class="mt-3 text-sm text-emerald-900">Usar fibras recicladas, envases reutilizables y una cadena justa que valore el trabajo hecho a mano.</p>
      </div>
    </section>

    <section class="grid gap-8 lg:grid-cols-[0.9fr,1.1fr] lg:items-center">
      <div class="overflow-hidden rounded-3xl border border-gray-200 shadow-sm">
        <img src="{{ Vite::asset('resources/images/ramos/ramo_pollo/girasol_pollo/ramo_pollito.jpg') }}" alt="Ramo crochet con pollito" class="h-full w-full object-cover" />
      </div>
      <div id="contacto" class="space-y-4">
        <h2 class="text-2xl font-semibold">Contacto</h2>
        <p class="text-gray-600">�Tienes una idea personalizada o necesitas ayuda con tu pedido? Escr�benos y conversemos. Nos encanta co-crear piezas �nicas.</p>
        <ul class="space-y-2 text-sm text-gray-600">
          <li><strong>Correo:</strong> hola@polycrochet.cl</li>
          <li><strong>Tel�fono:</strong> +56 9 1234 5678</li>
          <li><strong>Instagram:</strong> @polycrochet</li>
        </ul>
        <form class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
          <div class="grid gap-4">
            <label class="text-sm font-medium text-gray-700">Nombre
              <input type="text" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Tu nombre" />
            </label>
            <label class="text-sm font-medium text-gray-700">Correo
              <input type="email" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="tu@correo.cl" />
            </label>
            <label class="text-sm font-medium text-gray-700">Mensaje
              <textarea rows="4" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Cu�ntanos qu� necesitas"></textarea>
            </label>
            <button type="button" class="rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-500">Enviar mensaje</button>
          </div>
        </form>
      </div>
    </section>

    <section id="faq" class="space-y-6">
      <h2 class="text-2xl font-semibold">Preguntas frecuentes</h2>
      <div class="space-y-4">
        <details class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
          <summary class="cursor-pointer text-sm font-semibold text-gray-900">�Cu�nto tarda mi pedido?</summary>
          <p class="mt-2 text-sm text-gray-600">La confecci�n toma entre 5 y 10 d�as h�biles seg�n la complejidad. Te avisamos en cada etapa para que sepas cu�ndo llegar�.</p>
        </details>
        <details class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
          <summary class="cursor-pointer text-sm font-semibold text-gray-900">�Puedo personalizar colores y tama�os?</summary>
          <p class="mt-2 text-sm text-gray-600">S�, cada producto ofrece opciones de personalizaci�n. Escr�benos si buscas algo todav�a m�s especial o quieres combinar colecciones.</p>
        </details>
        <details class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
          <summary class="cursor-pointer text-sm font-semibold text-gray-900">�Qu� m�todos de pago aceptan?</summary>
          <p class="mt-2 text-sm text-gray-600">Aceptamos PayPal y transferencias bancarias. Pronto sumaremos WebPay y otros medios locales.</p>
        </details>
      </div>
    </section>

    <section id="politicas" class="space-y-6">
      <h2 class="text-2xl font-semibold">Pol�ticas</h2>
      <div class="grid gap-6 lg:grid-cols-3">
        <article class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
          <h3 class="text-lg font-semibold">Pol�tica de cambios</h3>
          <p class="mt-2 text-sm text-gray-600">Puedes solicitar ajustes o cambios hasta 7 d�as despu�s de recibir tu pedido. Revisamos cada caso de manera personalizada.</p>
        </article>
        <article class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
          <h3 class="text-lg font-semibold">Pol�tica de privacidad</h3>
          <p class="mt-2 text-sm text-gray-600">Protegemos tus datos siguiendo las normativas vigentes y solo los usamos para procesar pedidos y comunicaciones autorizadas.</p>
        </article>
        <article class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
          <h3 class="text-lg font-semibold">T�rminos y condiciones</h3>
          <p class="mt-2 text-sm text-gray-600">Al comprar aceptas nuestros tiempos de confecci�n, condiciones de personalizaci�n, env�os y devoluciones.</p>
        </article>
      </div>
    </section>
  </article>
@endsection

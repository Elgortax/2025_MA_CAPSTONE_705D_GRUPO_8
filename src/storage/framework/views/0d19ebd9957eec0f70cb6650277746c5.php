<?php $__env->startSection('title', 'Nosotros | PolyCrochet'); ?>

<?php $__env->startSection('content'); ?>
  <article class="space-y-12">
    <section class="space-y-4">
      <p class="text-sm uppercase tracking-wide text-blue-600">Quiénes somos</p>
      <h1 class="text-3xl font-bold">Tejemos historias, no solo productos</h1>
      <p class="text-gray-600">PolyCrochet nace del amor por el tejido y la autoexpresión. Creamos piezas personalizadas para acompañarte en tu día a día, cada una confeccionada a mano desde nuestro taller en Santiago.</p>
    </section>

    <section class="grid gap-6 rounded-3xl bg-white p-6 shadow-sm lg:grid-cols-3">
      <div class="rounded-2xl bg-blue-50 p-6">
        <h2 class="text-xl font-semibold text-blue-700">Nosotros</h2>
        <p class="mt-3 text-sm text-blue-900">Un equipo liderado por artesanas expertas y diseñadores textiles que reinventan el crochet tradicional con un toque moderno.</p>
      </div>
      <div class="rounded-2xl bg-amber-50 p-6">
        <h2 class="text-xl font-semibold text-amber-700">Nuestra visión</h2>
        <p class="mt-3 text-sm text-amber-900">Crear productos de alto impacto emocional, sostenibles y hechos a medida para cada persona o regalo especial.</p>
      </div>
      <div class="rounded-2xl bg-emerald-50 p-6">
        <h2 class="text-xl font-semibold text-emerald-700">Compromiso</h2>
        <p class="mt-3 text-sm text-emerald-900">Materiales reciclados y proveedores locales, minimizando residuos y celebrando el trabajo justo.</p>
      </div>
    </section>

    <section id="contacto" class="grid gap-8 lg:grid-cols-2">
      <div class="space-y-4">
        <h2 class="text-2xl font-semibold">Contacto</h2>
        <p class="text-gray-600">¿Tienes una idea personalizada o necesitas ayuda con tu pedido? Escríbenos y conversemos.</p>
        <ul class="space-y-2 text-sm text-gray-600">
          <li><strong>Correo:</strong> hola@polycrochet.cl</li>
          <li><strong>Teléfono:</strong> +56 9 1234 5678</li>
          <li><strong>Instagram:</strong> @polycrochet</li>
        </ul>
      </div>
      <form class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="grid gap-4">
          <label class="text-sm font-medium text-gray-700">Nombre
            <input type="text" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Tu nombre" />
          </label>
          <label class="text-sm font-medium text-gray-700">Correo
            <input type="email" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="tu@correo.cl" />
          </label>
          <label class="text-sm font-medium text-gray-700">Mensaje
            <textarea rows="4" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Cuéntanos qué necesitas"></textarea>
          </label>
          <button type="button" class="rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-500">Enviar mensaje</button>
        </div>
      </form>
    </section>

    <section id="faq" class="space-y-6">
      <h2 class="text-2xl font-semibold">Preguntas frecuentes</h2>
      <div class="space-y-4">
        <details class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
          <summary class="cursor-pointer text-sm font-semibold text-gray-900">¿Cuánto tarda mi pedido?</summary>
          <p class="mt-2 text-sm text-gray-600">La confección toma entre 5 y 10 días hábiles según la complejidad. Te avisamos en cada etapa.</p>
        </details>
        <details class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
          <summary class="cursor-pointer text-sm font-semibold text-gray-900">¿Puedo personalizar colores y tamaños?</summary>
          <p class="mt-2 text-sm text-gray-600">Sí, cada producto ofrece opciones de personalización. Escríbenos si buscas algo todavía más especial.</p>
        </details>
        <details class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
          <summary class="cursor-pointer text-sm font-semibold text-gray-900">¿Qué métodos de pago aceptan?</summary>
          <p class="mt-2 text-sm text-gray-600">Aceptamos PayPal y transferencias bancarias. Pronto sumaremos WebPay.</p>
        </details>
      </div>
    </section>

    <section id="politicas" class="space-y-6">
      <h2 class="text-2xl font-semibold">Políticas</h2>
      <div class="grid gap-6 lg:grid-cols-3">
        <article class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
          <h3 class="text-lg font-semibold">Política de cambios</h3>
          <p class="mt-2 text-sm text-gray-600">Puedes solicitar ajustes o cambios hasta 7 días después de recibir tu pedido. Evaluamos cada caso con dedicación.</p>
        </article>
        <article class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
          <h3 class="text-lg font-semibold">Política de privacidad</h3>
          <p class="mt-2 text-sm text-gray-600">Protegemos tus datos siguiendo las normativas vigentes. Sólo usamos tu información para procesar pedidos y comunicaciones autorizadas.</p>
        </article>
        <article class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
          <h3 class="text-lg font-semibold">Términos y condiciones</h3>
          <p class="mt-2 text-sm text-gray-600">Al comprar aceptas nuestros tiempos de confección, políticas de personalización, envíos y devoluciones.</p>
        </article>
      </div>
    </section>
  </article>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Alexis\Desktop\polycrochet\resources\views/pages/nosotros.blade.php ENDPATH**/ ?>
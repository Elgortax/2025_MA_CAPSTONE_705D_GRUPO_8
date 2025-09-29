<footer class="border-t bg-gray-900 text-gray-300">
  <div class="mx-auto grid gap-8 px-4 py-12 sm:px-6 lg:grid-cols-4 lg:px-8">
    <div>
      <h3 class="text-lg font-semibold text-white">PolyCrochet</h3>
      <p class="mt-3 text-sm text-gray-400">Piezas tejidas a mano con cariño desde Chile. Inspírate, personaliza y recibe tus favoritos en casa.</p>
    </div>
    <div>
      <h4 class="text-sm font-semibold uppercase tracking-wide text-gray-200">Navegación</h4>
      <ul class="mt-3 space-y-2 text-sm">
        <li><a href="{{ route('catalog') }}" class="hover:text-white">Catálogo</a></li>
        <li><a href="{{ route('nosotros') }}" class="hover:text-white">Nosotros</a></li>
        <li><a href="{{ route('account') }}" class="hover:text-white">Cuenta</a></li>
        <li><a href="{{ route('cart') }}" class="hover:text-white">Carrito</a></li>
      </ul>
    </div>
    <div>
      <h4 class="text-sm font-semibold uppercase tracking-wide text-gray-200">Soporte</h4>
      <ul class="mt-3 space-y-2 text-sm">
        <li><a href="{{ route('nosotros') }}#faq" class="hover:text-white">Preguntas frecuentes</a></li>
        <li><a href="{{ route('nosotros') }}#politicas" class="hover:text-white">Políticas</a></li>
        <li><a href="mailto:hola@polycrochet.cl" class="hover:text-white">hola@polycrochet.cl</a></li>
        <li><a href="tel:+56912345678" class="hover:text-white">+56 9 1234 5678</a></li>
      </ul>
    </div>
    <div>
      <h4 class="text-sm font-semibold uppercase tracking-wide text-gray-200">Síguenos</h4>
      <ul class="mt-3 space-y-2 text-sm">
        <li><a href="#" class="hover:text-white">Instagram</a></li>
        <li><a href="#" class="hover:text-white">TikTok</a></li>
        <li><a href="#" class="hover:text-white">Pinterest</a></li>
      </ul>
    </div>
  </div>
  <div class="border-t border-gray-800">
    <div class="mx-auto flex flex-col items-center justify-between gap-2 px-4 py-4 text-xs text-gray-500 sm:flex-row sm:px-6 lg:px-8">
      <span>&copy; {{ date('Y') }} PolyCrochet. Todos los derechos reservados.</span>
      <a href="{{ route('admin.dashboard') }}" class="hover:text-white">Acceso administrador</a>
    </div>
  </div>
</footer>

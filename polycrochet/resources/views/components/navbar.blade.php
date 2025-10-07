<header class="border-b bg-white">
  <div class="mx-auto flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
    <a href="{{ route('home') }}" class="flex items-center gap-3 text-lg font-semibold text-gray-900">
      <img src="{{ Vite::asset('resources/images/logo/logo.png') }}" alt="PolyCrochet" class="h-10 w-auto" />
      <span class="hidden sm:inline">PolyCrochet</span>
    </a>
    <nav class="hidden items-center gap-6 text-sm font-medium text-gray-600 md:flex">
      <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-blue-600' : 'hover:text-blue-600' }}">Inicio</a>
      <a href="{{ route('catalog') }}" class="{{ request()->routeIs('catalog') ? 'text-blue-600' : 'hover:text-blue-600' }}">Catálogo</a>
      <a href="{{ route('nosotros') }}" class="{{ request()->routeIs('nosotros') ? 'text-blue-600' : 'hover:text-blue-600' }}">Nosotros</a>
      <a href="{{ route('account') }}" class="{{ request()->routeIs('account') ? 'text-blue-600' : 'hover:text-blue-600' }}">Cuenta</a>
      <a href="{{ route('cart') }}" class="{{ request()->routeIs('cart') ? 'text-blue-600' : 'hover:text-blue-600' }}">Carrito</a>
    </nav>
    <button type="button" class="inline-flex items-center rounded-md border border-gray-300 px-3 py-1.5 text-sm font-medium text-gray-600 hover:border-blue-400 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 md:hidden">
      Menú
    </button>
  </div>
</header>

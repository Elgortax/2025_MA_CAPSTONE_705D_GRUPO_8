<header class="fixed inset-x-0 top-0 z-50">
  <div class="mx-auto w-full max-w-7xl px-4 pt-6 sm:px-6 lg:px-10">
    <div class="flex items-center justify-between rounded-full border border-rose-100/70 bg-white/90 px-4 py-3 shadow-lg shadow-rose-200/50 backdrop-blur">
      <a href="{{ route('home') }}" class="flex items-center gap-3 text-lg font-semibold text-rose-700">
        <img src="{{ asset('img/logo.png') }}" alt="PolyCrochet" class="h-10 w-10 rounded-full border border-rose-100 bg-white object-cover p-1 shadow-inner shadow-rose-100" loading="lazy">
        <span class="hidden text-base font-semibold tracking-tight sm:inline">PolyCrochet</span>
      </a>

      <nav class="hidden items-center gap-8 text-sm font-medium uppercase tracking-[0.18em] text-slate-500 lg:flex">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-rose-600' : 'hover:text-rose-500' }}">Inicio</a>
        <a href="{{ route('catalog') }}" class="{{ request()->routeIs('catalog') ? 'text-rose-600' : 'hover:text-rose-500' }}">Catálogo</a>
        <a href="{{ route('nosotros') }}" class="{{ request()->routeIs('nosotros') ? 'text-rose-600' : 'hover:text-rose-500' }}">Nosotros</a>

        @auth
          <a href="{{ route('account') }}" class="{{ request()->routeIs('account') ? 'text-rose-600' : 'hover:text-rose-500' }}">Mi cuenta</a>
          @if (! auth()->user()->isAdmin())
            <a href="{{ route('orders.history') }}" class="{{ request()->routeIs('orders.history') || request()->routeIs('orders.history.show') ? 'text-rose-600' : 'hover:text-rose-500' }}">Mis pedidos</a>
          @endif
          @if (auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'text-rose-600' : 'hover:text-rose-500' }}">Administración</a>
          @endif
        @else
          <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'text-rose-600' : 'hover:text-rose-500' }}">Ingresar</a>
          <a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'text-rose-600' : 'hover:text-rose-500' }}">Crear cuenta</a>
        @endauth

        <a href="{{ route('cart') }}" class="{{ request()->routeIs('cart') ? 'text-rose-600' : 'hover:text-rose-500' }}">Carrito</a>
      </nav>

      <button type="button" class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] text-rose-500 transition hover:border-rose-300 hover:text-rose-600 lg:hidden">
        Menú
      </button>
    </div>
  </div>
</header>

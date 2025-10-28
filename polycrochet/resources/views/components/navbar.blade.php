<header class="fixed inset-x-0 top-0 z-50">
  <div class="mx-auto w-full max-w-7xl px-4 pt-6 sm:px-6 lg:px-10">
    <div class="relative flex items-center justify-between rounded-full border border-rose-100/70 bg-white/90 px-4 py-3 shadow-lg shadow-rose-200/50 backdrop-blur">
      <a href="{{ route('home') }}" class="flex items-center gap-3 text-lg font-semibold text-rose-700">
        <img src="{{ asset('img/logo.png') }}" alt="PolyCrochet" class="h-10 w-10 rounded-full border border-rose-100 bg-white object-cover p-1 shadow-inner shadow-rose-100" loading="lazy">
        <span class="text-base font-semibold tracking-tight">PolyCrochet</span>
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

      <button
        type="button"
        class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] text-rose-500 transition hover:border-rose-300 hover:text-rose-600 lg:hidden"
        data-mobile-menu-toggle
        aria-expanded="false"
        aria-controls="mobile-primary-menu"
      >
        Menú
      </button>

      <div
        id="mobile-primary-menu"
        data-mobile-menu
        class="absolute left-0 right-0 top-[calc(100%+0.75rem)] hidden rounded-3xl border border-rose-100 bg-white/95 p-4 shadow-lg shadow-rose-200/40 lg:hidden"
      >
        <nav class="flex flex-col gap-3 text-sm font-semibold uppercase tracking-[0.18em] text-slate-700">
          <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-rose-600' : 'hover:text-rose-500' }}" data-mobile-menu-close>INICIO</a>
          <a href="{{ route('catalog') }}" class="{{ request()->routeIs('catalog') ? 'text-rose-600' : 'hover:text-rose-500' }}" data-mobile-menu-close>CATÁLOGO</a>
          <a href="{{ route('nosotros') }}" class="{{ request()->routeIs('nosotros') ? 'text-rose-600' : 'hover:text-rose-500' }}" data-mobile-menu-close>NOSOTROS</a>

          @auth
            <a href="{{ route('account') }}" class="{{ request()->routeIs('account') ? 'text-rose-600' : 'hover:text-rose-500' }}" data-mobile-menu-close>MI CUENTA</a>
            @if (! auth()->user()->isAdmin())
              <a href="{{ route('orders.history') }}" class="{{ request()->routeIs('orders.history') || request()->routeIs('orders.history.show') ? 'text-rose-600' : 'hover:text-rose-500' }}" data-mobile-menu-close>MIS PEDIDOS</a>
            @endif
            @if (auth()->user()->isAdmin())
              <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'text-rose-600' : 'hover:text-rose-500' }}" data-mobile-menu-close>ADMINISTRACIÓN</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="pt-1">
              @csrf
              <button type="submit" class="text-left text-slate-700 hover:text-rose-500" data-mobile-menu-close>CERRAR SESIÓN</button>
            </form>
          @else
            <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'text-rose-600' : 'hover:text-rose-500' }}" data-mobile-menu-close>INGRESAR</a>
            <a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'text-rose-600' : 'hover:text-rose-500' }}" data-mobile-menu-close>CREAR CUENTA</a>
          @endauth

          <a href="{{ route('cart') }}" class="{{ request()->routeIs('cart') ? 'text-rose-600' : 'hover:text-rose-500' }}" data-mobile-menu-close>CARRITO</a>
        </nav>
      </div>
    </div>
  </div>
</header>

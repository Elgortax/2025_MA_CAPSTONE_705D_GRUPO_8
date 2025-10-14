<header class="fixed inset-x-0 top-0 z-50">
  <div class="mx-auto w-full max-w-7xl px-4 pt-6 sm:px-6 lg:px-10">
    <div class="flex items-center justify-between rounded-full border border-rose-100/70 bg-white/90 px-4 py-3 shadow-lg shadow-rose-200/50 backdrop-blur">
      <a href="{{ route('home') }}" class="flex items-center gap-3 text-lg font-semibold text-rose-700">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-rose-300 via-rose-400 to-amber-300 text-base font-bold text-white shadow-lg shadow-rose-200/60">PC</span>
        <span class="hidden text-base font-semibold tracking-tight sm:inline">PolyCrochet Studio</span>
      </a>

      <nav class="hidden items-center gap-8 text-sm font-medium uppercase tracking-[0.18em] text-slate-500 lg:flex">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-rose-600' : 'hover:text-rose-500' }}">Inicio</a>
        <a href="{{ route('catalog') }}" class="{{ request()->routeIs('catalog') ? 'text-rose-600' : 'hover:text-rose-500' }}">Catálogo</a>
        <a href="{{ route('nosotros') }}" class="{{ request()->routeIs('nosotros') ? 'text-rose-600' : 'hover:text-rose-500' }}">Nosotros</a>
        <a href="{{ route('account') }}" class="{{ request()->routeIs('account') ? 'text-rose-600' : 'hover:text-rose-500' }}">Cuenta</a>
        <a href="{{ route('cart') }}" class="{{ request()->routeIs('cart') ? 'text-rose-600' : 'hover:text-rose-500' }}">Carrito</a>
      </nav>

      <button type="button" class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] text-rose-500 transition hover:border-rose-300 hover:text-rose-600 lg:hidden">
        Menú
      </button>
    </div>
  </div>
</header>

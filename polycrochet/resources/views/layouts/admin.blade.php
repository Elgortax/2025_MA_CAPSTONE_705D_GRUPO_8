<!DOCTYPE html>
<html lang="es" class="h-full antialiased">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Panel | PolyCrochet')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen bg-slate-950 text-slate-100">
  <aside class="hidden w-72 flex-shrink-0 flex-col border-r border-slate-800 bg-slate-950 px-6 py-8 md:flex">
    <a href="{{ route('home') }}" class="text-xl font-bold text-white">PolyCrochet Admin</a>
    <nav class="mt-10 space-y-2 text-sm font-semibold">
      <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">Dashboard</a>
      <a href="{{ route('admin.products.index') }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.products.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">Productos</a>
      <a href="{{ route('admin.orders.index') }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.orders.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">Pedidos</a>
      <a href="{{ route('admin.customers.index') }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.customers.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">Clientes</a>
      <a href="{{ route('admin.settings.edit') }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.settings.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">Configuración</a>
    </nav>
  </aside>

  <div class="flex-1">
    <header class="relative flex items-center justify-between border-b border-slate-800 bg-slate-900/60 px-4 py-4 sm:px-6">
      <div class="flex items-center gap-3">
        <button
          type="button"
          class="inline-flex items-center rounded-lg border border-slate-700 px-3 py-1.5 text-sm text-slate-300 hover:border-blue-500 hover:text-blue-400 md:hidden"
          data-mobile-menu-toggle
          aria-expanded="false"
          aria-controls="admin-mobile-menu"
        >
          Menú
        </button>
        <h1 class="text-lg font-semibold text-white">@yield('page_heading', 'Resumen')</h1>
      </div>
      <a href="{{ route('home') }}" class="rounded-full border border-slate-700 px-4 py-2 text-xs font-semibold text-slate-300 hover:border-blue-500 hover:text-blue-400">Ver tienda</a>

      <div
        id="admin-mobile-menu"
        data-mobile-menu
        class="absolute left-0 right-0 top-full z-50 hidden border-b border-slate-800 bg-slate-950 px-4 py-4 shadow-lg md:hidden"
      >
        <nav class="space-y-2 text-sm font-semibold">
          <a href="{{ route('admin.dashboard') }}" data-mobile-menu-close class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">Dashboard</a>
          <a href="{{ route('admin.products.index') }}" data-mobile-menu-close class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.products.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">Productos</a>
          <a href="{{ route('admin.orders.index') }}" data-mobile-menu-close class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.orders.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">Pedidos</a>
          <a href="{{ route('admin.customers.index') }}" data-mobile-menu-close class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.customers.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">Clientes</a>
          <a href="{{ route('admin.settings.edit') }}" data-mobile-menu-close class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.settings.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">Configuración</a>
        </nav>
      </div>
    </header>

    <main class="px-4 py-8 sm:px-6 lg:px-8">
      @yield('content')
    </main>
  </div>
</body>
</html>

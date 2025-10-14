<!DOCTYPE html>
<html lang="es" class="h-full antialiased">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'PolyCrochet')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-[#f9f5f1] via-[#fdf9f5] to-[#f3f1ff] text-slate-900 antialiased selection:bg-rose-200 selection:text-rose-900">
  <div class="relative flex min-h-screen flex-col">
    <div class="pointer-events-none absolute inset-0 -z-10">
      <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,182,193,0.18),transparent_55%)]"></div>
      <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom,_rgba(178,223,219,0.14),transparent_55%)]"></div>
    </div>

    @include('components.navbar')

    <main class="relative mx-auto w-full max-w-7xl flex-1 px-4 pb-20 pt-28 sm:px-6 lg:px-10">
      @yield('content')
    </main>

    @include('components.footer')
    @include('components.cart-panel')
  </div>
</body>
</html>

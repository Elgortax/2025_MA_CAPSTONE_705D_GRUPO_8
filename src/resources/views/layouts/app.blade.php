<!DOCTYPE html>
<html lang="es" class="h-full antialiased">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'PolyCrochet')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-gray-50 text-gray-900">
  @include('components.navbar')

  <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-8 sm:px-6 lg:px-8">
    @yield('content')
  </main>

  @include('components.footer')
</body>
</html>

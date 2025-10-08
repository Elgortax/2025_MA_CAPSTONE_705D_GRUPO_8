@extends('layouts.app')
@section('title', 'Error de pago | PolyCrochet')

@section('content')
  <section class="mx-auto max-w-2xl space-y-6 text-center">
    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-500">
      ×
    </div>
    <h1 class="text-3xl font-bold">No pudimos procesar tu pago</h1>
    <p class="text-gray-600">Ocurrió un problema al comunicarnos con PayPal. Revisa tu conexión o intenta nuevamente más tarde.</p>
    <div class="flex justify-center gap-4">
      <a href="{{ route('checkout') }}" class="rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-500">Reintentar</a>
      <a href="mailto:hola@polycrochet.cl" class="rounded-full border border-gray-300 px-6 py-3 text-sm font-semibold text-gray-700 hover:border-blue-400 hover:text-blue-600">Contactar soporte</a>
    </div>
  </section>
@endsection

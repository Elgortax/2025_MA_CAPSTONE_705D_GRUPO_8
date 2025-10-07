@extends('layouts.app')
@section('title', 'Pago cancelado | PolyCrochet')

@section('content')
  <section class="mx-auto max-w-2xl space-y-6 text-center">
    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-amber-100 text-amber-500">
      !
    </div>
    <h1 class="text-3xl font-bold">Pago cancelado</h1>
    <p class="text-gray-600">El proceso de pago se canceló. Si fue un error, puedes intentar nuevamente o elegir otro método.</p>
    <div class="flex justify-center gap-4">
      <a href="{{ route('checkout') }}" class="rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-500">Intentar de nuevo</a>
      <a href="{{ route('catalog') }}" class="rounded-full border border-gray-300 px-6 py-3 text-sm font-semibold text-gray-700 hover:border-blue-400 hover:text-blue-600">Seguir comprando</a>
    </div>
  </section>
@endsection

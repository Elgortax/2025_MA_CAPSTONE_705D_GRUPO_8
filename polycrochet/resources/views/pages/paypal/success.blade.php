@extends('layouts.app')
@section('title', 'Pago exitoso | PolyCrochet')

@section('content')
  <section class="mx-auto max-w-2xl space-y-6 text-center">
    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
      ?
    </div>
    <h1 class="text-3xl font-bold">Pago confirmado!</h1>
    <p class="text-gray-600">Hemos recibido el pago de tu pedido. En pocos minutos te enviaremos un correo con toda la información de seguimiento.</p>
    <div class="flex justify-center gap-4">
      <a href="{{ route('order.confirmation') }}" class="rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-500">Ir a la confirmación</a>
      <a href="{{ route('catalog') }}" class="rounded-full border border-gray-300 px-6 py-3 text-sm font-semibold text-gray-700 hover:border-blue-400 hover:text-blue-600">Volver al cat�logo</a>
    </div>
  </section>
@endsection

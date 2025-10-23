@extends('layouts.app')
@section('title', 'Correo verificado | PolyCrochet')

@php
  $status = session('verification.status');
  $isAlreadyVerified = $status === 'already';
@endphp

@section('content')
  <section class="mx-auto max-w-lg space-y-6 rounded-[3rem] border border-emerald-100 bg-white/95 p-10 text-center shadow-2xl shadow-emerald-100/60">
    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500 text-white shadow-lg shadow-emerald-200/80">
      <svg class="h-9 w-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
      </svg>
    </div>

    <div class="space-y-3">
      <h1 class="text-2xl font-semibold text-emerald-700">
        {{ $isAlreadyVerified ? 'Tu correo ya estaba verificado' : '¡Correo verificado con éxito!' }}
      </h1>
      <p class="text-sm text-slate-600">
        {{ $isAlreadyVerified
            ? 'Gracias por confirmar tu dirección. Ya puedes continuar disfrutando de PolyCrochet.'
            : 'Gracias por verificar tu dirección de correo. Ya puedes continuar disfrutando de PolyCrochet.' }}
      </p>
    </div>

    <div class="flex flex-col items-center justify-center gap-3 sm:flex-row">
      <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-6 py-3 text-sm font-semibold text-white shadow shadow-rose-200/60 transition hover:from-rose-500 hover:to-amber-400">
        Ir al inicio
      </a>
      <a href="{{ route('account') }}" class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-6 py-3 text-sm font-semibold text-rose-500 transition hover:border-rose-300 hover:bg-rose-50">
        Ir a mi cuenta
      </a>
    </div>
  </section>
@endsection

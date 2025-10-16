@extends('layouts.app')
@section('title', 'Verifica tu correo | PolyCrochet')

@section('content')
  <section class="mx-auto max-w-lg rounded-[2.5rem] border border-rose-100 bg-white/95 p-10 text-center shadow-2xl shadow-rose-100/50">
    <h1 class="text-2xl font-semibold text-rose-700">Confirma tu correo electrónico</h1>
    <p class="mt-3 text-sm text-slate-500">
      Te enviamos un enlace a <strong>{{ auth()->user()->email }}</strong>. Haz clic en el botón de verificación dentro de tu bandeja de entrada para activar tu cuenta.
    </p>

    @if (session('status') === 'verification-link-sent')
      <div class="mt-4 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm text-emerald-600 shadow-sm shadow-emerald-100/60">
        Enlace de verificación reenviado. Revisa tu correo nuevamente.
      </div>
    @endif

    <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
      <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-6 py-3 text-sm font-semibold text-white shadow shadow-rose-200/60 transition hover:from-rose-500 hover:to-amber-400">
          Reenviar enlace
        </button>
      </form>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="inline-flex items-center justify-center rounded-full border border-rose-200 px-6 py-3 text-sm font-semibold text-rose-500 transition hover:border-rose-300 hover:bg-rose-50">
          Cerrar sesión
        </button>
      </form>
    </div>
  </section>
@endsection

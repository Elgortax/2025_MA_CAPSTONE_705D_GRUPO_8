@extends('layouts.app')
@section('title', 'Restablecer contraseña | PolyCrochet')

@section('content')
  <section class="mx-auto max-w-md rounded-[2.5rem] border border-rose-100 bg-white/95 p-8 shadow-2xl shadow-rose-100/50">
    <h1 class="text-2xl font-semibold text-rose-700">Crea una nueva contraseña</h1>
    <p class="mt-2 text-sm text-slate-500">Ingresa una contraseña segura y confírmala para continuar.</p>

    @if ($errors->any())
      <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-600">
        <ul class="list-disc pl-5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}" class="mt-6 space-y-4">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">

      <div>
        <label for="email" class="text-xs font-semibold uppercase tracking-[0.28em] text-rose-400">Correo electrónico</label>
        <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required class="mt-2 w-full rounded-full border border-rose-200 bg-white/95 px-4 py-2 text-sm text-slate-600 focus:border-rose-400 focus:outline-none focus:ring-1 focus:ring-rose-300" />
      </div>

      <div>
        <label for="password" class="text-xs font-semibold uppercase tracking-[0.28em] text-rose-400">Nueva contraseña</label>
        <input id="password" name="password" type="password" required class="mt-2 w-full rounded-full border border-rose-200 bg-white/95 px-4 py-2 text-sm text-slate-600 focus:border-rose-400 focus:outline-none focus:ring-1 focus:ring-rose-300" autocomplete="new-password" />
      </div>

      <div>
        <label for="password_confirmation" class="text-xs font-semibold uppercase tracking-[0.28em] text-rose-400">Confirmar contraseña</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required class="mt-2 w-full rounded-full border border-rose-200 bg-white/95 px-4 py-2 text-sm text-slate-600 focus:border-rose-400 focus:outline-none focus:ring-1 focus:ring-rose-300" />
      </div>

      <button type="submit" class="w-full rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-6 py-3 text-sm font-semibold text-white shadow shadow-rose-200/60 transition hover:from-rose-500 hover:to-amber-400">
        Guardar nueva contraseña
      </button>
    </form>
  </section>
@endsection

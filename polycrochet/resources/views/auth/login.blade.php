@extends('layouts.app')
@section('title', 'Iniciar sesión | PolyCrochet')

@section('content')
  <section class="mx-auto max-w-md rounded-[2.5rem] border border-rose-100 bg-white/95 p-8 shadow-2xl shadow-rose-100/50">
    <h1 class="text-2xl font-semibold text-rose-700">Inicia sesión</h1>
    <p class="mt-2 text-sm text-slate-500">Accede a tu cuenta para seguir tus pedidos y guardar favoritos.</p>

    @if (session('status'))
      <div class="mt-4 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm text-emerald-600 shadow-sm shadow-emerald-100/60">
        {{ session('status') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-600">
        <ul class="list-disc pl-5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
      @csrf
      <div>
        <label for="email" class="text-xs font-semibold uppercase tracking-[0.28em] text-rose-400">Correo electrónico</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="mt-2 w-full rounded-full border border-rose-200 bg-white/95 px-4 py-2 text-sm text-slate-600 focus:border-rose-400 focus:outline-none focus:ring-1 focus:ring-rose-300" />
      </div>

      <div>
        <label for="password" class="text-xs font-semibold uppercase tracking-[0.28em] text-rose-400">Contraseña</label>
        <div class="relative mt-2">
          <input id="password" name="password" type="password" required class="w-full rounded-full border border-rose-200 bg-white/95 px-4 py-2 pr-12 text-sm text-slate-600 focus:border-rose-400 focus:outline-none focus:ring-1 focus:ring-rose-300" autocomplete="current-password" />
          <button
            type="button"
            class="absolute inset-y-0 right-3 flex items-center justify-center rounded-full bg-white/0 p-2 text-slate-400 transition hover:text-rose-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-400"
            data-password-toggle="#password"
            data-password-label-show="Mostrar contraseña"
            data-password-label-hide="Ocultar contraseña"
          >
            <img src="{{ asset('img/visible.png') }}" alt="" class="h-4 w-4" data-password-icon="show">
            <img src="{{ asset('img/hide.png') }}" alt="" class="hidden h-4 w-4" data-password-icon="hide">
            <span class="sr-only" data-password-live-label>Mostrar contraseña</span>
          </button>
        </div>
      </div>

      <div class="flex items-center justify-between text-sm text-slate-500">
        <label class="inline-flex items-center gap-2">
          <input type="checkbox" name="remember" class="rounded border-rose-200 text-rose-500 focus:ring-rose-400" />
          Recuérdame
        </label>
        <a href="{{ route('password.request') }}" class="font-semibold text-rose-500 hover:text-rose-600">Olvidé mi contraseña</a>
      </div>

      <button type="submit" class="w-full rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-6 py-3 text-sm font-semibold text-white shadow shadow-rose-200/60 transition hover:from-rose-500 hover:to-amber-400">
        Iniciar sesión
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
      ¿Aún no tienes cuenta?
      <a href="{{ route('register') }}" class="font-semibold text-rose-500 hover:text-rose-600">Crear cuenta</a>
    </p>
  </section>
@endsection

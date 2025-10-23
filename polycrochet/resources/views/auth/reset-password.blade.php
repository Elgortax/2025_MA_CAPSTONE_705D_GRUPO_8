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

    <form method="POST" action="{{ route('password.store') }}" class="mt-6 space-y-4" data-password-form>
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">

      <div>
        <label for="email" class="text-xs font-semibold uppercase tracking-[0.28em] text-rose-400">Correo electrónico</label>
        <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required class="mt-2 w-full rounded-full border border-rose-200 bg-white/95 px-4 py-2 text-sm text-slate-600 focus:border-rose-400 focus:outline-none focus:ring-1 focus:ring-rose-300" />
      </div>

      <div>
        <label for="password" class="text-xs font-semibold uppercase tracking-[0.28em] text-rose-400">Nueva contraseña</label>
        <div class="relative mt-2">
          <input
            id="password"
            name="password"
            type="password"
            required
            autocomplete="new-password"
            class="w-full rounded-full border border-rose-200 bg-white/95 px-4 py-2 pr-12 text-sm text-slate-600 focus:border-rose-400 focus:outline-none focus:ring-1 focus:ring-rose-300"
            data-password
          />
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
        <div class="mt-3 rounded-2xl border border-slate-100 bg-slate-50/70 p-4 text-xs text-slate-500" data-password-rules>
          <p class="font-semibold text-slate-600">Tu contraseña debe incluir:</p>
          <ul class="mt-2 space-y-1">
            <li class="flex items-center gap-2 text-slate-500" data-password-rule="length">
              <span aria-hidden="true" data-password-indicator class="flex h-4 w-4 items-center justify-center rounded-full border border-slate-300 text-[10px] font-semibold text-white"></span>
              Entre 8 y 20 caracteres.
            </li>
            <li class="flex items-center gap-2 text-slate-500" data-password-rule="uppercase">
              <span aria-hidden="true" data-password-indicator class="flex h-4 w-4 items-center justify-center rounded-full border border-slate-300 text-[10px] font-semibold text-white"></span>
              Al menos una letra mayúscula.
            </li>
            <li class="flex items-center gap-2 text-slate-500" data-password-rule="lowercase">
              <span aria-hidden="true" data-password-indicator class="flex h-4 w-4 items-center justify-center rounded-full border border-slate-300 text-[10px] font-semibold text-white"></span>
              Al menos una letra minúscula.
            </li>
            <li class="flex items-center gap-2 text-slate-500" data-password-rule="number">
              <span aria-hidden="true" data-password-indicator class="flex h-4 w-4 items-center justify-center rounded-full border border-slate-300 text-[10px] font-semibold text-white"></span>
              Al menos un número.
            </li>
            <li class="flex items-center gap-2 text-slate-500" data-password-rule="symbol">
              <span aria-hidden="true" data-password-indicator class="flex h-4 w-4 items-center justify-center rounded-full border border-slate-300 text-[10px] font-semibold text-white"></span>
              Al menos un símbolo (ej. !, @, #, $).
            </li>
          </ul>
        </div>
        @error('password')
          <p class="mt-2 text-xs text-rose-500" role="alert">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label for="password_confirmation" class="text-xs font-semibold uppercase tracking-[0.28em] text-rose-400">Confirmar contraseña</label>
        <div class="relative mt-2">
          <input
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            required
            autocomplete="new-password"
            class="w-full rounded-full border border-rose-200 bg-white/95 px-4 py-2 pr-12 text-sm text-slate-600 focus:border-rose-400 focus:outline-none focus:ring-1 focus:ring-rose-300"
            data-password-confirmation
          />
          <button
            type="button"
            class="absolute inset-y-0 right-3 flex items-center justify-center rounded-full bg-white/0 p-2 text-slate-400 transition hover:text-rose-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-400"
            data-password-toggle="#password_confirmation"
            data-password-label-show="Mostrar contraseña"
            data-password-label-hide="Ocultar contraseña"
          >
            <img src="{{ asset('img/visible.png') }}" alt="" class="h-4 w-4" data-password-icon="show">
            <img src="{{ asset('img/hide.png') }}" alt="" class="hidden h-4 w-4" data-password-icon="hide">
            <span class="sr-only" data-password-live-label>Mostrar contraseña</span>
          </button>
        </div>
        <p class="mt-2 hidden text-xs text-rose-500" data-password-match-error role="alert">Las contraseñas deben coincidir.</p>
      </div>

      <button type="submit" class="w-full rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-6 py-3 text-sm font-semibold text-white shadow shadow-rose-200/60 transition hover:from-rose-500 hover:to-amber-400">
        Guardar nueva contraseña
      </button>
    </form>
  </section>
@endsection

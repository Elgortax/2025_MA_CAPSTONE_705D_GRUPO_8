@extends('layouts.app')
@section('title', 'Mi cuenta | PolyCrochet')

@section('content')
  @auth
    <section class="grid gap-10 lg:grid-cols-[1.4fr,1fr]">
      <article class="space-y-6 rounded-[2.5rem] border border-rose-100 bg-white/95 p-8 shadow-xl shadow-rose-100/60">
        <header class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h1 class="text-3xl font-bold text-rose-700">Hola, {{ auth()->user()->name }}</h1>
            <p class="text-sm text-slate-500">Desde aquí puedes revisar tu historial, actualizar datos y seguir tus pedidos.</p>
          </div>
          <span class="inline-flex items-center gap-2 rounded-full border {{ auth()->user()->hasVerifiedEmail() ? 'border-emerald-200 bg-emerald-50 text-emerald-600' : 'border-amber-200 bg-amber-50 text-amber-600' }} px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.25em]">
            {{ auth()->user()->hasVerifiedEmail() ? 'Correo verificado' : 'Pendiente de verificación' }}
          </span>
        </header>

        <dl class="grid gap-4 text-sm text-slate-600 sm:grid-cols-2">
          <div class="rounded-2xl border border-rose-100 bg-white/95 p-4 shadow-sm shadow-rose-100/50">
            <dt class="text-xs uppercase tracking-[0.25em] text-rose-400">Correo</dt>
            <dd class="mt-1 font-semibold text-rose-600">{{ auth()->user()->email }}</dd>
            <dt class="mt-3 text-xs uppercase tracking-[0.25em] text-rose-400">Teléfono</dt>
            <dd class="mt-1 font-semibold text-rose-600">
              {{ auth()->user()->phone ?? 'Sin teléfono registrado' }}
            </dd>
          </div>
          <div class="rounded-2xl border border-rose-100 bg-white/95 p-4 shadow-sm shadow-rose-100/50">
            <dt class="text-xs uppercase tracking-[0.25em] text-rose-400">Miembro desde</dt>
            <dd class="mt-1 font-semibold text-rose-600">{{ auth()->user()->created_at->translatedFormat('d \\d\\e F Y') }}</dd>
          </div>
        </dl>

        <div class="flex flex-wrap items-center gap-3">
          @if (! auth()->user()->hasVerifiedEmail())
            <form method="POST" action="{{ route('verification.send') }}">
              @csrf
              <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-5 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-rose-500 transition hover:border-rose-300 hover:bg-rose-50">
                Reenviar verificación
              </button>
            </form>
          @endif

          <a href="{{ route('cart') }}" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-6 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-white shadow shadow-rose-200/60 transition hover:from-rose-500 hover:to-amber-400">
            Ver carrito
          </a>

          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-6 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-rose-500 transition hover:border-rose-300 hover:bg-rose-50">
              Cerrar sesión
            </button>
          </form>
        </div>
      </article>

      <aside class="space-y-5 rounded-[2.5rem] border border-rose-100 bg-white/95 p-6 shadow-lg shadow-rose-100/60">
        <h2 class="text-lg font-semibold text-rose-600">Atajos</h2>
        <div class="grid gap-4 text-sm text-slate-600">
          <a href="{{ route('cart') }}" class="flex items-center justify-between rounded-2xl border border-rose-100 bg-white/95 px-4 py-3 transition hover:border-rose-300 hover:text-rose-600">
            <span>Seguir mis pedidos</span>
            <span class="text-xs uppercase tracking-[0.25em] text-rose-400">Carrito</span>
          </a>
          @if (auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between rounded-2xl border border-rose-100 bg-white/95 px-4 py-3 transition hover:border-rose-300 hover:text-rose-600">
              <span>Ir al panel administrativo</span>
              <span class="text-xs uppercase tracking-[0.25em] text-rose-400">Admin</span>
            </a>
          @endif
        </div>
      </aside>
    </section>
  @else
    <section class="grid gap-10 lg:grid-cols-2">
      <article class="space-y-6 rounded-[2.5rem] border border-rose-100 bg-white/95 p-8 shadow-xl shadow-rose-100/60">
        <div class="flex items-center gap-3">
          <div class="h-14 w-14 overflow-hidden rounded-full border border-rose-100 bg-rose-50">
            <img src="{{ Vite::asset('resources/images/animales/pollito.png') }}" alt="Amigurumi pollito" class="h-full w-full object-cover" />
          </div>
          <div>
            <h1 class="text-2xl font-semibold text-rose-700">Bienvenida/o de vuelta</h1>
            <p class="text-sm text-slate-500">Accede para seguir tus pedidos, guardar favoritos y recibir novedades.</p>
          </div>
        </div>
        <a href="{{ route('login') }}" class="inline-flex w-full items-center justify-center rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-6 py-3 text-sm font-semibold text-white shadow shadow-rose-200/60 transition hover:from-rose-500 hover:to-amber-400">
          Iniciar sesión
        </a>
        <p class="text-xs text-slate-400">¿No recuerdas tu contraseña? Puedes solicitar un enlace desde la pantalla de acceso.</p>
      </article>

      <article class="space-y-6 rounded-[2.5rem] border border-rose-100 bg-white/95 p-8 shadow-xl shadow-rose-100/60">
        <div class="overflow-hidden rounded-[2rem]">
          <img src="{{ Vite::asset('resources/images/munecas/muneca_celeste/muneca_celeste2.jpg') }}" alt="Muñeca crochet tonos celestes" class="h-44 w-full object-cover" />
        </div>
        <div class="space-y-4">
          <h2 class="text-lg font-semibold text-rose-600">¿Primera vez en PolyCrochet?</h2>
          <p class="text-sm text-slate-500">Crea tu cuenta para guardar direcciones, recibir asesoría personalizada y obtener acceso anticipado a lanzamientos.</p>
          <a href="{{ route('register') }}" class="inline-flex w-full items-center justify-center rounded-full border border-rose-200 px-6 py-3 text-sm font-semibold text-rose-500 transition hover:border-rose-300 hover:bg-rose-50">
            Crear cuenta
          </a>
        </div>
      </article>
    </section>
  @endauth
@endsection

@extends('layouts.app')

@section('title', 'Mi cuenta | PolyCrochet')

@section('content')
  @auth
    @php
      $addressRedirect = route('account');
      $showAddressErrors = $errors->any() && old('redirect_to') === $addressRedirect;
      $formContext = old('form_context');
      $statusKey = session('status');
      $statusMessage = session('status_message');
      $openEditorId = $showAddressErrors && $formContext && \Illuminate\Support\Str::startsWith($formContext, 'edit-')
        ? (int) \Illuminate\Support\Str::after($formContext, 'edit-')
        : null;
      $openCreateForm = $showAddressErrors && $formContext === 'create';
      $openCreateForm = $openCreateForm || ($addresses->isEmpty() && ! $address);
    @endphp

    <section class="grid gap-10 lg:grid-cols-[1.4fr,1fr]">
      <article class="space-y-6 rounded-[2.5rem] border border-rose-100 bg-white/95 p-8 shadow-xl shadow-rose-100/60">
        @unless (auth()->user()->isAdmin())
          @if ($statusKey)
            <div class="rounded-2xl border {{ $statusKey === 'address-deleted' ? 'border-amber-200 bg-amber-50 text-amber-700' : 'border-emerald-200 bg-emerald-50 text-emerald-700' }} px-4 py-3 text-sm shadow-sm">
              {{ $statusMessage ?? 'Operación realizada correctamente.' }}
            </div>
          @elseif ($showAddressErrors)
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-600 shadow-sm">
              Revisa los campos marcados en tu dirección antes de continuar.
            </div>
          @endif
        @endunless

        <header class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h1 class="text-3xl font-bold text-rose-700">Hola, {{ auth()->user()->name }}</h1>
            <p class="text-sm text-slate-500">
              Desde aquí puedes revisar tu historial, gestionar tu dirección y seguir tus pedidos.
            </p>
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

        @unless (auth()->user()->isAdmin())
          <section class="rounded-2xl border border-rose-100 bg-white/95 p-6 shadow-sm shadow-rose-100/50">
          <header class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
              <h2 class="text-lg font-semibold text-rose-700">Dirección de envío</h2>
              <p class="text-sm text-slate-500">Administra todas tus direcciones guardadas y elige cuál usar por defecto.</p>
            </div>
          </header>

          @if ($addresses->isEmpty())
            <p class="mt-4 rounded-2xl border border-dashed border-rose-200 bg-rose-50/40 px-4 py-3 text-sm text-rose-600">
              Aún no registras una dirección. Agrega una para guardar tus envíos frecuentes.
            </p>
          @endif

          @if ($addresses->isNotEmpty())
            <div class="mt-6 space-y-5">
              @foreach ($addresses as $addressItem)
                @php
                  $editorId = "account-address-editor-{$addressItem->id}";
                  $isOpen = $openEditorId === $addressItem->id;
                @endphp
                <article class="space-y-4 rounded-2xl border border-rose-100 bg-white p-5 shadow-sm">
                  <header class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                      <h3 class="text-base font-semibold text-rose-700">
                        Dirección {{ $loop->iteration }}
                        @if ($addressItem->is_default)
                          <span class="ml-2 rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-700">Predeterminada</span>
                        @endif
                      </h3>
                      <p class="text-sm text-slate-500">
                        {{ $addressItem->street }} {{ $addressItem->number }}@if ($addressItem->apartment), {{ $addressItem->apartment }}@endif
                      </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                      @if (! $addressItem->is_default)
                        <form method="POST" action="{{ route('user-addresses.default', $addressItem) }}">
                          @csrf
                          @method('PATCH')
                          <input type="hidden" name="redirect_to" value="{{ $addressRedirect }}">
                          <input type="hidden" name="form_context" value="default-{{ $addressItem->id }}">
                          <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-emerald-200 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600 transition hover:border-emerald-300 hover:bg-emerald-50">
                            Usar por defecto
                          </button>
                        </form>
                      @endif

                      <button
                        type="button"
                        class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-rose-500 transition hover:border-rose-300 hover:bg-rose-50"
                        data-address-toggle
                        data-target="#{{ $editorId }}"
                        data-open-text="Editar"
                        data-close-text="Cancelar"
                        {{ $isOpen ? 'aria-expanded=true' : '' }}
                      >
                        Editar
                      </button>

                      <form method="POST" action="{{ route('user-addresses.destroy', $addressItem) }}" onsubmit="return confirm('¿Seguro que deseas eliminar esta dirección?');">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="redirect_to" value="{{ $addressRedirect }}">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 transition hover:border-rose-200 hover:text-rose-600">
                          Eliminar
                        </button>
                      </form>
                    </div>
                  </header>

                  <dl class="grid gap-2 rounded-2xl border border-rose-100 bg-rose-50/60 p-4 text-sm text-rose-700">
                    <div class="flex items-start justify-between">
                      <dt class="font-semibold text-rose-800">Comuna</dt>
                      <dd class="text-right">{{ $addressItem->commune->name }}</dd>
                    </div>
                    <div class="flex items-start justify-between">
                      <dt class="font-semibold text-rose-800">Región</dt>
                      <dd class="text-right">{{ $addressItem->region->name }}</dd>
                    </div>
                    @if ($addressItem->reference)
                      <div class="flex items-start justify-between">
                        <dt class="font-semibold text-rose-800">Referencia</dt>
                        <dd class="max-w-xs text-right">{{ $addressItem->reference }}</dd>
                      </div>
                    @endif
                  </dl>

                  <div id="{{ $editorId }}" class="{{ $isOpen ? '' : 'hidden' }}" data-address-editor>
                    <x-address.form
                      :action="route('user-addresses.update', $addressItem)"
                      method="PUT"
                      :regions="$regions"
                      :address="$addressItem"
                      :redirect="$addressRedirect"
                      submit-text="Guardar cambios"
                      :form-context="'edit-' . $addressItem->id"
                    />
                  </div>
                </article>
              @endforeach
            </div>
          @endif

          <div class="mt-6 flex flex-wrap items-center gap-3">
            <button
              type="button"
              class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-5 py-2 text-sm font-semibold text-rose-600 transition hover:border-rose-300 hover:bg-rose-50"
              data-address-toggle
              data-target="#account-address-editor-create"
              data-open-text="Agregar dirección"
              data-close-text="Cerrar formulario"
              {{ $openCreateForm ? 'aria-expanded=true' : '' }}
            >
              Agregar dirección
            </button>
          </div>

          <div
            id="account-address-editor-create"
            class="mt-6 {{ $openCreateForm ? '' : 'hidden' }}"
            data-address-editor
          >
            <x-address.form
              :action="route('user-addresses.store')"
              method="POST"
              :regions="$regions"
              :address="null"
              :redirect="$addressRedirect"
              :show-default-toggle="$addresses->isNotEmpty()"
              :default-checked="! $addresses->isNotEmpty()"
              submit-text="Guardar dirección"
              form-context="create"
              class="grid gap-4 sm:grid-cols-2"
            />
          </div>
          </section>
        @endunless

        <div class="flex flex-wrap items-center gap-3">
          @if (! auth()->user()->hasVerifiedEmail())
            <form method="POST" action="{{ route('verification.send') }}">
              @csrf
              <button type="submit"
                      class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-5 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-rose-500 transition hover:border-rose-300 hover:bg-rose-50">
                Reenviar verificación
              </button>
            </form>
          @endif

          <a href="{{ route('cart') }}"
             class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-6 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-white shadow shadow-rose-200/60 transition hover:from-rose-500 hover:to-amber-400">
            Ver carrito
          </a>

          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-6 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-rose-500 transition hover:border-rose-300 hover:bg-rose-50">
              Cerrar sesión
            </button>
          </form>
        </div>
      </article>

      <aside class="space-y-5 rounded-[2.5rem] border border-rose-100 bg-white/95 p-6 shadow-lg shadow-rose-100/60">
        <h2 class="text-lg font-semibold text-rose-600">Atajos</h2>
        <div class="grid gap-4 text-sm text-slate-600">
          @unless (auth()->user()->isAdmin())
            <a href="{{ route('cart') }}"
               class="flex items-center justify-between rounded-2xl border border-rose-100 bg-white/95 px-4 py-3 transition hover:border-rose-300 hover:text-rose-600">
              <span>Seguir mis pedidos</span>
              <span class="text-xs uppercase tracking-[0.25em] text-rose-400">Carrito</span>
            </a>
          @endunless
          @if (auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center justify-between rounded-2xl border border-rose-100 bg-white/95 px-4 py-3 transition hover:border-rose-300 hover:text-rose-600">
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
            <img src="{{ Vite::asset('resources/images/animales/pollito.png') }}" alt="Amigurumi pollito" class="h-full w-full object-cover">
          </div>
          <div>
            <h1 class="text-2xl font-semibold text-rose-700">¡Bienvenida/o de vuelta!</h1>
            <p class="text-sm text-slate-500">Accede para seguir tus pedidos, guardar direcciones y recibir novedades.</p>
          </div>
        </div>
        <a href="{{ route('login') }}"
           class="inline-flex w-full items-center justify-center rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-6 py-3 text-sm font-semibold text-white shadow shadow-rose-200/60 transition hover:from-rose-500 hover:to-amber-400">
          Iniciar sesión
        </a>
        <p class="text-xs text-slate-400">¿No recuerdas tu contraseña? Puedes solicitar un enlace desde la pantalla de acceso.</p>
      </article>

      <article class="space-y-6 rounded-[2.5rem] border border-rose-100 bg-white/95 p-8 shadow-xl shadow-rose-100/60">
        <div class="overflow-hidden rounded-[2rem]">
          <img src="{{ Vite::asset('resources/images/munecas/muneca_celeste/muneca_celeste2.jpg') }}" alt="Muñeca crochet tonos celestes" class="h-44 w-full object-cover">
        </div>
        <div class="space-y-4">
          <h2 class="text-lg font-semibold text-rose-600">¿Primera vez en PolyCrochet?</h2>
          <p class="text-sm text-slate-500">Crea tu cuenta para guardar direcciones, recibir asesoría personalizada y obtener acceso anticipado a lanzamientos.</p>
          <a href="{{ route('register') }}"
             class="inline-flex w-full items-center justify-center rounded-full border border-rose-200 px-6 py-3 text-sm font-semibold text-rose-500 transition hover:border-rose-300 hover:bg-rose-50">
            Crear cuenta
          </a>
        </div>
      </article>
    </section>
  @endauth
@endsection

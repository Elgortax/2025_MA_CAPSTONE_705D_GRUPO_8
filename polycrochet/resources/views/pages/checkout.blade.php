@extends('layouts.app')

@section('title', 'Checkout | PolyCrochet')

@section('content')
  @php
    $addressFormHasErrors = $errors->any() && old('redirect_to') === route('checkout');
    $formContext = old('form_context');
    $statusKey = session('status');
    $statusMessage = session('status_message');
    $openEditorId = $addressFormHasErrors && $formContext && \Illuminate\Support\Str::startsWith($formContext, 'edit-')
      ? (int) \Illuminate\Support\Str::after($formContext, 'edit-')
      : null;
    $openCreateForm = $addressFormHasErrors && $formContext === 'create';
    $openCreateForm = $openCreateForm || ($addresses->isEmpty() && ! $address);
  @endphp

  <section class="space-y-8">
    <header>
      <h1 class="text-2xl font-bold text-slate-900">Finaliza tu compra</h1>
      <p class="mt-2 text-sm text-slate-600">
        Revisa tus datos antes de continuar con el pago seguro a través de PayPal.
      </p>
    </header>

    @if ($statusKey)
      <div class="rounded-2xl border {{ $statusKey === 'address-deleted' ? 'border-amber-200 bg-amber-50 text-amber-700' : 'border-emerald-200 bg-emerald-50 text-emerald-700' }} px-4 py-3 text-sm shadow-sm">
        {{ $statusMessage ?? 'Dirección actualizada correctamente.' }}
      </div>
    @endif

    @if ($errors->any())
      <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-600 shadow-sm">
        Revisa los campos marcados para continuar.
      </div>
    @endif

    <div class="grid gap-8 lg:grid-cols-[2fr,1fr]">
      <div class="space-y-6">
        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
          <h2 class="text-lg font-semibold text-slate-900">Información de contacto</h2>
          <p class="mt-2 text-sm text-slate-600">
            @auth
              {{ auth()->user()->name }} — {{ auth()->user()->email }}
              @if (auth()->user()->phone)
                — +56 {{ auth()->user()->phone }}
              @endif
            @else
              Inicia sesión o crea una cuenta para guardar tu dirección y agilizar futuras compras.
            @endauth
          </p>
          @guest
            <div class="mt-4 flex flex-wrap gap-3">
              <a href="{{ route('login') }}"
                 class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-6 py-2 text-sm font-semibold text-white shadow transition hover:from-rose-500 hover:to-amber-400">
                Iniciar sesión
              </a>
              <a href="{{ route('register') }}"
                 class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-6 py-2 text-sm font-semibold text-rose-500 transition hover:border-rose-300 hover:bg-rose-50">
                Crear cuenta
              </a>
            </div>
          @endguest
        </article>

        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
          <header class="flex items-start justify-between gap-4">
            <div>
              <h2 class="text-lg font-semibold text-slate-900">Dirección de envío</h2>
              <p class="mt-1 text-sm text-slate-600">
                @auth
                  Selecciona la dirección que usaremos en este pedido o agrega una nueva.
                @else
                  Inicia sesión para guardar tu dirección de envío en PolyCrochet.
                @endauth
              </p>
            </div>
          </header>

          @auth
            @if ($addresses->isNotEmpty())
              <div class="mt-4 space-y-4">
                @foreach ($addresses as $addressItem)
                  @php
                    $editorId = "checkout-address-editor-{$addressItem->id}";
                    $isOpen = $openEditorId === $addressItem->id;
                  @endphp
                  <article class="rounded-2xl border {{ $addressItem->is_default ? 'border-blue-200 bg-blue-50/60' : 'border-slate-200 bg-slate-50/60' }} p-4 shadow-sm transition hover:border-blue-200 hover:bg-blue-50/70">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                      <div class="flex items-start gap-3">
                        <form method="POST" action="{{ route('user-addresses.default', $addressItem) }}" data-address-select>
                          @csrf
                          @method('PATCH')
                          <input type="hidden" name="redirect_to" value="{{ route('checkout') }}">
                          <input type="hidden" name="form_context" value="select-{{ $addressItem->id }}">
                          <input
                            type="radio"
                            name="address_choice"
                            value="{{ $addressItem->id }}"
                            class="mt-1 h-5 w-5 text-blue-600 focus:ring-blue-500"
                            data-address-radio
                            @checked($addressItem->is_default)
                          >
                        </form>
                        <div>
                          <h3 class="text-sm font-semibold text-slate-800">
                            {{ $addressItem->street }} {{ $addressItem->number }}
                            @if ($addressItem->apartment)
                              <span class="text-xs text-slate-500">• {{ $addressItem->apartment }}</span>
                            @endif
                          </h3>
                          <p class="text-xs text-slate-500">
                            {{ $addressItem->commune->name }} • {{ $addressItem->region->name }}
                          </p>
                          @if ($addressItem->reference)
                            <p class="text-xs text-slate-400 mt-1">
                              Ref.: {{ $addressItem->reference }}
                            </p>
                          @endif
                        </div>
                      </div>

                      <div class="flex flex-wrap items-center gap-2">
                        <button
                          type="button"
                          class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-600 transition hover:border-blue-300 hover:bg-blue-50"
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
                          <input type="hidden" name="redirect_to" value="{{ route('checkout') }}">
                          <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-600 transition hover:border-rose-300 hover:text-rose-600">
                            Eliminar
                          </button>
                        </form>
                      </div>
                    </div>

                    <div id="{{ $editorId }}" class="mt-4 {{ $isOpen ? '' : 'hidden' }}" data-address-editor>
                      <x-address.form
                        :action="route('user-addresses.update', $addressItem)"
                        method="PUT"
                        :regions="$regions"
                        :address="$addressItem"
                        :redirect="route('checkout')"
                        submit-text="Guardar cambios"
                        :form-context="'edit-' . $addressItem->id"
                      />
                    </div>
                  </article>
                @endforeach
              </div>
            @else
              <p class="mt-4 rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 px-4 py-3 text-sm text-slate-600">
                Aún no registras una dirección de envío. Agrega una para continuar con tu compra.
              </p>
            @endif

            <div class="mt-6 flex flex-wrap items-center gap-3">
              <button
                type="button"
                class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-5 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50"
                data-address-toggle
                data-target="#checkout-address-editor-create"
                data-open-text="Agregar dirección"
                data-close-text="Cerrar formulario"
                {{ $openCreateForm ? 'aria-expanded=true' : '' }}
              >
                Agregar dirección
              </button>
            </div>

            <div
              id="checkout-address-editor-create"
              class="mt-6 {{ $openCreateForm ? '' : 'hidden' }}"
              data-address-editor
            >
              <x-address.form
                :action="route('user-addresses.store')"
                method="POST"
                :regions="$regions"
                :address="null"
                :redirect="route('checkout')"
                :show-default-toggle="$addresses->isNotEmpty()"
                :default-checked="! $addresses->isNotEmpty()"
                submit-text="Guardar dirección"
                form-context="create"
                class="grid gap-4 sm:grid-cols-2"
              />
            </div>
          @else
            <div class="mt-6 rounded-2xl border border-dashed border-rose-200 bg-rose-50/40 p-4 text-sm text-rose-600">
              Una vez que ingreses con tu cuenta podrás registrar tu dirección de envío de forma segura.
            </div>
          @endauth
        </article>

        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
          <h2 class="text-lg font-semibold text-slate-900">Pago con PayPal</h2>
          <p class="mt-2 text-sm text-slate-600">
            Te redirigiremos a la pasarela segura de PayPal. Al finalizar, volverás a PolyCrochet con la confirmación de tu pedido.
          </p>
          <button type="button"
                  class="mt-4 inline-flex w-full justify-center rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow transition hover:bg-blue-500">
            Ir a PayPal
          </button>
        </article>
      </div>

      <aside class="space-y-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-900">Resumen de compra</h2>
        <ul class="space-y-3 text-sm text-slate-600">
          @foreach ([
            [
              'name' => 'Ramo Animalitos – Gatito pastel',
              'image' => 'resources/images/ramos/ramo_gato/ramo_rosa_gato.jpg',
              'price' => '$39.990',
            ],
            [
              'name' => 'Set de girasoles con abejas',
              'image' => 'resources/images/girasol/girasol_abejas.jpg',
              'price' => '$22.900',
            ],
          ] as $summary)
            <li class="flex items-center gap-3">
              <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-lg border border-slate-100">
                <img src="{{ Vite::asset($summary['image']) }}" alt="{{ $summary['name'] }}" class="h-full w-full object-cover">
              </div>
              <div class="flex flex-1 items-center justify-between">
                <span class="text-sm font-medium text-slate-800">{{ $summary['name'] }}</span>
                <span class="text-sm font-semibold text-slate-900">{{ $summary['price'] }}</span>
              </div>
            </li>
          @endforeach
        </ul>
        <dl class="space-y-2 text-sm text-slate-600">
          <div class="flex justify-between"><dt>Subtotal</dt><dd>$62.890</dd></div>
          <div class="flex justify-between"><dt>Envío estimado</dt><dd>$4.500</dd></div>
          <div class="flex justify-between font-semibold text-slate-900"><dt>Total</dt><dd>$67.390</dd></div>
        </dl>
        <p class="text-xs text-slate-400">Al continuar aceptas nuestras políticas y términos de compra.</p>
      </aside>
    </div>
  </section>
@endsection

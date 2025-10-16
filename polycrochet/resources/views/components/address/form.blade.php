@props([
    'action',
    'method' => 'POST',
    'regions' => collect(),
    'address' => null,
    'submitText' => 'Guardar dirección',
    'redirect' => null,
    'showDefaultToggle' => false,
    'defaultChecked' => false,
    'formContext' => null,
])

@php
    $selectedRegion = old('region_id', optional($address)->region_id);
    $selectedCommune = old('commune_id', optional($address)->commune_id);
    $regionsForSelect = $regions instanceof \Illuminate\Support\Collection ? $regions : collect($regions);
    $communesForSelectedRegion = $selectedRegion
        ? optional($regionsForSelect->firstWhere('id', (int) $selectedRegion))->communes ?? collect()
        : collect();

    $inputBaseClass = 'mt-1 w-full rounded-md border px-3 py-2 text-sm transition focus:outline-none focus:ring-1';
    $inputNeutralClass = 'border-gray-300 focus:border-blue-500 focus:ring-blue-500';
    $inputErrorClass = 'border-rose-400 focus:border-rose-500 focus:ring-rose-300';

    $locationsPayload = $regionsForSelect
        ->map(fn ($region) => [
            'id' => $region->id,
            'name' => $region->name,
            'communes' => collect($region->communes)
                ->map(fn ($commune) => [
                    'id' => $commune->id,
                    'name' => $commune->name,
                ])
                ->values(),
        ])
        ->values();
@endphp

@once
  <script id="chile-locations" type="application/json">{!! $locationsPayload->toJson(JSON_UNESCAPED_UNICODE) !!}</script>
@endonce

<form method="POST" action="{{ $action }}" {{ $attributes->merge(['class' => 'grid gap-4 sm:grid-cols-2']) }} data-address-form>
  @csrf
  @if (strtoupper($method) === 'PUT')
    @method('PUT')
  @endif

  @if ($redirect)
    <input type="hidden" name="redirect_to" value="{{ $redirect }}">
  @endif

  @if ($formContext)
    <input type="hidden" name="form_context" value="{{ $formContext }}">
  @endif

  @if ($showDefaultToggle)
    <label class="sm:col-span-2 flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
      <input
        type="checkbox"
        name="make_default"
        value="1"
        class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
        @checked(old('make_default', $defaultChecked))
      >
      Usar como dirección predeterminada
    </label>
  @endif

  <label class="text-sm font-medium text-gray-700 sm:col-span-2">
    Calle
    <input
      type="text"
      name="street"
      value="{{ old('street', optional($address)->street) }}"
      autocomplete="address-line1"
      class="{{ $inputBaseClass }} {{ $errors->has('street') ? $inputErrorClass : $inputNeutralClass }}"
      placeholder="Ej. Avenida Siempre Viva"
      required
    >
    @error('street')
      <span class="mt-1 block text-xs font-medium text-rose-500">{{ $message }}</span>
    @enderror
  </label>

  <label class="text-sm font-medium text-gray-700">
    Número
    <input
      type="text"
      name="number"
      value="{{ old('number', optional($address)->number) }}"
      autocomplete="address-line2"
      class="{{ $inputBaseClass }} {{ $errors->has('number') ? $inputErrorClass : $inputNeutralClass }}"
      placeholder="742"
      required
    >
    @error('number')
      <span class="mt-1 block text-xs font-medium text-rose-500">{{ $message }}</span>
    @enderror
  </label>

  <label class="text-sm font-medium text-gray-700">
    Departamento / Casa (opcional)
    <input
      type="text"
      name="apartment"
      value="{{ old('apartment', optional($address)->apartment) }}"
      class="{{ $inputBaseClass }} {{ $errors->has('apartment') ? $inputErrorClass : $inputNeutralClass }}"
      placeholder="Depto 502, Casa B"
    >
    @error('apartment')
      <span class="mt-1 block text-xs font-medium text-rose-500">{{ $message }}</span>
    @enderror
  </label>

  <label class="text-sm font-medium text-gray-700">
    Referencia (opcional)
    <input
      type="text"
      name="reference"
      value="{{ old('reference', optional($address)->reference) }}"
      class="{{ $inputBaseClass }} {{ $errors->has('reference') ? $inputErrorClass : $inputNeutralClass }}"
      placeholder="Portón negro, frente a plaza"
    >
    @error('reference')
      <span class="mt-1 block text-xs font-medium text-rose-500">{{ $message }}</span>
    @enderror
  </label>

  <label class="text-sm font-medium text-gray-700">
    Código postal (opcional)
    <input
      type="text"
      name="postal_code"
      value="{{ old('postal_code', optional($address)->postal_code) }}"
      class="{{ $inputBaseClass }} {{ $errors->has('postal_code') ? $inputErrorClass : $inputNeutralClass }}"
      placeholder="8320000"
    >
    @error('postal_code')
      <span class="mt-1 block text-xs font-medium text-rose-500">{{ $message }}</span>
    @enderror
  </label>

  <label class="text-sm font-medium text-gray-700 sm:col-span-2">
    Región
    <select
      name="region_id"
      data-region-select
      data-default-value="{{ $selectedRegion }}"
      class="{{ $inputBaseClass }} {{ $errors->has('region_id') ? $inputErrorClass : $inputNeutralClass }}"
      required
    >
      <option value="">Selecciona una región</option>
      @foreach ($regionsForSelect as $region)
        <option value="{{ $region->id }}" @selected((string) $region->id === (string) $selectedRegion)>
          {{ $region->name }}
        </option>
      @endforeach
    </select>
    @error('region_id')
      <span class="mt-1 block text-xs font-medium text-rose-500">{{ $message }}</span>
    @enderror
  </label>

  <label class="text-sm font-medium text-gray-700 sm:col-span-2">
    Comuna
    <select
      name="commune_id"
      data-commune-select
      data-default-value="{{ $selectedCommune }}"
      class="{{ $inputBaseClass }} {{ $errors->has('commune_id') ? $inputErrorClass : $inputNeutralClass }}"
      required
    >
      <option value="">Selecciona una comuna</option>
      @foreach ($communesForSelectedRegion as $commune)
        <option value="{{ $commune->id }}" @selected((string) $commune->id === (string) $selectedCommune)>
          {{ $commune->name }}
        </option>
      @endforeach
    </select>
    @error('commune_id')
      <span class="mt-1 block text-xs font-medium text-rose-500">{{ $message }}</span>
    @enderror
  </label>

  <div class="sm:col-span-2 text-right">
    <button
      type="submit"
      class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow transition hover:bg-blue-500"
    >
      {{ $submitText }}
    </button>
  </div>
</form>

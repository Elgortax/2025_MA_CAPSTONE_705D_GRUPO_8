@extends('layouts.admin')
@section('title', 'Clientes | PolyCrochet')
@section('page_heading', 'Clientes')

@section('content')
  <section class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-semibold text-white">Clientes</h1>
        <p class="text-sm text-slate-400">Visualiza información clave, pedidos y comportamiento de compra.</p>
      </div>
      <form method="GET">
        <input
          type="search"
          name="q"
          value="{{ $search }}"
          placeholder="Buscar cliente"
          class="w-64 rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-xs text-slate-300 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
        />
      </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/60">
      <table class="min-w-full divide-y divide-slate-800 text-sm">
        <thead class="bg-slate-900/70 text-left text-xs uppercase tracking-wide text-slate-400">
          <tr>
            <th class="px-4 py-3">Nombre</th>
            <th class="px-4 py-3">Correo</th>
            <th class="px-4 py-3 text-center">Pedidos</th>
            <th class="px-4 py-3 text-right">Monto total</th>
            <th class="px-4 py-3 text-right">Último pedido</th>
            <th class="px-4 py-3 text-center">Estado</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-800 text-slate-300">
          @forelse ($customers as $customer)
            @php
              $totalSpent = (float) ($customer->orders_total_sum ?? 0);
              $lastOrderAt = $customer->orders_max_created_at ? \Carbon\Carbon::parse($customer->orders_max_created_at) : null;
              $statusLabel = match (true) {
                $customer->orders_count === 0 => 'Nuevo',
                $lastOrderAt && $lastOrderAt->lt(now()->subMonths(3)) => 'Inactivo',
                $totalSpent >= 200000 => 'Premium',
                default => 'Activo',
              };
              $statusClass = match ($statusLabel) {
                'Premium' => 'bg-purple-500/10 text-purple-300 border-purple-400/40',
                'Inactivo' => 'bg-slate-500/10 text-slate-300 border-slate-400/40',
                'Nuevo' => 'bg-blue-500/10 text-blue-300 border-blue-400/40',
                default => 'bg-emerald-500/10 text-emerald-300 border-emerald-400/40',
              };
            @endphp
            <tr>
              <td class="px-4 py-3 font-semibold text-white">
                {{ $customer->name }}
              </td>
              <td class="px-4 py-3">{{ $customer->email }}</td>
              <td class="px-4 py-3 text-center">{{ $customer->orders_count }}</td>
              <td class="px-4 py-3 text-right">${{ number_format($totalSpent, 0, ',', '.') }}</td>
              <td class="px-4 py-3 text-right">
                @if ($lastOrderAt)
                  {{ $lastOrderAt->translatedFormat('d/m/Y H:i') }}
                @else
                  —
                @endif
              </td>
              <td class="px-4 py-3 text-center">
                <span class="inline-flex rounded-full border px-3 py-0.5 text-xs font-semibold {{ $statusClass }}">
                  {{ $statusLabel }}
                </span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-4 py-6 text-center text-sm text-slate-300">
                No se encontraron clientes con los criterios aplicados.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{ $customers->links() }}
  </section>
@endsection

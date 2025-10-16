@extends('layouts.admin')
@section('title', 'Dashboard | PolyCrochet')
@section('page_heading', 'Resumen general')

@section('content')
  <section class="grid gap-6 lg:grid-cols-3">
    <article class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6">
      <p class="text-sm text-slate-400">Ventas del mes</p>
      <p class="mt-2 text-3xl font-semibold text-white">${{ number_format($metrics['month_sales'], 0, ',', '.') }}</p>
      <p class="mt-1 text-xs {{ $metrics['sales_variance'] !== null && $metrics['sales_variance'] >= 0 ? 'text-emerald-400' : 'text-amber-300' }}">
        @if ($metrics['sales_variance'] === null)
          Primer mes con registros
        @else
          {{ $metrics['sales_variance'] >= 0 ? '+' : '' }}{{ number_format($metrics['sales_variance'], 1, ',', '.') }}% vs mes anterior
        @endif
      </p>
    </article>

    <article class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6">
      <p class="text-sm text-slate-400">Unidades vendidas</p>
      <p class="mt-2 text-3xl font-semibold text-white">{{ $metrics['month_units'] }}</p>
      <p class="mt-1 text-xs text-slate-400">Productos entregados o en produccion durante el mes</p>
    </article>

    <article class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6">
      <p class="text-sm text-slate-400">Pedidos confirmados</p>
      <p class="mt-2 text-3xl font-semibold text-white">{{ $metrics['orders_count'] }}</p>
      <p class="mt-1 text-xs text-slate-400">Incluye pedidos pagados o en proceso de confeccion</p>
    </article>
  </section>

  <section class="mt-8 grid gap-6 lg:grid-cols-[2fr,1fr]">
    <article class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-white">Ventas del mes</h2>
        <a href="{{ route('admin.reports.weekly') }}" class="rounded-full border border-slate-700 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-slate-300 transition hover:border-blue-500 hover:text-blue-300">
          Descargar semana
        </a>
      </div>
      <canvas id="salesChart" class="mt-6 h-72"></canvas>
    </article>

    <article class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6">
      <h2 class="text-lg font-semibold text-white">Pedidos recientes</h2>
      <ul class="mt-4 space-y-4 text-sm text-slate-300">
        @forelse ($recentOrders as $order)
          <li class="rounded-xl border border-slate-800/60 bg-slate-900/80 p-4">
            <div class="flex items-center justify-between gap-3">
              <div>
                <p class="text-sm font-semibold text-white">{{ $order->order_number }}</p>
                <p class="text-xs text-slate-400">{{ optional($order->user)->name ?? 'Invitado' }}</p>
              </div>
              <span class="rounded-full border border-blue-400/30 bg-blue-500/10 px-2 py-0.5 text-xs font-semibold text-blue-300">
                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
              </span>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs text-slate-400">
              <span>{{ $order->created_at->format('d M Y - H:i') }}</span>
              <span class="font-semibold text-white">${{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
          </li>
        @empty
          <li class="rounded-xl border border-slate-800/60 bg-slate-900/80 p-4 text-xs text-slate-400">
            Aun no registras pedidos.
          </li>
        @endforelse
      </ul>
    </article>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js" integrity="sha256-GsKLKMdVdX6f+9J3R1GwM3Uf7webx+e9x8RJWb1x1oA=" crossorigin="anonymous"></script>
  <script>
    const salesData = @json($chartPoints);
    const ctx = document.getElementById('salesChart').getContext('2d');
    const labels = salesData.map(point => point.day);
    const totals = salesData.map(point => point.total);

    new Chart(ctx, {
      type: 'line',
      data: {
        labels,
        datasets: [{
          label: 'Ventas (CLP)',
          data: totals,
          tension: 0.35,
          borderColor: '#fb7185',
          backgroundColor: 'rgba(251, 113, 133, 0.25)',
          fill: true,
          pointRadius: 4,
          pointBackgroundColor: '#fb7185',
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            ticks: {
              callback: value => '$' + Intl.NumberFormat('es-CL').format(value),
              color: '#cbd5f5',
            },
            grid: { color: 'rgba(148, 163, 184, 0.15)' },
          },
          x: {
            ticks: { color: '#cbd5f5' },
            grid: { display: false },
          },
        },
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: context => '$' + Intl.NumberFormat('es-CL').format(context.parsed.y),
            },
          },
        },
      },
    });
  </script>
@endsection

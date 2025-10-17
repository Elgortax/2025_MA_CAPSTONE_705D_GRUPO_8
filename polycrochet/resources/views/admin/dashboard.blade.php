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
        <a href="{{ route('admin.reports.monthly') }}" class="rounded-full border border-slate-700 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-slate-300 transition hover:border-blue-500 hover:text-blue-300">
          Descargar mes
        </a>
      </div>
      <div class="mt-6 h-72">
        <canvas id="salesChart" class="h-full w-full"></canvas>
      </div>
    </article>

    <article class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6">
      <h2 class="text-lg font-semibold text-white">Pedidos recientes</h2>
      <ul class="mt-4 space-y-4 text-sm text-slate-300">
        @forelse ($recentOrders as $order)
          <li class="rounded-xl border border-slate-800/60 bg-slate-900/80 p-4">
            <div class="flex items-center justify-between gap-3">
              <div>
                <p class="text-sm font-semibold text-white">
                  <a href="{{ route('admin.orders.show', $order) }}" class="hover:text-blue-300">
                    {{ $order->order_number }}
                  </a>
                </p>
                <p class="text-xs text-slate-400">{{ optional($order->user)->name ?? 'Invitado' }}</p>
              </div>
              @php
                $badgeClass = match ($order->status) {
                  'pagado' => 'border-emerald-400/30 bg-emerald-400/10 text-emerald-200',
                  'en_produccion' => 'border-blue-400/30 bg-blue-400/10 text-blue-200',
                  'enviado' => 'border-sky-400/30 bg-sky-400/10 text-sky-200',
                  'cancelado' => 'border-rose-400/30 bg-rose-400/10 text-rose-200',
                  default => 'border-amber-400/30 bg-amber-400/10 text-amber-200',
                };
              @endphp
              <span class="rounded-full border px-2 py-0.5 text-xs font-semibold {{ $badgeClass }}">
                {{ $order->status_label }}
              </span>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs text-slate-400">
              <span>{{ $order->created_at->translatedFormat('d M Y - H:i') }}</span>
              <span class="font-semibold text-white">${{ number_format((float) $order->total, 0, ',', '.') }}</span>
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

  <section class="mt-8 grid gap-6 lg:grid-cols-2">
    <article class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-white">Ventas por categoria (mes)</h2>
        <a href="{{ route('admin.reports.categories') }}" class="rounded-full border border-slate-700 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-slate-300 transition hover:border-blue-500 hover:text-blue-300">
          Descargar
        </a>
      </div>
      @if ($categoryBreakdown->isEmpty())
        <p class="mt-6 text-sm text-slate-400">Aun no hay ventas registradas este mes para construir el grafico.</p>
      @else
        <div class="mt-6 h-72">
          <canvas id="categoryChart" class="h-full w-full"></canvas>
        </div>
        <p class="mt-4 text-xs text-slate-500">Incluye categorias con pedidos pagados, en produccion o enviados durante el mes.</p>
      @endif
    </article>

    <article class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-white">Top productos vendidos</h2>
        <a href="{{ route('admin.reports.products') }}" class="rounded-full border border-slate-700 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-slate-300 transition hover:border-blue-500 hover:text-blue-300">
          Descargar
        </a>
      </div>
      @if ($topProducts->isEmpty())
        <p class="mt-6 text-sm text-slate-400">Aun no hay ventas registradas este mes para calcular el ranking.</p>
      @else
        <div class="mt-6 h-72">
          <canvas id="topProductsChart" class="h-full w-full"></canvas>
        </div>
        <p class="mt-4 text-xs text-slate-500">Se muestran hasta 8 productos con mayor facturacion del mes.</p>
      @endif
    </article>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js" integrity="sha256-llOggT23Q7vngzKjiW4ox7x1RuT/9R5+l56QjR8EcdE=" crossorigin="anonymous"></script>
  <script>
    const salesData = @json($chartPoints);
    const currencyFormatter = new Intl.NumberFormat('es-CL');
    const salesCanvas = document.getElementById('salesChart');

    if (salesCanvas) {
      const ctx = salesCanvas.getContext('2d');
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
                callback: value => '$' + currencyFormatter.format(value),
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
                label: context => '$' + currencyFormatter.format(context.parsed.y),
              },
            },
          },
        },
      });
    }

    const categoryData = @json($categoryBreakdown->values());
    const categoryCanvas = document.getElementById('categoryChart');

    if (categoryCanvas && categoryData.length) {
      const categoryLabels = categoryData.map(item => item.category);
      const categoryTotals = categoryData.map(item => item.total);

      new Chart(categoryCanvas.getContext('2d'), {
        type: 'bar',
        data: {
          labels: categoryLabels,
          datasets: [{
            label: 'Ventas (CLP)',
            data: categoryTotals,
            backgroundColor: '#38bdf8',
            borderColor: '#0ea5e9',
            borderWidth: 1.5,
            borderRadius: 6,
            maxBarThickness: 42,
            barPercentage: 0.55,
            categoryPercentage: 0.55,
          }],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              ticks: {
                callback: value => '$' + currencyFormatter.format(value),
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
                label: context => '$' + currencyFormatter.format(context.parsed.y),
                afterLabel: context => {
                  const item = categoryData[context.dataIndex];
                  return 'Unidades: ' + item.units;
                },
              },
            },
          },
        },
      });
    }

    const productData = @json($topProducts->values());
    const productsCanvas = document.getElementById('topProductsChart');

    if (productsCanvas && productData.length) {
      const productLabels = productData.map(item => item.name);
      const productTotals = productData.map(item => item.total);

      new Chart(productsCanvas.getContext('2d'), {
        type: 'bar',
        data: {
          labels: productLabels,
          datasets: [{
            label: 'Ventas (CLP)',
            data: productTotals,
            backgroundColor: '#f472b6',
            borderColor: '#ec4899',
            borderWidth: 1.5,
            borderRadius: 6,
            maxBarThickness: 32,
            barPercentage: 0.6,
            categoryPercentage: 0.6,
          }],
        },
        options: {
          indexAxis: 'y',
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            x: {
              ticks: {
                callback: value => '$' + currencyFormatter.format(value),
                color: '#cbd5f5',
              },
              grid: { color: 'rgba(148, 163, 184, 0.15)' },
            },
            y: {
              ticks: { color: '#cbd5f5' },
              grid: { display: false },
            },
          },
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: context => {
                  const base = '$' + currencyFormatter.format(context.parsed.x);
                  const item = productData[context.dataIndex];
                  return base + ' | ' + item.units + ' uds';
                },
                afterLabel: context => {
                  const sku = productData[context.dataIndex].sku;
                  return sku ? 'SKU: ' + sku : '';
                },
              },
            },
          },
        },
      });
    }
  </script>
@endsection

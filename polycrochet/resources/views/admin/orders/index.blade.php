@extends('layouts.admin')
@section('title', 'Pedidos | PolyCrochet')
@section('page_heading', 'Pedidos')

@section('content')
  <section class="space-y-6">
    @if (session('status'))
      <div class="rounded-full border border-emerald-400/30 bg-emerald-500/10 px-4 py-2 text-sm text-emerald-200">
        {{ session('status') }}
      </div>
    @endif

    <div class="flex flex-wrap items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-semibold text-white">Pedidos</h1>
        <p class="text-sm text-slate-400">Monitorea el avance de cada pedido y registra notas internas.</p>
      </div>
      <form method="GET" class="flex flex-wrap items-center gap-3">
        <input
          type="search"
          name="q"
          value="{{ $search }}"
          placeholder="Buscar pedido o cliente"
          class="w-56 rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-xs text-slate-300 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
        />
        <select
          name="status"
          onchange="this.form.submit()"
          class="rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-xs	text-slate-300 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
        >
          <option value="">Todos los estados</option>
          @foreach ($statuses as $key => $label)
            <option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>
          @endforeach
        </select>
      </form>
    </div>

    <div class="space-y-4">
      @php
        $statusStyles = [
          'pendiente' => 'border-amber-400/30 bg-amber-400/10 text-amber-200',
          'pagado' => 'border-emerald-400/30 bg-emerald-400/10 text-emerald-200',
          'en_produccion' => 'border-blue-400/30 bg-blue-400/10 text-blue-200',
          'enviado' => 'border-sky-400/30 bg-sky-400/10 text-sky-200',
          'cancelado' => 'border-rose-400/30 bg-rose-400/10 text-rose-200',
        ];
      @endphp

      @forelse ($orders as $order)
        @php
          $statusClass = $statusStyles[$order->status] ?? 'border-slate-700 bg-slate-800/60 text-slate-200';
          $toggleStatusId = 'status-form-' . $order->id;
          $toggleNoteId = 'note-form-' . $order->id;
        @endphp
        <article class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6">
          <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
              <div class="flex items-center gap-3">
                <h2 class="text-lg font-semibold text-white">
                  <a href="{{ route('admin.orders.show', $order) }}" class="hover:text-blue-300">
                    {{ $order->order_number }}
                  </a>
                </h2>
                <span class="rounded-full border px-2 py-0.5 text-xs font-semibold {{ $statusClass }}">
                  {{ $order->status_label }}
                </span>
              </div>
              <p class="mt-1 text-xs text-slate-400">
                Cliente: {{ optional($order->user)->name ?? 'Invitado' }} &bull;
                Total: ${{ number_format((float) $order->total, 0, ',', '.') }} &bull;
                Ítems: {{ $order->items_count }}
              </p>
            </div>
            <div class="text-right text-xs text-slate-400">
              <p>Creado: {{ $order->created_at->translatedFormat('d M Y - H:i') }}</p>
              <p>Actualizado: {{ $order->updated_at->diffForHumans() }}</p>
            </div>
          </div>

          <div class="mt-5 flex flex-wrap items-center justify-between gap-3 text-xs text-slate-300">
            <div class="flex gap-3">
              <button type="button" data-toggle="{{ $toggleStatusId }}" class="font-semibold text-blue-300 transition hover:text-blue-200">
                Actualizar estado
              </button>
              <button type="button" data-toggle="{{ $toggleNoteId }}" class="font-semibold text-slate-300 transition hover:text-slate-100">
                Agregar nota
              </button>
              <a href="{{ route('admin.orders.show', $order) }}" class="font-semibold text-rose-200 hover:text-rose-100">
                Ver detalle
              </a>
            </div>
          </div>

          <div id="{{ $toggleStatusId }}" class="mt-4 hidden rounded-xl border border-slate-800/80 bg-slate-900/80 p-4">
            <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
              @csrf
              @method('PATCH')
              <div class="flex flex-wrap items-center gap-3">
                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Nuevo estado</label>
                <select name="status" class="rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-xs text-slate-200 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                  @foreach ($statuses as $key => $label)
                    <option value="{{ $key }}" @selected($order->status === $key)>{{ $label }}</option>
                  @endforeach
                </select>
              </div>
              <button type="submit" class="inline-flex items-center justify-center rounded-full bg-blue-500 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-blue-400">
                Guardar
              </button>
            </form>
          </div>

          <div id="{{ $toggleNoteId }}" class="mt-4 hidden rounded-xl border border-slate-800/80 bg-slate-900/80 p-4">
            <form method="POST" action="{{ route('admin.orders.notes.store', $order) }}" class="space-y-3">
              @csrf
              <label class="block text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">
                Nota interna
                <textarea
                  name="note"
                  rows="3"
                  class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-sm text-slate-200 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                  placeholder="Ej. Confirmar colores y embalaje personalizado..."
                ></textarea>
              </label>
              <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center justify-center rounded-full bg-slate-200 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-900 transition hover:bg-white">
                  Guardar nota
                </button>
              </div>
            </form>
          </div>
        </article>
      @empty
        <article class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6 text-sm text-slate-300">
          No hay pedidos con los filtros actuales.
        </article>
      @endforelse
    </div>

    {{ $orders->links() }}
  </section>

  <script>
    document.querySelectorAll('[data-toggle]').forEach((button) => {
      button.addEventListener('click', () => {
        const target = document.getElementById(button.dataset.toggle);
        if (target) {
          target.classList.toggle('hidden');
        }
      });
    });
  </script>
@endsection

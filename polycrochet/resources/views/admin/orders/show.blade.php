@extends('layouts.admin')
@section('title', 'Pedido ' . $order->order_number . ' | PolyCrochet')
@section('page_heading', 'Detalle del pedido')

@section('content')
  <section class="grid gap-6 lg:grid-cols-[1.6fr,1fr]">
    <article class="space-y-6 rounded-2xl border border-slate-800 bg-slate-900/70 p-6">
      @php
        $statusStyles = [
          'pendiente' => 'border-amber-400/30 bg-amber-400/10 text-amber-200',
          'pagado' => 'border-emerald-400/30 bg-emerald-400/10 text-emerald-200',
          'en_produccion' => 'border-blue-400/30 bg-blue-400/10 text-blue-200',
          'enviado' => 'border-sky-400/30 bg-sky-400/10 text-sky-200',
          'entregado' => 'border-emerald-400/30 bg-emerald-400/10 text-emerald-200',
          'cancelado' => 'border-rose-400/30 bg-rose-400/10 text-rose-200',
        ];
        $statusBadgeClass = $statusStyles[$order->status] ?? 'border-slate-700 bg-slate-800/60 text-slate-200';
      @endphp

      <header class="flex flex-wrap items-start justify-between gap-4">
        <div>
          <h1 class="text-2xl font-semibold text-white">Pedido {{ $order->order_number }}</h1>
          <p class="mt-1 text-sm text-slate-400">Creado {{ $order->created_at->translatedFormat('d \\d\\e F Y H:i') }}</p>
        </div>
        <span class="rounded-full border px-2 py-0.5 text-xs font-semibold {{ $statusBadgeClass }}">
          {{ $order->status_label }}
        </span>
      </header>

      <section class="rounded-xl border border-slate-800 bg-slate-900/60">
        <table class="min-w-full divide-y divide-slate-800 text-sm">
          <thead class="bg-slate-900/70 text-xs uppercase tracking-wide text-slate-400">
            <tr>
              <th class="px-4 py-3 text-left">Producto</th>
              <th class="px-4 py-3 text-center">Cant.</th>
              <th class="px-4 py-3 text-right">Precio</th>
              <th class="px-4 py-3 text-right">Subtotal</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800 text-slate-200">
            @foreach ($order->items as $item)
              <tr>
                <td class="px-4 py-3">
                  <div class="flex items-center gap-3">
                    <div class="h-10 w-10 flex-shrink-0 overflow-hidden rounded-lg border border-slate-800 bg-slate-800/80">
                      @if ($item->product?->primaryImage?->url)
                        <img src="{{ $item->product->primaryImage->url }}" alt="{{ $item->product_name }}" class="h-full w-full object-cover">
                      @else
                        <span class="flex h-full items-center justify-center text-xs text-slate-500">{{ mb_substr($item->product_name, 0, 1) }}</span>
                      @endif
                    </div>
                    <div>
                      <p class="text-sm font-semibold text-white">{{ $item->product_name }}</p>
                      @if ($item->product_sku)
                        <p class="text-xs text-slate-400">SKU: {{ $item->product_sku }}</p>
                      @endif
                    </div>
                  </div>
                </td>
                <td class="px-4 py-3 text-center">{{ $item->quantity }}</td>
                <td class="px-4 py-3 text-right">${{ number_format((float) $item->unit_price, 0, ',', '.') }}</td>
                <td class="px-4 py-3 text-right font-semibold text-white">${{ number_format((float) $item->line_total, 0, ',', '.') }}</td>
              </tr>
            @endforeach
          </tbody>
          <tfoot class="bg-slate-900/70 text-sm text-slate-200">
            <tr>
              <td colspan="3" class="px-4 py-3 text-right text-slate-400">Subtotal</td>
              <td class="px-4 py-3 text-right font-semibold text-white">${{ number_format((float) $order->items_total, 0, ',', '.') }}</td>
            </tr>
            <tr>
              <td colspan="3" class="px-4 py-3 text-right text-slate-400">Envío</td>
              <td class="px-4 py-3 text-right font-semibold text-white">${{ number_format((float) $order->shipping_total, 0, ',', '.') }}</td>
            </tr>
            <tr>
              <td colspan="3" class="px-4 py-3 text-right text-slate-400">Total pagado</td>
              <td class="px-4 py-3 text-right text-lg font-semibold text-emerald-300">${{ number_format((float) $order->total, 0, ',', '.') }}</td>
            </tr>
          </tfoot>
        </table>
      </section>

      <section class="space-y-4 rounded-xl border border-slate-800 bg-slate-900/60 p-5">
        <header class="flex items-center justify-between gap-4">
          <h2 class="text-lg font-semibold text-white">Actualizar estado</h2>
          <span class="text-xs text-slate-400">Último cambio {{ $order->updated_at->diffForHumans() }}</span>
        </header>
        <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
          @csrf
          @method('PATCH')
          <div class="flex items-center gap-3">
            <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Estado</label>
            <select name="status" class="rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-xs text-slate-200 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
              @foreach ($statuses as $key => $label)
                <option value="{{ $key }}" @selected($order->status === $key)>{{ $label }}</option>
              @endforeach
            </select>
          </div>
          <button type="submit" class="inline-flex items-center justify-center rounded-full bg-blue-500 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-blue-400">
            Guardar cambios
          </button>
        </form>
      </section>

      <section class="space-y-4 rounded-xl border border-slate-800 bg-slate-900/60 p-5">
        <header class="flex items-center justify-between gap-4">
          <h2 class="text-lg font-semibold text-white">Notas internas</h2>
          <span class="text-xs text-slate-400">Visibles solo para el equipo</span>
        </header>
        <form method="POST" action="{{ route('admin.orders.notes.store', $order) }}" class="space-y-3">
          @csrf
          <textarea
            name="note"
            rows="3"
            class="w-full rounded-lg border border-slate-700 bg-slate-900/80 px-3 py-2 text-sm text-slate-200 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            placeholder="Escribe un comentario interno sobre este pedido..."
          ></textarea>
          <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center justify-center rounded-full bg-slate-200 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-900 transition hover:bg-white">
              Agregar nota
            </button>
          </div>
        </form>

        <ul class="space-y-4 text-sm text-slate-300">
          @forelse ($order->notes as $note)
            <li class="rounded-xl border border-slate-800/60 bg-slate-900/80 p-4">
              <div class="flex items-center justify-between gap-3">
                <div>
                  <p class="text-sm font-semibold text-white">{{ $note->author?->name ?? 'Sistema' }}</p>
                  <p class="text-xs text-slate-400">{{ $note->created_at->translatedFormat('d M Y - H:i') }} ({{ $note->created_at->diffForHumans() }})</p>
                </div>
                <form method="POST" action="{{ route('admin.orders.notes.destroy', [$order, $note]) }}">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="text-xs font-semibold text-rose-300 hover:text-rose-200">Eliminar</button>
                </form>
              </div>
              <p class="mt-2 text-sm leading-relaxed text-slate-200">{{ $note->note }}</p>
            </li>
          @empty
            <li class="rounded-xl border border-slate-800/60 bg-slate-900/80 p-4 text-xs text-slate-400">
              Aún no registras notas para este pedido.
            </li>
          @endforelse
        </ul>
      </section>
    </article>

    <aside class="space-y-6 rounded-2xl border border-slate-800 bg-slate-900/70 p-6">
      <section>
        <h2 class="text-lg font-semibold text-white">Datos de envío</h2>
        <dl class="mt-4 space-y-3 text-sm text-slate-300">
          <div>
            <dt class="text-xs uppercase tracking-[0.3em] text-slate-500">Destinatario</dt>
            <dd class="mt-1 font-semibold text-white">{{ $order->shipping_name }}</dd>
          </div>
          <div>
            <dt class="text-xs uppercase tracking-[0.3em] text-slate-500">Dirección</dt>
            <dd class="mt-1">{{ $order->shipping_address ?: 'Sin dirección registrada' }}</dd>
            <dd>{{ $order->shipping_location }}</dd>
            @if ($order->shipping_data['reference'] ?? null)
              <dd class="text-xs text-slate-400">Referencia: {{ $order->shipping_data['reference'] }}</dd>
            @endif
          </div>
          <div>
            <dt class="text-xs uppercase tracking-[0.3em] text-slate-500">Contacto</dt>
            <dd class="mt-1">{{ $order->billing_data['email'] ?? $order->user?->email }}</dd>
            <dd>{{ $order->shipping_phone ?? 'Sin teléfono' }}</dd>
          </div>
        </dl>
      </section>

      <section>
        <h2 class="text-lg font-semibold text-white">Pagos</h2>
        <div class="mt-4 space-y-3 rounded-xl border border-slate-800 bg-slate-900/80 p-4 text-sm text-slate-300">
          <div class="flex items-center justify-between">
            <span>Método</span>
            <span class="font-semibold text-white">PayPal</span>
          </div>
          <div class="flex items-center justify-between">
            <span>Total (CLP)</span>
            <span class="font-semibold text-white">${{ number_format((float) $order->total, 0, ',', '.') }}</span>
          </div>
          @if ($conversion = data_get($order->metadata, 'paypal.currency'))
            <div class="flex items-center justify-between text-xs text-slate-400">
              <span>Total ({{ $conversion }})</span>
              <span>${{ number_format(data_get($order->metadata, 'paypal.capture.purchase_units.0.payments.captures.0.amount.value', 0), 2) }}</span>
            </div>
          @endif
          @if ($order->paid_at)
            <p class="text-xs text-emerald-300">Pagado el {{ $order->paid_at->translatedFormat('d \\d\\e F Y \\a \\l\\a\\s H:i') }}</p>
          @else
            <p class="text-xs text-amber-300">Pago pendiente de confirmación.</p>
          @endif
        </div>
      </section>
    </aside>
  </section>
@endsection

@php
  $formatCurrency = fn (float $value) => '$' . number_format($value, 0, ',', '.');
  $shippingName = $shipping['name'] ?? optional($order->user)->name;
  $customerEmail = $order->billing_data['email'] ?? $order->user?->email;
@endphp

<x-mail::message>
# Nuevo pedido confirmado

Se registró un nuevo pedido **{{ $order->order_number }}** y el pago fue confirmado correctamente.

<x-mail::panel>
**Cliente:** {{ $shippingName ?? 'Sin nombre' }}  
**Correo:** {{ $customerEmail ?? 'Sin correo registrado' }}  
**Teléfono:** {{ $order->billing_data['phone'] ?? $order->user?->phone ?? 'Sin teléfono' }}  
**Total pagado:** **{{ $formatCurrency((float) $order->total) }}**
</x-mail::panel>

## Detalle de productos
@foreach ($order->items as $item)
- {{ $item->product_name }} · Cantidad: {{ $item->quantity }} — {{ $formatCurrency((float) $item->line_total) }}
@endforeach

**Subtotal:** {{ $formatCurrency((float) $order->items_total) }}  
**Envío:** {{ $formatCurrency((float) $order->shipping_total) }}  
**Total:** {{ $formatCurrency((float) $order->total) }}

**Dirección de envío:**  
{{ collect([$shipping['street'] ?? null, $shipping['number'] ?? null, $shipping['apartment'] ?? null])->filter()->implode(' ') ?: 'Sin dirección registrada' }}  
{{ collect([$shipping['commune'] ?? null, $shipping['region'] ?? null])->filter()->implode(', ') }}

Puedes revisar el pedido completo desde el administrador.

<x-mail::button :url="route('admin.orders.show', ['order' => $order->id])">
Ver pedido en el panel
</x-mail::button>

— PolyCrochet Studio
</x-mail::message>

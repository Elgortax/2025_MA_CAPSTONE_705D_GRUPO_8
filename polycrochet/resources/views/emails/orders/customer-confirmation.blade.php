@php
  use Illuminate\Support\Str;

  $formatCurrency = fn (float $value) => '$' . number_format($value, 0, ',', '.');
  $shippingName = $shipping['name'] ?? optional($order->user)->name;
  $shippingLines = collect([
    $shipping['street'] ?? null,
    $shipping['number'] ?? null,
    $shipping['apartment'] ?? null,
  ])->filter()->implode(' ');
  $locationLine = collect([
    $shipping['commune'] ?? null,
    $shipping['region'] ?? null,
  ])->filter()->implode(', ');
@endphp

<x-mail::message>
# ¡Gracias por tu compra!

Hemos recibido el pedido **{{ $order->order_number }}** y ya comenzamos a prepararlo. A continuación, encontrarás un resumen de lo que solicitaste.

<x-mail::panel>
**Destinatario:** {{ $shippingName ?? 'Sin nombre de destinatario' }}  
**Dirección:** {{ $shippingLines ?: 'Sin dirección registrada' }} {{ $locationLine ? '(' . $locationLine . ')' : '' }}  
**Contacto:** {{ $order->billing_data['email'] ?? $order->user?->email }} {{ $order->billing_data['phone'] ? '· ' . $order->billing_data['phone'] : '' }}
</x-mail::panel>

## Resumen del pedido

@foreach ($order->items as $item)
- **{{ $item->product_name }}** · Cantidad: {{ $item->quantity }} — {{ $formatCurrency((float) $item->line_total) }}
@endforeach

**Subtotal:** {{ $formatCurrency((float) $order->items_total) }}  
**Envío:** {{ $formatCurrency((float) $order->shipping_total) }}  
**Total pagado:** **{{ $formatCurrency((float) $order->total) }}**

@if ($order->paid_at)
> Pago confirmado el {{ $order->paid_at->translatedFormat('d \\d\\e F Y \\a \\l\\a\\s H:i') }}
@endif

Si necesitas hacer cambios o tienes alguna consulta, responde a este correo o escríbenos a **soporte@polycrochet.cl**.

<x-mail::button :url="route('orders.history')">
Ver mis pedidos
</x-mail::button>

Gracias por confiar en PolyCrochet. Estamos felices de tejer algo especial para ti.

Saludos cordiales,  
**Equipo PolyCrochet**
</x-mail::message>

<x-mail::message>
# ¡Gracias por escribirnos!

Hola {{ $name }},

Recibimos tu mensaje y nuestro equipo lo revisará a la brevedad. Te responderemos a este mismo correo.

## Copia de tu mensaje
@component('mail::panel')
{{ $message }}
@endcomponent

Mientras tanto, puedes seguir explorando nuestras colecciones o revisar tu cuenta.

<x-mail::button :url="route('catalog')">
Ver catálogo
</x-mail::button>

¡Gracias por confiar en PolyCrochet!

— Equipo PolyCrochet
</x-mail::message>

<x-mail::message>
# Nuevo mensaje desde Conversemos tu idea

Recibiste un nuevo mensaje a través del formulario del sitio.

<x-mail::panel>
**Nombre:** {{ $name }}  
**Correo:** {{ $email }}
</x-mail::panel>

## Mensaje
@component('mail::panel')
{{ $message }}
@endcomponent

Puedes responder directamente a este correo para continuar la conversación.

— PolyCrochet Studio
</x-mail::message>

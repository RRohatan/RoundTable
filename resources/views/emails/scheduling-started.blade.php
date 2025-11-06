
<x-mail::message>
# ¡Agendamiento Abierto!

Hola **{{ $participant->name }}**,

Nos complace informarte que la etapa de agendamiento para el evento **{{ $event->name }}** ya está activa.

Ya puedes ingresar a la plataforma para ver el directorio de participantes y empezar a solicitar tus reuniones.

<x-mail::button :url="route('dashboard')">
Ir al Panel
</x-mail::button>

¡Gracias por participar!<br>
{{ config('app.name') }}
</x-mail::message>


<x-mail::message>
# ¡Nueva Solicitud de Reunión!

Has recibido una nueva solicitud de reunión de **{{ $requester->participantProfile->company_name }}** (Contacto: {{ $requester->name }}) para el evento **{{ $event->name }}**.

Puedes revisar, aceptar o rechazar esta solicitud en tu panel de gestión de reuniones.

<x-mail::button :url="route('participant.meetings.index')">
Gestionar Solicitudes
</x-mail::button>

¡Gracias!<br>
{{ config('app.name') }}
</x-mail::message>


<x-mail::message>
# ¡Tu Reunión ha sido Confirmada!

Hola,

Tu solicitud de reunión con **{{ $receiver->participantProfile->company_name }}** (Contacto: {{ $receiver->name }}) para el evento **{{ $event->name }}** ha sido aceptada.

Tu reunión ha sido agendada:

**Hora:** {{ $meeting->scheduled_start_time->format('h:i A') }}
**Mesa:** {{ $meeting->assigned_table }}

Puedes revisar tu agenda completa en el panel.

<x-mail::button :url="route('participant.meetings.myAgenda')">
Ver Mi Agenda
</x-mail::button>

¡Gracias!<br>
{{ config('app.name') }}
</x-mail::message>

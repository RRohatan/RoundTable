
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mi Agenda del Día
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <h3 class="text-2xl font-semibold mb-6">Mis Reuniones Programadas</h3>

                    <div class="space-y-4">
                        @forelse ($myMeetings as $meeting)
                            @php
                                $myRegistrationId = Auth::user()->registrations
                                                        ->where('event_id', $meeting->event_id)
                                                        ->first()->id;

                                $otherParticipant = $meeting->requester_registration_id == $myRegistrationId
                                                    ? $meeting->receiver
                                                    : $meeting->requester;
                            @endphp

                            <div class="p-4 border rounded-lg shadow-sm">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="font-bold text-lg text-indigo-600">{{ $meeting->scheduled_start_time->format('h:i A') }}</span>
                                        <span class="mx-2">|</span>
                                        <span class="font-semibold text-gray-700">{{ $meeting->assigned_table }}</span>

                                        <h4 class="text-xl font-bold mt-1">{{ $otherParticipant->participant->participantProfile->company_name }}</h4>
                                        <p class="text-gray-600">
                                            Evento: {{ $meeting->event->name }}
                                        </p>
                                    </div>

                                    <div class="flex flex-col space-y-2 text-right">

                                        @if ($meeting->status == 'confirmed')
                                            <form method="POST" action="{{ route('participant.meetings.start', $meeting) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-green-500 text-white rounded-md text-xs uppercase font-semibold">Iniciar</button>
                                            </form>
                                            <form method="POST" action="{{ route('participant.meetings.cancel', $meeting) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-500 text-white rounded-md text-xs uppercase font-semibold" onclick="return confirm('¿Estás seguro de cancelar esta reunión?')">Cancelar</button>
                                            </form>

                                        @elseif ($meeting->status == 'in_progress')
                                            <form method="POST" action="{{ route('participant.meetings.complete', $meeting) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-blue-500 text-white rounded-md text-xs uppercase font-semibold">Finalizar Reunión</g-button>
                                            </form>

                                        @elseif ($meeting->status == 'completed')
                                            <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md text-xs uppercase font-semibold">Completada</span>
                                            @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">No tienes reuniones programadas en tu agenda.</p>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

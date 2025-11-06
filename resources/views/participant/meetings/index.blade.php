
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Reuniones
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

                    <h3 class="text-2xl font-semibold mb-6">Solicitudes Pendientes</h3>
                    <div class="space-y-4">
                        @forelse ($pendingMeetings as $meeting)
                            <div class="p-4 border rounded-lg shadow-sm flex justify-between items-center">
                                <div>
                                    <h4 class="text-xl font-bold">{{ $meeting->requester->participant->participantProfile->company_name }}</h4>
                                    <p class="text-gray-600">
                                        Evento: {{ $meeting->event->name }}
                                    </p>
                                    <p class="text-gray-700 mt-2">
                                        <strong>Solicita:</strong> {{ $meeting->requester->role_description }}
                                    </p>
                                </div>

                                <div class="flex space-x-2">
                                    <form method="POST" action="{{ route('participant.meetings.confirm', $meeting) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type"submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">
                                            Aceptar
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('participant.meetings.reject', $meeting) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type"submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
                                            Rechazar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">No tienes solicitudes de reunión pendientes.</p>
                        @endforelse
                    </div>

                    <h3 class="text-2xl font-semibold mt-12 mb-6">Mis Reuniones Confirmadas (Agenda)</h3>
                   <div class="space-y-4">

                     @forelse ($confirmedMeetings as $meeting)
                    <div class="p-4 border border-green-300 bg-green-50 rounded-lg shadow-sm">
                     @php
                      // Averiguamos cuál es el ID de nuestra inscripción
                     $myRegistrationId = Auth::user()->registrations
                                        ->where('event_id', $meeting->event_id)
                                        ->first()->id;

                           $otherParticipant = $meeting->requester_registration_id == $myRegistrationId
                                    ? $meeting->receiver
                                    : $meeting->requester;
                               @endphp

                               <h4 class="text-xl font-bold">{{ $otherParticipant->participant->participantProfile->company_name }}</h4>
                               <p class="text-gray-600">
                                   Evento: {{ $meeting->event->name }}
                               </p>
                               <p class="text-gray-800 font-semibold mt-2">
                                   <span class="text-green-700">
                                       HORA: {{ $meeting->scheduled_start_time->format('h:i A') }}
                                   </span>
                                   <span class="mx-2">|</span>
                                   <span class="text-blue-700">
                                       {{ $meeting->assigned_table }}
                                   </span>
                               </p>
                        </div>
                    @empty
                        <p class="text-gray-500">No tienes reuniones confirmadas en tu agenda.</p>
                    @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

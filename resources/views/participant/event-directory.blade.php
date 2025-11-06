<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $event->name }}
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
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif
                    <h3 class="text-2xl font-semibold mb-6">{{ $directoryTitle }}</h3>

                    <div class="space-y-4">

                        @forelse ($participants as $registration)
                            <div class="p-4 border rounded-lg shadow-sm">

                                <div class="flex justify-between items-start">

                                    <div>
                                        <h4 class="text-xl font-bold">{{ $registration->participant->participantProfile->company_name }}</h4>
                                        <p class="text-gray-600">Sector: {{ $registration->participant->participantProfile->sector }}</p>
                                        <p class="text-gray-600">Contacto: {{ $registration->participant->name }}</p>
                                    </div>

                                    <div class="text-right">
                                        <form method="POST" action="{{ route('participant.meeting.store') }}">
                                            @csrf
                                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                                            <input type="hidden" name="receiver_registration_id" value="{{ $registration->id }}">

                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">
                                                Solicitar Reunión
                                            </button>
                                        </form>
                                    </div>
                                    </div>
                                <div class="mt-4 border-t pt-2">
                                    <p class="font-semibold">
                                        @if($registration->role == 'supplier')
                                            Ofrece:
                                        @else
                                            Busca:
                                        @endif
                                    </p>
                                    <p class="text-gray-700">{{ $registration->role_description }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">No hay participantes que coincidan con tu búsqueda por el momento.</p>
                        @endforelse </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

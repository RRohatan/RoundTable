<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Panel del Organizador
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

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-semibold">Mis Eventos Creados</h3>
                        <a href="{{ route('organizer.events.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            + Crear Nuevo Evento
                        </a>
                    </div>

                    @forelse ($events as $event)
                        <div class="mb-4 p-4 border rounded-lg">
                           <a href="{{ route('organizer.events.show', $event) }}">
                               <h4 class="text-xl font-bold text-blue-600 hover:underline">{{ $event->name }}</h4>
                           </a>
                            <p class="text-gray-600">
                                {{ \Carbon\Carbon::parse($event->date)->translatedFormat('l, j F Y') }} en {{ $event->location }}
                            </p>
                            <div class="mt-4 pt-4 border-t flex justify-between items-center">

    <div class="w-1/2">
        <span class="text-sm font-medium">Link de Registro:</span>
        <input type="text" readonly value="{{ route('event.register.form', ['link' => $event->registration_link]) }}" class="text-sm text-gray-600 bg-gray-100 border-none rounded p-1 w-full" onclick="this.select()">
    </div>

    <div class="w-1/2 text-right">
        <form method="POST" action="{{ route('organizer.events.updateStatus', $event) }}">
            @csrf
            @method('PATCH')

            <span class="text-sm font-medium mr-2">Estado: <strong>{{ $event->status }}</strong></span>

            @if($event->status == 'RegistrationOpen')
                <input type="hidden" name="status" value="SchedulingActive">
                <x-primary-button class="bg-blue-600 hover:bg-blue-500">
                    Iniciar Agendamiento
                </x-primary-button>

            @elseif($event->status == 'SchedulingActive')
                <input type="hidden" name="status" value="InProgress">
                <x-primary-button class="bg-green-600 hover:bg-green-500">
                    Iniciar Evento
                </x-primary-button>
            @endif
        </form>
    </div>
</div>
                        </div>
                    @empty
                        <p class="text-gray-500">
                            Aún no has creado ningún evento.
                        </p>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

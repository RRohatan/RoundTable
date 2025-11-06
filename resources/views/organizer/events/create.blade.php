<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Nuevo Evento (Rueda de Negocios)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                            <strong>¡Ups! Hubo algunos problemas:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif  <form method="POST" action="{{ route('organizer.events.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="name" value="Nombre del Evento" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="date" value="Fecha del Evento" />
                            <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="old('date')" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="location" value="Lugar" />
                            <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location')" required />
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="start_time" value="Hora de Inicio" />
                                <x-text-input id="start_time" class="block mt-1 w-full" type="time" name="start_time" :value="old('start_time')" required />
                            </div>
                            <div>
                                <x-input-label for="end_time" value="Hora de Fin" />
                                <x-text-input id="end_time" class="block mt-1 w-full" type="time" name="end_time" :value="old('end_time')" required />
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="supplier_limit" value="Cupo Máximo de Oferentes" />
                                <x-text-input id="supplier_limit" class="block mt-1 w-full" type="number" name="supplier_limit" :value="old('supplier_limit')" required />
                            </div>
                            <div>
                                <x-input-label for="meeting_duration_minutes" value="Duración de Cita (minutos)" />
                                <x-text-input id="meeting_duration_minutes" class="block mt-1 w-full" type="number" name="meeting_duration_minutes" :value="old('meeting_duration_minutes')" required />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="registration_deadline" value="Fecha Límite de Inscripción" />
                            <x-text-input id="registration_deadline" class="block mt-1 w-full" type="datetime-local" name="registration_deadline" :value="old('registration_deadline')" required />
                            <p class="text-sm text-gray-600 mt-1">La fecha límite debe ser anterior a la fecha del evento.</p>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ms-4">
                                Crear Evento
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> ```

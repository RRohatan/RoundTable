<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Panel del Participante
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                   <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 mt-4">
                        <a href="{{ route('participant.meetings.index') }}"
                           class="w-full sm:w-auto px-4 py-2 text-sm md:text-base font-semibold rounded-md text-white bg-blue-600 hover:bg-blue-700 text-center">
                            VER SOLICITUDES DE REUNIÓN
                        </a>
                        <a href="{{ route('participant.meetings.myAgenda') }}"
                           class="w-full sm:w-auto px-4 py-2 text-sm md:text-base font-semibold rounded-md text-white bg-green-600 hover:bg-green-700 text-center">
                            MI AGENDA DEL DÍA
                        </a>
                    </div>

                    @forelse ($registrations as $registration)
                        <div class="mb-4 p-4 border rounded-lg flex justify-between items-center">
                            <div>
                                <h4 class="text-xl font-bold">{{ $registration->event->name }}</h4>
                                <p class="text-gray-600">
                                    {{ \Carbon\Carbon::parse($registration->event->date)->translatedFormat('l, j F Y') }}
                                    en {{ $registration->event->location }}
                                </p>
                                <p class="mt-2">
                                    Tu rol:
                                    @if($registration->role == 'supplier')
                                        <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">
                                            Oferente
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                            Demandante
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <div>
                            <a href="{{ route('participant.event.directory', ['event' => $registration->event_id]) }}"
   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700 transition ease-in-out duration-150">

    <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
      <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
      <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.18l.88-1.47a1.65 1.65 0 012.68 0l1.55 2.58A1.65 1.65 0 007.4 11.2l.92.5a.65.65 0 01.01.01l.01.01a.65.65 0 00.9.01l.01-.01.92-.5a1.65 1.65 0 001.61-1.6l1.55-2.58a1.65 1.65 0 012.68 0l.88 1.47a1.651 1.651 0 010 1.18l-.88 1.47a1.65 1.65 0 01-2.68 0l-1.55-2.58A1.65 1.65 0 0012.6 8.8l-.92-.5a.65.65 0 01-.01-.01l-.01-.01a.65.65 0 00-.9-.01l-.01.01-.92.5a1.65 1.65 0 00-1.61 1.6L4.62 12.1a1.65 1.65 0 01-2.68 0L.664 10.59z" clip-rule="evenodd" />
    </svg>

    Ver Directorio
</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">
                            Aún no te has inscrito en ningún evento.
                        </p>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

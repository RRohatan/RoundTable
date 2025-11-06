
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard del Evento: {{ $event->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-2xl font-semibold mb-4">Inscripciones</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        <div class="p-4 bg-blue-100 border border-blue-200 rounded-lg shadow">
                            <div class="text-3xl font-bold">{{ $supplierCount + $buyerCount }}</div>
                            <div class="text-gray-600">Total Inscritos</div>
                        </div>
                        <div class="p-4 bg-gray-100 rounded-lg shadow">
                            <div class="text-3xl font-bold">{{ $supplierCount }}</div>
                            <div class="text-gray-600">Oferentes (de {{ $event->supplier_limit }})</div>
                        </div>
                        <div class="p-4 bg-gray-100 rounded-lg shadow">
                            <div class="text-3xl font-bold">{{ $buyerCount }}</div>
                            <div class="text-gray-600">Demandantes</div>
                        </div>
                    </div>

                    <h3 class="text-2xl font-semibold mb-4">Reuniones</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        <div class="p-4 bg-green-100 border border-green-200 rounded-lg shadow">
                            <div class="text-3xl font-bold">{{ $confirmedMeetings }}</div>
                            <div class="text-gray-600">Reuniones Programadas</div>
                        </div>
                        <div class="p-4 bg-gray-100 rounded-lg shadow">
                            <div class="text-3xl font-bold">{{ $completedMeetings }}</div>
                            <div class="text-gray-600">Completadas</div>
                        </div>
                        <div class="p-4 bg-yellow-100 border border-yellow-200 rounded-lg shadow">
                            <div class="text-3xl font-bold">{{ $pendingMeetings }}</div>
                            <div class="text-gray-600">Solicitudes Pendientes</div>
                        </div>
                    </div>

                    <h3 class="text-2xl font-semibold mb-4">Resultados (Post-Encuesta)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="p-4 bg-indigo-100 border border-indigo-200 rounded-lg shadow">
                            <div class="text-3xl font-bold">{{ $surveyResults->get('SaleOrPurchase', 0) }}</div>
                            <div class="text-gray-600">Ventas/Compras Concretadas</div>
                        </div>
                        <div class="p-4 bg-gray-100 rounded-lg shadow">
                            <div class="text-3xl font-bold">{{ $surveyResults->get('Alliance', 0) }}</div>
                            <div class="text-gray-600">Alianzas Establecidas</div>
                        </div>
                        <div class="p-4 bg-gray-100 rounded-lg shadow">
                            <div class="text-3xl font-bold">{{ $surveyResults->get('FollowUp', 0) }}</div>
                            <div class="text-gray-600">En Seguimiento</div>
                        </div>
                        <div class="p-4 bg-gray-100 rounded-lg shadow">
                            <div class="text-3xl font-bold">{{ $surveyResults->get('None', 0) }}</div>
                            <div class="text-gray-600">Sin Acuerdo</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

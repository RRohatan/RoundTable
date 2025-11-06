
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Encuesta Post-Reunión
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-xl font-semibold mb-2">Reunión Completada</h3>
                    <p class="text-gray-600 mb-6">Por favor, indícanos el resultado principal de esta reunión.</p>

                    <form method="POST" action="{{ route('participant.survey.store', $meeting) }}">
                        @csrf

                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input id="result_sale" name="result" type="radio" value="SaleOrPurchase" required class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                <label for="result_sale" class="ml-3 block text-sm font-medium text-gray-700">
                                    Se concretó una Venta/Compra
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="result_alliance" name="result" type="radio" value="Alliance" required class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                <label for="result_alliance" class="ml-3 block text-sm font-medium text-gray-700">
                                    Se estableció una Alianza
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="result_followup" name="result" type="radio" value="FollowUp" required class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                <label for="result_followup" class="ml-3 block text-sm font-medium text-gray-700">
                                    Seguimos en contacto (Negociación en proceso)
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="result_none" name="result" type="radio" value="None" required class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                <label for="result_none" class="ml-3 block text-sm font-medium text-gray-700">
                                    Sin acuerdo
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <x-primary-button>
                                Enviar Respuesta
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

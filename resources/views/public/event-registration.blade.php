
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción: {{ $event->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto max-w-2xl py-12 px-4">

        <div class="bg-white p-8 rounded-lg shadow-md">


            <h1 class="text-3xl font-bold mb-2">Inscripción a:</h1>
            <h2 class="text-2xl text-gray-700 mb-6">{{ $event->name }}</h2>

            <p class="text-gray-600 mb-2"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($event->date)->translatedFormat('l, j F Y') }}</p>
            <p class="text-gray-600 mb-6"><strong>Lugar:</strong> {{ $event->location }}</p>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    <strong>¡Ups! Hubo algunos problemas:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('event.register.store', ['link' => $event->registration_link]) }}" enctype="multipart/form-data">
                @csrf

                <h3 class="text-xl font-semibold border-t pt-6 mt-6 mb-4">Datos del Representante y Cuenta</h3>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre Completo (Representante)</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="mt-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico (Será tu usuario)</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                        <input type="password" name="password" id="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <h3 class="text-xl font-semibold border-t pt-6 mt-6 mb-4">Datos de la Empresa</h3>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700">Nombre de la Empresa</label>
                        <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="nit" class="block text-sm font-medium text-gray-700">NIT</label>
                        <input type="text" name="nit" id="nit" value="{{ old('nit') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono de Contacto</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="sector" class="block text-sm font-medium text-gray-700">Sector Productivo</label>
                        <input type="text" name="sector" id="sector" value="{{ old('sector') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

       <h3 class="text-xl font-semibold border-t pt-6 mt-6 mb-4">Participación en el Evento</h3>

<div class="mt-4">
    <label class="block text-sm font-medium text-gray-700">¿Cómo participas?</label>
    <div class="mt-2 space-y-2">
        <div class="flex items-center">
            <input id="role_buyer" name="role" type="radio" value="buyer" {{ old('role') == 'buyer' ? 'checked' : '' }} required class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
            <label for="role_buyer" class="ml-3 block text-sm font-medium text-gray-700">Demandante (Busco productos/servicios)</label>
        </div>
        <div class="flex items-center">
            <input id="role_supplier" name="role" type="radio" value="supplier" {{ old('role') == 'supplier' ? 'checked' : '' }} required class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
            <label for="role_supplier" class="ml-3 block text-sm font-medium text-gray-700">Oferente (Ofrezco productos/servicios)</label>
        </div>
    </div>
</div>

<div class="mt-4">
    <label for="role_description" class="block text-sm font-medium text-gray-700">Describe brevemente qué buscas o qué ofreces:</label>
    <textarea name="role_description" id="role_description" rows="3" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('role_description') }}</textarea>
</div>

<h3 class="text-xl font-semibold border-t pt-6 mt-6 mb-4">Portafolio (Solo Oferente)</h3>

<div id="portfolio_wrapper" class="mt-4">
    <label for="portfolio_url" class="block text-sm font-medium text-gray-700">Portafolio (PDF, JPG, PNG - Max 2MB)</label>
    <input type="file" name="portfolio_url" id="portfolio_url" class="mt-1 block w-full text-sm text-gray-500
        file:mr-4 file:py-2 file:px-4
        file:rounded-md file:border-0
        file:text-sm file:font-semibold
        file:bg-indigo-50 file:text-indigo-700
        hover:file:bg-indigo-100">
</div>


                <div class="flex items-center justify-end mt-8">
                    <button type="submit" class="w-full flex justify-center py-3 px-6 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Completar Inscripción
                    </button>
                </div>
            </form>

        </div>
    </div>


    <script>
    const buyer = document.getElementById('role_buyer');
    const supplier = document.getElementById('role_supplier');
    const portfolioWrapper = document.getElementById('portfolio_wrapper');
    const portfolioInput = document.getElementById('portfolio_url');

    function checkRole() {
        if (buyer.checked) {
            portfolioWrapper.style.display = 'none';
            portfolioInput.disabled = true;
        } else {
            portfolioWrapper.style.display = 'block';
            portfolioInput.disabled = false;
        }
    }

    buyer.addEventListener('change', checkRole);
    supplier.addEventListener('change', checkRole);

    // Ejecutamos una vez por si old('role') venía seleccionado
    checkRole();
</script>

</body>


</html>

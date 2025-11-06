<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Plataforma de Ruedas de Negocio</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">

        <div x-data="{ view: 'login' }" class="min-h-screen flex flex-col lg:flex-row">

         <div class="w-full lg:w-1/2 min-h-[300px] lg:min-h-screen bg-cover bg-center" style="background-image: url('{{ asset('images/cosmox-bg.jpg') }}');">
    <div class="w-full h-full flex flex-col justify-center items-center bg-black bg-opacity-60 p-12 text-center">

        <a href="/">
            {{-- Usamos directamente la etiqueta img para especificar el logo blanco --}}
            <img src="{{ asset('image/LOGO cosmox2.svg') }}" alt="Logo de Mi Empresa" class="w-56 h-56 object-contain" />
            {{-- Asegúrate que 'logo-white.svg' sea el nombre de tu archivo de logo blanco --}}
        </a>

        {{-- Aquí cambiamos el texto "Laravel" --}}
        <h1 class="text-3xl lg:text-4xl font-bold text-white mt-4">
        Business Roundtable
        </h1>

        <p class="text-lg text-gray-200 mt-2">
            Tu plataforma para conectar negocios.
        </p>
    </div>
</div>

            <div class="w-full lg:w-1/2 min-h-screen flex flex-col justify-center items-center p-6 sm:p-12 bg-gray-100">

                <div class="w-full max-w-md">

                    <div class="flex border-b border-gray-300 mb-6">
                        <button
                            @click="view = 'login'"
                            :class="{ 'border-indigo-600 text-indigo-600': view === 'login', 'border-transparent text-gray-500': view !== 'login' }"
                            class="flex-1 py-3 px-4 text-center font-semibold border-b-2 focus:outline-none transition-colors duration-300">
                            Iniciar Sesión
                        </button>
                        <button
                            @click="view = 'register'"
                            :class="{ 'border-indigo-600 text-indigo-600': view === 'register', 'border-transparent text-gray-500': view !== 'register' }"
                            class="flex-1 py-3 px-4 text-center font-semibold border-b-2 focus:outline-none transition-colors duration-300">
                            Registrar Empresa (Organizador)
                        </button>
                    </div>

                    <div x-show="view === 'login'">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Bienvenido de nuevo</h2>

                        @if (session('status'))
                            <div class="mb-4 font-medium text-sm text-green-600">
                                {{ session('status') }}
                            </div>
                        @endif

                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div>
                                <x-input-label for="email" value="Correo Electrónico" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="password" value="Contraseña" />
                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-between mt-4">
                                <label for="remember_me" class="inline-flex items-center">
                                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                                    <span class="ms-2 text-sm text-gray-600">Recordarme</span>
                                </label>
                                @if (Route::has('password.request'))
                                    <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                                        ¿Olvidaste tu contraseña?
                                    </a>
                                @endif
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button class="w-full justify-center text-lg">
                                    Acceder
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                    <div x-show="view === 'register'" style="display: none;">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Crear Cuenta de Organizador</h2>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div>
                                <x-input-label for="name" value="Nombre de la Empresa" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="email_reg" value="Correo Electrónico" />
                                <x-text-input id="email_reg" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="password_reg" value="Contraseña" />
                                <x-text-input id="password_reg" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="password_confirmation" value="Confirmar Contraseña" />
                                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button class="w-full justify-center text-lg">
                                    Registrarme
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

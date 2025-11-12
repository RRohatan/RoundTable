<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\ParticipantProfile;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // Para registrar errores
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class EventRegistrationController extends Controller
{
    /**
     * Muestra el formulario de inscripción para un evento específico.
     */
    public function showRegistrationForm(string $link)
    {
        // 1. Encontrar el evento usando el link
        $event = Event::where('registration_link', $link)->firstOrFail();

        // 2. Verificar si el evento sigue abierto
        if ($event->status !== 'RegistrationOpen' || now()->gt($event->registration_deadline)) {
            // Aquí podrías retornar una vista de "evento cerrado"
            abort(404, 'Las inscripciones para este evento están cerradas.');
        }

        // 3. Pasar el evento a la vista
        return view('public.event-registration', [
            'event' => $event
        ]);
    }

    /**
     * Procesa y guarda la nueva inscripción.
     */
    public function storeRegistration(Request $request, string $link)
    {
        // 1. Encontrar el evento
        $event = Event::where('registration_link', $link)->firstOrFail();

        // 2. Validar que el evento siga abierto
        if ($event->status !== 'RegistrationOpen' || now()->gt($event->registration_deadline)) {
            return back()->withErrors(['error' => 'Las inscripciones para este evento están cerradas.']);
        }

        // 3. Validación de todos los datos del formulario
        $validatedData = $request->validate([
            // Datos del Usuario
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => ['required', 'confirmed', Password::min(8)],

            // Datos del Perfil
            'company_name' => 'required|string|max:255',
            'nit' => 'required|string|max:255|unique:participant_profiles,nit',
            'phone' => 'required|string|max:20',
            'sector' => 'required|string|max:255',
            'portfolio_url' => 'nullable|file|mimes:pdf,jpg,png|max:10240', // PDF o Imagen, max 2MB

            // Datos de la Inscripción
            'role' => 'required|in:supplier,buyer', // 'oferente' o 'demandante'
            'role_description' => 'required|string|min:10',
        ]);

        // 4. Lógica de Cupos (Solo para Oferentes/Suppliers)
        if ($validatedData['role'] === 'supplier') {
            $supplierCount = $event->registrations()->where('role', 'supplier')->count();
            if ($supplierCount >= $event->supplier_limit) {
                return back()->withErrors(['role' => 'Lo sentimos, el cupo para oferentes está lleno.']);
            }
        }

        // 5. Manejo del archivo (Portafolio)
        $portfolioPath = null;
        if ($request->hasFile('portfolio_url')) {
            // Guarda el archivo en 'storage/app/public/portfolios'
            // Asegúrate de correr 'php artisan storage:link'
            $portfolioPath = $request->file('portfolio_url')->store('portfolios', 'public');
        }

        // 6. Transacción de Base de Datos
        // Usamos una transacción para que, si algo falla, todo se revierta.
        try {
            DB::beginTransaction();

            // 6.1. Buscar o Crear el Usuario (User)
            $user = User::where('email', $validatedData['email'])->first();

            if (!$user) {
                // Si el usuario no existe, lo creamos
                $user = User::create([
                    'name' => $validatedData['name'],
                    'email' => $validatedData['email'],
                    'password' => Hash::make($validatedData['password']),
                    'role' => 'participant', // Rol de plataforma
                ]);
            }

            // 6.2. Crear el Perfil (ParticipantProfile)
            // Asumimos que un usuario solo tiene un perfil, incluso si el NIT ya existía.
            // La validación 'unique:participant_profiles' previene esto.
            ParticipantProfile::create([
                'user_id' => $user->id,
                'company_name' => $validatedData['company_name'],
                'nit' => $validatedData['nit'],
                'phone' => $validatedData['phone'],
                'sector' => $validatedData['sector'],
                'portfolio_url' => $portfolioPath,
            ]);

            // 6.3. Crear la Inscripción (Registration)
            Registration::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'role' => $validatedData['role'], // Rol del evento
                'role_description' => $validatedData['role_description'],
            ]);

            DB::commit(); // Todo salió bien, confirmar cambios

            // 7. (Opcional pero recomendado) Enviar email de confirmación
            // Mail::to($user->email)->send(new RegistrationSuccessMail($event));

        } catch (\Exception $e) {
            DB::rollBack(); // Algo falló, revertir
            Log::error('Error en registro: ' . $e->getMessage()); // Registrar el error

            // Si el error es por NIT duplicado (que ya validamos, pero por si acaso)
            if (Str::contains($e->getMessage(), 'Duplicate entry') && Str::contains($e->getMessage(), 'nit')) {
                 return back()->withInput()->withErrors(['nit' => 'El NIT ingresado ya está registrado.']);
            }

            return back()->withInput()->withErrors(['error' => 'Ocurrió un error inesperado durante el registro. Por favor, inténtelo de nuevo.']);
        }

        // 8. Redirigir a una página de éxito
        // (Crearemos esta vista en el paso 5)
        return redirect()->route('login')->with('status', '¡Registro exitoso! Ya puedes iniciar sesión.');
    }
}

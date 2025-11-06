<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OrganizerProfile; 
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request) // <-- ELIMINAMOS :RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // --- 3. INICIAMOS UNA TRANSACCIÓN ---
        // Esto asegura que si algo falla, no se crea nada.
        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name, // Este será el "Nombre de la Empresa"
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'organizer', // <-- 4. ASIGNAR ROL DE ORGANIZADOR
            ]);

            // 5. CREAR EL PERFIL DE ORGANIZADOR ASOCIADO
            OrganizerProfile::create([
                'user_id' => $user->id,
                // El logo se puede añadir después en la vista de "Perfil"
            ]);

            DB::commit(); // Todo salió bien, confirmar cambios

        } catch (\Exception $e) {
            DB::rollBack(); // Algo falló, revertir
            // (Opcional) Registrar el error
            // Log::error('Fallo en registro de organizador: ' . $e->getMessage());
            return back()->withInput()->withErrors(['email' => 'No se pudo completar el registro. Intente de nuevo.']);
        }
        // --- FIN DE LA TRANSACCIÓN ---

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}

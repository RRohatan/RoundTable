<?php

namespace Database\Seeders;

use App\Models\OrganizerProfile;
use App\Models\ParticipantProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- 1. CREAR EL ORGANIZADOR ---
        $organizer = User::create([
            'name' => 'Admin Organizador',
            'email' => 'organizador@mail.com',
            'password' => Hash::make('12345678'),
            'role' => 'organizer',
        ]);

        // Crear su perfil de organizador
        OrganizerProfile::create([
            'user_id' => $organizer->id,
            'logo_url' => 'https://via.placeholder.com/150'
        ]);


        // --- 2. CREAR PARTICIPANTES (OFERENTES) ---
        User::factory(10)->create([
            'role' => 'participant'
        ])->each(function ($user) {
            ParticipantProfile::factory()->create([
                'user_id' => $user->id,
            ]);

            // Nota: Aquí creamos el perfil. La inscripción
            // como 'supplier' se haría en el EventSeeder.
        });

        // --- 3. CREAR PARTICIPANTES (DEMANDANTES) ---
         User::factory(10)->create([
            'role' => 'participant'
        ])->each(function ($user) {
            ParticipantProfile::factory()->create([
                'user_id' => $user->id,
            ]);
        });


        // --- 4. CREAR UN PARTICIPANTE DE PRUEBA ---
        // (Para que puedas iniciar sesión fácilmente)
        $participant = User::create([
            'name' => 'Participante Prueba',
            'email' => 'participante@mail.com',
            'password' => Hash::make('12345678'),
            'role' => 'participant',
        ]);

        ParticipantProfile::factory()->create([
            'user_id' => $participant->id,
            'company_name' => 'Empresa de Prueba S.A.S'
        ]);
    }
}

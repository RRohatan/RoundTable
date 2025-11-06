<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Encontrar al organizador que creamos
        $organizer = User::where('role', 'organizer')->first();

        // 2. Crear el Evento
        $event = Event::create([
            'user_id' => $organizer->id,
            'name' => 'Rueda de Negocios Tech 2025',
            'date' => '2025-12-15',
            'location' => 'Centro de Convenciones',
            'start_time' => '08:00:00',
            'end_time' => '18:00:00',
            'meeting_duration_minutes' => 20,
            'registration_link' => Str::uuid(), // Genera un link único
            'supplier_limit' => 50,
            'registration_deadline' => '2025-12-01 23:59:59',
            'status' => 'RegistrationOpen',
        ]);

        // 3. Inscribir a los participantes que creamos

        // --- ESTA ES LA PARTE CORREGIDA ---

        // Primero, obtenemos TODOS los participantes de prueba
        $participants = User::where('role', 'participant')
            ->where('email', '!=', 'participante@mail.com')
            ->orderBy('id') // Aseguramos el orden
            ->get();

        // Dividimos la colección de forma segura
        $suppliers = $participants->slice(0, 10);
        $buyers = $participants->slice(10, 10);

        // Inscribir a los Oferentes ('supplier')
        foreach ($suppliers as $supplier) {
            Registration::create([
                'user_id' => $supplier->id,
                'event_id' => $event->id,
                'role' => 'supplier', // Oferente
                'role_description' => 'Ofrecemos soluciones de software avanzado.'
            ]);
        }

        // Inscribir a los Demandantes ('buyer')
        foreach ($buyers as $buyer) {
            Registration::create([
                'user_id' => $buyer->id,
                'event_id' => $event->id,
                'role' => 'buyer', // Demandante
                'role_description' => 'Buscamos proveedores de tecnología.'
            ]);
        }

        // --- FIN DE LA CORRECCIÓN ---


        // Inscribir a nuestro usuario de prueba
        $testParticipant = User::where('email', 'participante@mail.com')->first();
        if ($testParticipant) {
             Registration::create([
                'user_id' => $testParticipant->id,
                'event_id' => $event->id,
                'role' => 'buyer', // Será un demandante
                'role_description' => 'Buscando alianzas estratégicas.'
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Llamamos a nuestros seeders en orden
        $this->call([
            UserSeeder::class,
            EventSeeder::class,
            // Aquí puedes añadir más seeders en el futuro
        ]);
    }

}


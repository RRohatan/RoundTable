<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ParticipantProfile>
 */
class ParticipantProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'user_id' se asignará desde el Seeder
            'company_name' => fake()->company(),
            'nit' => fake()->unique()->numerify('#########-#'),
            'phone' => fake()->phoneNumber(),
            'sector' => fake()->randomElement(['Tecnología', 'Alimentos', 'Salud', 'Turismo']),
            'portfolio_url' => null,
        ];
    }
}

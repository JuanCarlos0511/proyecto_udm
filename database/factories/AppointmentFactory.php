<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => fake()->dateTimeBetween('+1 week', '+2 months')->format('Y-m-d'),
            'user_id' => \App\Models\User::factory(),
            'subject' => fake()->sentence(3),
            'status' => fake()->randomElement(['Solicitado', 'Agendado', 'Completado', 'Cancelado']),
            'modality' => fake()->randomElement(['Consultorio', 'Domicilio']),
            'price' => fake()->randomFloat(2, 50, 200),
        ];
    }
}

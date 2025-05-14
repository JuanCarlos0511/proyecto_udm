<?php

namespace Database\Factories;

use App\Models\FollowUp;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FollowUp>
 */
class FollowUpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Obtener IDs de doctores y pacientes
        $doctorIds = User::where('role', 'doctor')->pluck('id')->toArray();
        $patientIds = User::where('role', 'paciente')->pluck('id')->toArray();
        
        // Si no hay doctores o pacientes, crear algunos
        if (empty($doctorIds)) {
            $doctorIds = [User::factory()->create(['role' => 'doctor'])->id];
        }
        
        if (empty($patientIds)) {
            $patientIds = [User::factory()->create(['role' => 'paciente'])->id];
        }
        
        // Generar una fecha de inicio entre 6 meses atrás y hoy
        $startDate = $this->faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d');
        
        // Posibles estados
        $status = $this->faker->randomElement(['active', 'inactive', 'completed']);
        
        // Si el estado es completado, generar una fecha de fin
        $endDate = null;
        if ($status === 'completed') {
            $endDate = $this->faker->dateTimeBetween($startDate, '+3 months')->format('Y-m-d');
        } elseif ($status === 'active' && $this->faker->boolean(30)) {
            // Para algunos seguimientos activos, establecer una fecha de fin futura
            $endDate = $this->faker->dateTimeBetween('+1 month', '+6 months')->format('Y-m-d');
        }
        
        return [
            'doctor_id' => $this->faker->randomElement($doctorIds),
            'patient_id' => $this->faker->randomElement($patientIds),
            'notes' => $this->faker->paragraph(),
            'status' => $status,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}

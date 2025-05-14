<?php

namespace Database\Seeders;

use App\Models\FollowUp;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FollowUpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los doctores
        $doctors = User::where('role', 'doctor')->get();
        
        // Obtener todos los pacientes
        $patients = User::where('role', 'paciente')->get();
        
        // Si no hay doctores o pacientes, no podemos crear seguimientos
        if ($doctors->isEmpty() || $patients->isEmpty()) {
            return;
        }
        
        // Crear al menos un seguimiento para cada doctor
        foreach ($doctors as $doctor) {
            // Seleccionar un paciente aleatorio para este doctor
            $patient = $patients->random();
            
            // Crear un seguimiento activo
            FollowUp::create([
                'doctor_id' => $doctor->id,
                'patient_id' => $patient->id,
                'notes' => 'Seguimiento de prueba para ' . $patient->name,
                'status' => 'active',
                'start_date' => now()->subDays(rand(1, 30)),
                'end_date' => rand(0, 1) ? now()->addDays(rand(30, 90)) : null,
            ]);
            
            // Para algunos doctores, crear más seguimientos
            if (rand(0, 1)) {
                // Seleccionar otro paciente aleatorio diferente
                $anotherPatient = $patients->except([$patient->id])->random();
                
                // Crear otro seguimiento con estado aleatorio
                $statuses = ['active', 'inactive', 'completed'];
                
                FollowUp::create([
                    'doctor_id' => $doctor->id,
                    'patient_id' => $anotherPatient->id,
                    'notes' => 'Otro seguimiento de prueba para ' . $anotherPatient->name,
                    'status' => $statuses[array_rand($statuses)],
                    'start_date' => now()->subDays(rand(1, 60)),
                    'end_date' => rand(0, 1) ? now()->addDays(rand(30, 90)) : null,
                ]);
            }
        }
        
        // Asegurarse de que cada paciente tenga al menos un seguimiento activo
        foreach ($patients as $patient) {
            $hasActiveFollowUp = FollowUp::where('patient_id', $patient->id)
                ->where('status', 'active')
                ->exists();
                
            if (!$hasActiveFollowUp) {
                // Seleccionar un doctor aleatorio
                $doctor = $doctors->random();
                
                // Crear un seguimiento activo
                FollowUp::create([
                    'doctor_id' => $doctor->id,
                    'patient_id' => $patient->id,
                    'notes' => 'Seguimiento activo para ' . $patient->name,
                    'status' => 'active',
                    'start_date' => now()->subDays(rand(1, 15)),
                    'end_date' => null,
                ]);
            }
        }
    }
}

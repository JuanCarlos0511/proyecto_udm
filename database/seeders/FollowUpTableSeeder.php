<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class FollowUpTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar algunos doctores y pacientes existentes
        $doctors = User::where('role', 'doctor')->take(3)->get();
        $patients = User::where('role', 'paciente')->take(5)->get();
        
        if ($doctors->isEmpty() || $patients->isEmpty()) {
            $this->command->info('No se encontraron suficientes doctores o pacientes para crear seguimientos');
            return;
        }
        
        // Crear grupos de seguimiento (doctor-paciente)
        $groupCounter = 1;
        
        foreach ($doctors as $doctor) {
            foreach ($patients as $patient) {
                $startDate = Carbon::now()->subDays(rand(1, 30));
                $endDate = rand(0, 1) ? $startDate->copy()->addDays(rand(10, 60)) : null;
                $status = ['active', 'inactive', 'completed'][rand(0, 2)];
                // Definir tratamientos específicos
                $treatments = ['Electroterapia', 'Hidroterapia', 'Mecanoterapia', 'Atención Integral'];
                $notes = $treatments[rand(0, 3)];
                
                
                // Crear registro para el doctor
                DB::table('follow_ups')->insert([
                    'follow_up_group_id' => $groupCounter,
                    'user_id' => $doctor->id,
                    'notes' => $notes,
                    'status' => $status,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                
                // Crear registro para el paciente
                DB::table('follow_ups')->insert([
                    'follow_up_group_id' => $groupCounter,
                    'user_id' => $patient->id,
                    'notes' => $notes,
                    'status' => $status,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                
                $groupCounter++;
            }
        }
        
        $this->command->info('Se crearon ' . ($groupCounter - 1) . ' grupos de seguimiento');
    }
}

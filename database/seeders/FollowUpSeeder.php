<?php

namespace Database\Seeders;

use App\Models\FollowUp;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class FollowUpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar las tablas existentes
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('follow_ups')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Primero vamos a asegurarnos que los usuarios 10 y 11 existan
        $user10 = User::find(10);
        $user11 = User::find(12);
        
        // Si no existen, mostrar un mensaje y continuar con otros usuarios
        if (!$user10 || !$user11) {
            echo "AVISO: Uno o ambos de los usuarios con IDs 10 y 11 no existen.\n";
            echo "Se procederá a crear seguimientos con otros usuarios.\n";
        }
        
        // Obtener doctores y pacientes disponibles
        $doctors = User::where('role', 'doctor')->get();
        $patients = User::where('role', 'paciente')->get();
        
        // Si no hay doctores o pacientes, no podemos crear seguimientos
        if ($doctors->isEmpty() || $patients->isEmpty()) {
            echo "No hay doctores o pacientes disponibles para crear seguimientos.\n";
            return;
        }
        
        // 1. Crear grupos de seguimiento específicos para los IDs 10 y 11 (si existen)
        $this->createSpecificFollowUps($user10, $user11);
        
        // 2. Crear grupos de seguimiento generales para otros doctores y pacientes
        $this->createGeneralFollowUps($doctors, $patients);
        
        echo "\nTotal de seguimientos creados: " . FollowUp::count() . "\n";
    }
    
    /**
     * Crear grupos de seguimiento específicos para los usuarios 10 y 11
     */
    private function createSpecificFollowUps($user10, $user11)
    {
        if (!$user10 || !$user11) {
            return;
        }
        
        $doctor = null;
        $patient = null;
        
        // Determinar cuál usuario es doctor y cuál es paciente
        if ($user10->role === 'doctor' && $user11->role === 'paciente') {
            $doctor = $user10;
            $patient = $user11;
        } elseif ($user11->role === 'doctor' && $user10->role === 'paciente') {
            $doctor = $user11;
            $patient = $user10;
        } else {
            echo "AVISO: Los usuarios 10 y 11 no son una combinación doctor-paciente válida.\n";
            return;
        }
        
        // Crear varios grupos de seguimiento entre estos usuarios
        $this->createFollowUpExample($doctor, $patient, 'active', 'Electroterapia', Carbon::now()->subDays(30), null);
        $this->createFollowUpExample($doctor, $patient, 'completed', 'Hidroterapia', Carbon::now()->subDays(90), Carbon::now()->subDays(30));
        $this->createFollowUpExample($doctor, $patient, 'inactive', 'Mecanoterapia', Carbon::now()->subDays(60), Carbon::now()->addDays(60));
        
        echo "Creados 3 ejemplos de seguimiento entre usuario {$doctor->id} (doctor) y usuario {$patient->id} (paciente)\n";
    }
    
    /**
     * Crea un ejemplo de seguimiento entre doctor y paciente
     */
    private function createFollowUpExample($doctor, $patient, $status, $notes, $startDate, $endDate)
    {
        // Crear un ID único para este grupo de seguimiento
        $groupId = $this->getNextGroupId();
        
        // Crear el seguimiento para el doctor
        FollowUp::create([
            'follow_up_group_id' => $groupId,
            'user_id' => $doctor->id, 
            'notes' => $notes,
            'status' => $status,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
        
        // Crear el seguimiento para el paciente con el mismo group_id
        FollowUp::create([
            'follow_up_group_id' => $groupId,
            'user_id' => $patient->id, 
            'notes' => $notes,
            'status' => $status,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
        
        return $groupId;
    }
    
    /**
     * Crear grupos de seguimiento generales aleatorios
     */
    private function createGeneralFollowUps($doctors, $patients)
    {
        $treatmentTypes = [
            'Electroterapia',
            'Hidroterapia',
            'Mecanoterapia',
            'Atención Integral'
        ];
        
        // Para cada doctor, crear entre 1 y 3 seguimientos
        foreach ($doctors as $doctor) {
            // Saltar el doctor específico si este ya fue usado en los ejemplos específicos
            if ($doctor->id == 10 || $doctor->id == 11) continue;
            
            $numFollowUps = rand(1, 3);
            
            for ($i = 0; $i < $numFollowUps; $i++) {
                // Seleccionar un paciente aleatorio (que no sea el ID 10 o 11)
                $patient = $patients->filter(function($p) {
                    return $p->id != 10 && $p->id != 11;
                })->random();
                
                // Estado aleatorio
                $status = ['active', 'inactive', 'completed'][rand(0, 2)];
                
                // Notas aleatorias
                $notes = $treatmentTypes[array_rand($treatmentTypes)];
                
                // Fechas aleatorias
                $startDate = Carbon::now()->subDays(rand(1, 120));
                $endDate = ($status === 'completed') ? Carbon::now()->subDays(rand(1, 30)) : 
                           ($status === 'inactive' ? Carbon::now()->addDays(rand(30, 120)) : null);
                
                // Crear el seguimiento
                $groupId = $this->createFollowUpExample($doctor, $patient, $status, $notes, $startDate, $endDate);
                
                echo "Creado seguimiento ID {$groupId} entre doctor {$doctor->id} y paciente {$patient->id} - Estado: {$status}\n";
            }
        }
    }
    
    /**
     * Obtener el próximo ID de grupo disponible
     */
    private function getNextGroupId()
    {
        // Obtener el último ID de grupo utilizado
        $lastGroup = DB::table('follow_ups')->max('follow_up_group_id');
        
        // Si no hay registros, comenzar desde 1
        return $lastGroup ? $lastGroup + 1 : 1;
    }
}

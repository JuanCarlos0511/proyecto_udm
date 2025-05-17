<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desactivar verificación de claves foráneas temporalmente
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Clear existing appointments
        Appointment::truncate();
        
        // Reset appointment groups
        \App\Models\AppointmentGroup::truncate();
        
        // Reactivar verificación de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Get user IDs for patients
        $patients = User::where('role', 'paciente')->get();
        
        // Get user IDs for doctors
        $doctors = User::where('role', 'doctor')->get();
        
        // Asegurarnos de que el doctor con ID 15 exista
        $doctorId15 = User::find(15);
        if (!$doctorId15 || $doctorId15->role !== 'doctor') {
            echo "AVISO: No se encontró un doctor con ID 15, se usarán doctores aleatorios\n";
        }
        
        // Predefined appointment subjects
        $subjects = [
            'Consulta General',
            'Limpieza Dental',
            'Extracción',
            'Ortodoncia',
            'Revisión Periódica',
            'Tratamiento de Conducto',
            'Implante Dental',
            'Blanqueamiento Dental'
        ];
        
        $dateStart = strtotime('-3 days');
        $dateEnd = strtotime('+30 days');
        
        // 1. Citas con doctores asignados (registro duplicado)
        foreach ($patients as $index => $patient) {
            if ($index >= 3) break; // Solo para los primeros 3 pacientes
            
            // Para cada paciente, crear 2 citas con doctor asignado (duplicadas)
            for ($i = 0; $i < 2; $i++) {
                // Fecha aleatoria pero con hora específica entre 8:00 AM y 6:00 PM
                $randomDate = date('Y-m-d', mt_rand($dateStart, $dateEnd));
                $hours = str_pad(mt_rand(8, 18), 2, '0', STR_PAD_LEFT); // Horas entre 08 y 18 (8 AM - 6 PM)
                $minutes = ['00', '15', '30', '45'][mt_rand(0, 3)]; // Minutos en intervalos de 15
                $date = "$randomDate $hours:$minutes:00";
                $subject = $subjects[array_rand($subjects)];
                $status = ['Solicitado', 'Agendado', 'Completado', 'Cancelado'][array_rand(['Solicitado', 'Agendado', 'Completado', 'Cancelado'])];
                $modality = ['Consultorio', 'Domicilio'][array_rand(['Consultorio', 'Domicilio'])];
                $price = mt_rand(5000, 20000) / 100; // Entre 50 y 200 con decimales
                $selectedDoctor = $doctors[array_rand($doctors->toArray())];
                
                // Crear un grupo de citas para relacionar la cita del paciente con la del doctor
                $appointmentGroup = \App\Models\AppointmentGroup::create([
                    'name' => "Cita entre {$patient->name} y {$selectedDoctor->name}",
                    'description' => "Cita para {$subject} el {$date}",
                ]);
                
                // Cita para el paciente
                $patientAppointment = Appointment::create([
                    'date' => $date,
                    'user_id' => $patient->id,
                    'subject' => $subject,
                    'status' => $status,
                    'modality' => $modality,
                    'price' => $price,
                    'appointment_group_id' => $appointmentGroup->id,
                ]);
                
                // Cita duplicada para el doctor (mismo horario)
                $doctorAppointment = Appointment::create([
                    'date' => $date,
                    'user_id' => $selectedDoctor->id, // Usuario es el doctor
                    'subject' => $subject,
                    'status' => $status,
                    'modality' => $modality,
                    'price' => $price,
                    'appointment_group_id' => $appointmentGroup->id,
                ]);
                
                echo "Creada cita duplicada entre paciente {$patient->name} y doctor {$selectedDoctor->name} para fecha {$date}\n";
            }
        }
        
        // 2. Citas sin doctor asignado (solo el registro del paciente)
        foreach ($patients as $patient) {
            // Para cada paciente, crear 1-3 citas sin doctor asignado
            $numAppointments = mt_rand(1, 3);
            
            for ($i = 0; $i < $numAppointments; $i++) {
                // Fecha aleatoria pero con hora específica entre 8:00 AM y 6:00 PM
                $randomDate = date('Y-m-d', mt_rand($dateStart, $dateEnd));
                $hours = str_pad(mt_rand(8, 18), 2, '0', STR_PAD_LEFT); // Horas entre 08 y 18 (8 AM - 6 PM)
                $minutes = ['00', '15', '30', '45'][mt_rand(0, 3)]; // Minutos en intervalos de 15
                $date = "$randomDate $hours:$minutes:00";
                $subject = $subjects[array_rand($subjects)];
                $status = ['Solicitado', 'Agendado'][array_rand(['Solicitado', 'Agendado'])];
                $modality = ['Consultorio', 'Domicilio'][array_rand(['Consultorio', 'Domicilio'])];
                $price = mt_rand(5000, 20000) / 100; // Entre 50 y 200 con decimales
                
                // Solo cita para el paciente (sin doctor asignado)
                $appointment = Appointment::create([
                    'date' => $date,
                    'user_id' => $patient->id,
                    'subject' => $subject,
                    'status' => $status,
                    'modality' => $modality,
                    'price' => $price,
                ]);
                
                echo "Creada cita para paciente {$patient->name} sin doctor asignado para fecha {$date}\n";
            }
        }
        
        // 3. Citas específicas para el doctor con ID 15 en estado "Solicitado"
        $this->createAppointmentsForDoctor15($patients, $subjects);
        
        echo "Seedeo de citas completado. Total: " . Appointment::count() . " citas creadas.\n";
    }
    
    /**
     * Crear citas específicas para el doctor con ID 15
     */
    private function createAppointmentsForDoctor15($patients, $subjects)
    {
        $doctorId15 = User::find(15);
        if (!$doctorId15 || $doctorId15->role !== 'doctor') {
            echo "No se pudieron crear citas específicas para el doctor con ID 15 porque no existe o no es un doctor\n";
            return;
        }
        
        echo "Creando citas específicas para el doctor {$doctorId15->name} (ID: 15)\n";
        
        // Seleccionar algunos pacientes para las citas
        $selectedPatients = $patients->random(min(5, count($patients)));
        
        foreach ($selectedPatients as $patient) {
            // Fecha dentro de los próximos 7 días
            $randomDays = mt_rand(1, 7);
            $randomDate = date('Y-m-d', strtotime("+{$randomDays} days"));
            $hours = str_pad(mt_rand(8, 18), 2, '0', STR_PAD_LEFT); // Horas entre 08 y 18
            $minutes = ['00', '15', '30', '45'][mt_rand(0, 3)];
            $date = "{$randomDate} {$hours}:{$minutes}:00";
            
            $subject = $subjects[array_rand($subjects)];
            $modality = ['Consultorio', 'Domicilio'][array_rand(['Consultorio', 'Domicilio'])];
            $price = mt_rand(5000, 20000) / 100;
            
            // Crear grupo de citas
            $appointmentGroup = \App\Models\AppointmentGroup::create([
                'name' => "Cita entre {$patient->name} y {$doctorId15->name}",
                'description' => "Cita para {$subject} el {$date}",
            ]);
            
            // Cita para el paciente en estado "Solicitado"
            $patientAppointment = Appointment::create([
                'date' => $date,
                'user_id' => $patient->id,
                'subject' => $subject,
                'status' => 'Solicitado',
                'modality' => $modality,
                'price' => $price,
                'appointment_group_id' => $appointmentGroup->id,
            ]);
            
            // Cita correspondiente para el doctor
            $doctorAppointment = Appointment::create([
                'date' => $date,
                'user_id' => $doctorId15->id,
                'subject' => $subject,
                'status' => 'Solicitado',
                'modality' => $modality,
                'price' => $price,
                'appointment_group_id' => $appointmentGroup->id,
            ]);
            
            echo "  - Creada cita Solicitada entre paciente {$patient->name} y doctor {$doctorId15->name} para {$date}\n";
        }
        
        echo "Total de citas específicas creadas para doctor ID 15: " . ($selectedPatients->count() * 2) . "\n";
    }
}
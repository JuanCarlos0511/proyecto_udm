<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get user IDs
        $patientId = User::where('role', 'patient')->first()->id ?? 3;
        $doctorId = User::where('role', 'doctor')->first()->id ?? 2;
        
        Appointment::create([
            'date' => '2025-04-15',
            'user_id' => $patientId,
            'subject' => 'Consulta general',
            'status' => 'Agendado',
            'modality' => 'Consultorio',
            'price' => 100.00,
        ]);
        
        Appointment::create([
            'date' => '2025-04-16',
            'user_id' => $patientId,
            'subject' => 'Seguimiento de tratamiento',
            'status' => 'Solicitado',
            'modality' => 'Domicilio',
            'price' => 150.00,
        ]);
        
        Appointment::create([
            'date' => '2025-04-17',
            'user_id' => $patientId,
            'subject' => 'Revisión de exámenes',
            'status' => 'Agendado',
            'modality' => 'Consultorio',
            'price' => 120.00,
        ]);
        
        // Create additional appointments
        Appointment::factory(5)->create();
    }
}
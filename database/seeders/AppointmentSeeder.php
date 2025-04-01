<?php

namespace Database\Seeders;

use App\Models\Appointment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Appointment::create([
            'date' => '2025-04-15',
            'patient_id' => 1,
            'doctor_id' => 1,
            'subject' => 'Annual checkup',
            'status' => 'scheduled',
            'modality' => 'in-person',
        ]);
        
        Appointment::create([
            'date' => '2025-04-16',
            'patient_id' => 2,
            'doctor_id' => 2,
            'subject' => 'Flu symptoms',
            'status' => 'scheduled',
            'modality' => 'home-visit',
        ]);
        
        Appointment::create([
            'date' => '2025-04-17',
            'patient_id' => 3,
            'doctor_id' => 3,
            'subject' => 'Follow-up appointment',
            'status' => 'scheduled',
            'modality' => 'in-person',
        ]);
    }
}
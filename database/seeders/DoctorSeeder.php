<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Doctor::create([
            'name' => 'Dr. John Smith',
            'specialty' => 'Cardiology',
            'phone' => '555-123-4567',
            'email' => 'john.smith@example.com',
            'status' => 'active',
        ]);
        
        Doctor::create([
            'name' => 'Dr. Maria Rodriguez',
            'specialty' => 'Pediatrics',
            'phone' => '555-987-6543',
            'email' => 'maria.rodriguez@example.com',
            'status' => 'active',
        ]);
        
        Doctor::create([
            'name' => 'Dr. Robert Johnson',
            'specialty' => 'Neurology',
            'phone' => '555-456-7890',
            'email' => 'robert.johnson@example.com',
            'status' => 'inactive',
        ]);
    }
}
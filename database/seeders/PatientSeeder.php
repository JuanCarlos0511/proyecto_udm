<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Patient::create([
            'name' => 'Alice Brown',
            'age' => 35,
            'phone' => '555-111-2222',
            'address' => '123 Main St, Anytown, USA',
        ]);
        
        Patient::create([
            'name' => 'Bob Wilson',
            'age' => 42,
            'phone' => '555-333-4444',
            'address' => '456 Oak Ave, Somewhere, USA',
        ]);
        
        Patient::create([
            'name' => 'Carol Davis',
            'age' => 28,
            'phone' => '555-555-6666',
            'address' => '789 Pine Rd, Nowhere, USA',
        ]);
    }
}
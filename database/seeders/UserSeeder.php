<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'age' => 35,
            'role' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'adress' => 'Admin Street 123',
            'status' => 'active',
            'phoneNumber' => '123-456-7890',
        ]);
        
        // Create doctor user
        User::create([
            'name' => 'Doctor User',
            'age' => 40,
            'role' => 'doctor',
            'email' => 'doctor@example.com',
            'password' => Hash::make('password'),
            'adress' => 'Doctor Avenue 456',
            'status' => 'active',
            'phoneNumber' => '234-567-8901',
        ]);
        
        // Create patient user
        User::create([
            'name' => 'Patient User',
            'age' => 28,
            'role' => 'patient',
            'email' => 'patient@example.com',
            'password' => Hash::make('password'),
            'adress' => 'Patient Boulevard 789',
            'status' => 'active',
            'phoneNumber' => '345-678-9012',
        ]);
        
        // Create additional users
        User::factory(5)->create();
    }
}

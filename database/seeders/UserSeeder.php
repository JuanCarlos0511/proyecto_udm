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
        // Actualizar o crear usuarios con nuevas contraseñas
        
        // Rosa Elba Martinez (Doctor)
        User::updateOrCreate(
            ['email' => 'rosa.martinez@clinicamiel.com'],
            [
                'name' => 'Rosa Elba Martinez',
                'age' => 45,
                'role' => 'doctor',
                'password' => Hash::make('RosaDoc2025!'),
                'adress' => 'Pedro José Méndez, 89240 Tampico, Tamaulipas',
                'status' => 'active',
                'phoneNumber' => '5551234567',
                'email_verified_at' => now(),
            ]
        );
        
        // Mauricio Solis (Admin)
        User::updateOrCreate(
            ['email' => 'mauricio.solis@clinicamiel.com'],
            [
                'name' => 'Mauricio Solis',
                'age' => 38,
                'role' => 'admin',
                'password' => Hash::make('MauAdmin2025#'),
                'adress' => 'Pedro José Méndez, 89240 Tampico, Tamaulipas',
                'status' => 'active',
                'phoneNumber' => '5552345678',
                'email_verified_at' => now(),
            ]
        );
        
        // Isaac Solis Martinez (Doctor)
        User::updateOrCreate(
            ['email' => 'isaac.solis@clinicamiel.com'],
            [
                'name' => 'Isaac Solis Martinez',
                'age' => 42,
                'role' => 'doctor',
                'password' => Hash::make('IsaacDoc2025@'),
                'adress' =>'Pedro José Méndez, 89240 Tampico, Tamaulipas',
                'status' => 'active',
                'phoneNumber' => '5553456789',
                'email_verified_at' => now(),
            ]
        );
        
        // Karla Lorena Martinez Avila (Doctor)
        User::updateOrCreate(
            ['email' => 'karla.martinez@clinicamiel.com'],
            [
                'name' => 'Karla Lorena Martinez Avila',
                'age' => 39,
                'role' => 'doctor',
                'password' => Hash::make('KarlaDoc2025&'),
                'adress' => 'Pedro José Méndez, 89240 Tampico, Tamaulipas',
                'status' => 'active',
                'phoneNumber' => '5554567890',
                'email_verified_at' => now(),
            ]
        );
        
        // Crear un usuario paciente de ejemplo
        // User::create([
        //     'name' => 'Paciente Ejemplo',
        //     'age' => 30,
        //     'role' => 'paciente',
        //     'email' => 'paciente@ejemplo.com',
        //     'password' => Hash::make('password'),
        //     'adress' => 'Calle Paciente 123, Colonia Salud',
        //     'status' => 'active',
        //     'phoneNumber' => '5555678901',
        //     'email_verified_at' => now(),
        // ]);
    }
}

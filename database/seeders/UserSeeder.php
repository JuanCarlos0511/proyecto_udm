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
        
        // Crear usuarios pacientes de ejemplo
        User::updateOrCreate(
            ['email' => 'maria.gonzalez@ejemplo.com'],
            [
                'name' => 'María González',
                'age' => 32,
                'role' => 'paciente',
                'password' => Hash::make('Maria2025!'),
                'adress' => 'Calle Flores 123, Col. Centro, Tampico',
                'status' => 'active',
                'phoneNumber' => '5555678901',
                'email_verified_at' => now(),
                'emergency_contact_name' => 'Roberto González',
                'emergency_contact_phone' => '5555678902',
                'emergency_contact_relationship' => 'Hermano',
            ]
        );
        
        User::updateOrCreate(
            ['email' => 'carlos.rodriguez@ejemplo.com'],
            [
                'name' => 'Carlos Rodríguez',
                'age' => 45,
                'role' => 'paciente',
                'password' => Hash::make('Carlos2025!'),
                'adress' => 'Av. Hidalgo 456, Col. Lomas, Tampico',
                'status' => 'active',
                'phoneNumber' => '5555678903',
                'email_verified_at' => now(),
                'emergency_contact_name' => 'Ana Rodríguez',
                'emergency_contact_phone' => '5555678904',
                'emergency_contact_relationship' => 'Esposa',
            ]
        );
        
        User::updateOrCreate(
            ['email' => 'ana.martinez@ejemplo.com'],
            [
                'name' => 'Ana Martínez',
                'age' => 28,
                'role' => 'paciente',
                'password' => Hash::make('Ana2025!'),
                'adress' => 'Calle Reforma 789, Col. Nuevo, Tampico',
                'status' => 'active',
                'phoneNumber' => '5555678905',
                'email_verified_at' => now(),
                'emergency_contact_name' => 'Juan Martínez',
                'emergency_contact_phone' => '5555678906',
                'emergency_contact_relationship' => 'Padre',
            ]
        );
        
        User::updateOrCreate(
            ['email' => 'juan.perez@ejemplo.com'],
            [
                'name' => 'Juan Pérez',
                'age' => 52,
                'role' => 'paciente',
                'password' => Hash::make('Juan2025!'),
                'adress' => 'Av. Madero 101, Col. Centro, Tampico',
                'status' => 'active',
                'phoneNumber' => '5555678907',
                'email_verified_at' => now(),
                'emergency_contact_name' => 'Laura Pérez',
                'emergency_contact_phone' => '5555678908',
                'emergency_contact_relationship' => 'Hija',
            ]
        );
        
        User::updateOrCreate(
            ['email' => 'lucia.gomez@ejemplo.com'],
            [
                'name' => 'Lucía Gómez',
                'age' => 35,
                'role' => 'paciente',
                'password' => Hash::make('Lucia2025!'),
                'adress' => 'Calle Juárez 234, Col. Alameda, Tampico',
                'status' => 'active',
                'phoneNumber' => '5555678909',
                'email_verified_at' => now(),
                'emergency_contact_name' => 'Miguel Gómez',
                'emergency_contact_phone' => '5555678910',
                'emergency_contact_relationship' => 'Esposo',
            ]
        );
    }
}

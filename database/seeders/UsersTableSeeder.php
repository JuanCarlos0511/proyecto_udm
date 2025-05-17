<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador
        DB::table('users')->insert([
            'name' => 'Administrador Principal',
            'email' => 'admin@clinicamiel.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'phoneNumber' => '123456789',
            'age' => 35,
            'adress' => 'Calle Principal #123',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        // Crear doctores
        $doctores = [
            [
                'name' => 'Dr. Carlos Méndez',
                'email' => 'cmendez@clinicamiel.com',
                'speciality' => 'Cardiología'
            ],
            [
                'name' => 'Dra. María Rodríguez',
                'email' => 'mrodriguez@clinicamiel.com',
                'speciality' => 'Pediatría'
            ],
            [
                'name' => 'Dr. José González',
                'email' => 'jgonzalez@clinicamiel.com',
                'speciality' => 'Neurología'
            ],
        ];
        
        foreach ($doctores as $doctor) {
            DB::table('users')->insert([
                'name' => $doctor['name'],
                'email' => $doctor['email'],
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'status' => 'active',
                'phoneNumber' => '555' . rand(100000, 999999),
                'age' => rand(30, 60),
                'adress' => 'Calle Doctor #' . rand(100, 999),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
        
        // Crear pacientes
        $nombres = ['Ana', 'Pedro', 'Laura', 'Juan', 'Sofía', 'Miguel', 'Isabella', 'Luis'];
        $apellidos = ['García', 'López', 'Martínez', 'Hernández', 'Pérez', 'Sánchez', 'Romero', 'Flores'];
        
        for ($i = 0; $i < 10; $i++) {
            $nombre = $nombres[array_rand($nombres)];
            $apellido = $apellidos[array_rand($apellidos)];
            $email = strtolower(substr($nombre, 0, 1) . $apellido . rand(10, 99) . '@mail.com');
            
            DB::table('users')->insert([
                'name' => $nombre . ' ' . $apellido,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'paciente',
                'status' => 'active',
                'phoneNumber' => '777' . rand(100000, 999999),
                'age' => rand(18, 80),
                'adress' => 'Calle Paciente #' . rand(100, 999),
                'created_at' => Carbon::now()->subDays(rand(1, 365)),
                'updated_at' => Carbon::now(),
            ]);
        }
        
        $this->command->info('Se crearon usuarios: 1 administrador, 3 doctores y 10 pacientes');
    }
}

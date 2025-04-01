<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        DB::table('pets')->insert([
            [
                'name' => 'Buddy',
                'type' => 'Dog',
                'age' => 3
            ],
            [
                'name' => 'Whiskers',
                'type' => 'Cat',
                'age' => 2
            ],
            [
                'name' => 'Goldie',
                'type' => 'Fish',
                'age' => 1
            ],
            [
                'name' => 'Hopper',
                'type' => 'Rabbit',
                'age' => 4
            ]
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TutorSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Andi Pratama',
                'email' => 'andi@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'tutor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'tutor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Citra Lestari',
                'email' => 'citra@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'tutor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dewi Anggraini',
                'email' => 'dewi@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'tutor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Eko Saputra',
                'email' => 'eko@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'tutor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Tutor;
use Illuminate\Database\Seeder;

class TutorSeeder extends Seeder
{
    public function run(): void
    {
        $andi = User::create([
            'name' => 'Andi Pratama',
            'email' => 'andi@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'tutor'
        ]);

        Tutor::create([
            'user_id' => $andi->id,
            'bio' => 'Tutor TPS berpengalaman membantu siswa SNBT.',
            'tps' => true,
            'literasi' => false,
            'numerasi' => false,
            'rating' => 4.8,
            'total_reviews' => 120
        ]);

        $budi = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'tutor'
        ]);

        Tutor::create([
            'user_id' => $budi->id,
            'bio' => 'Spesialis Numerasi dan strategi pengerjaan cepat.',
            'tps' => false,
            'literasi' => false,
            'numerasi' => true,
            'rating' => 4.7,
            'total_reviews' => 95
        ]);

        $citra = User::create([
            'name' => 'Citra Lestari',
            'email' => 'citra@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'tutor'
        ]);

        Tutor::create([
            'user_id' => $citra->id,
            'bio' => 'Tutor Literasi dengan fokus reading comprehension.',
            'tps' => false,
            'literasi' => true,
            'numerasi' => false,
            'rating' => 4.9,
            'total_reviews' => 150
        ]);
    }
}
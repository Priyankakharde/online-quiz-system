<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 🔥 Reset users (safe for development)
        User::truncate();

        // ✅ Create Admin/User (LOGIN FIXED PERMANENTLY)
        User::create([
            'name' => 'Priyanka',
            'email' => 'priyanka123@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        // ✅ Call ALL seeders (IMPORTANT)
        $this->call([
            CategorySeeder::class,
            QuizSeeder::class,      // 👉 make sure quiz exists (id = 1)
            SubjectSeeder::class,   // 👉 subjects linked to quiz
        ]);
    }
}
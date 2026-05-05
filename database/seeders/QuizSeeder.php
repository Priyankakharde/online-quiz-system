<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        // 🔥 Clear old data (safe in development)
        Quiz::truncate();

        // ✅ Create Quiz (FIXED - no start_time)
        Quiz::create([
            'title' => 'AI Fundamentals Test',
            'description' => 'Basic concepts of Artificial Intelligence',
            'category_id' => 1, // make sure category exists
            'duration' => 60,
        ]);
    }
}
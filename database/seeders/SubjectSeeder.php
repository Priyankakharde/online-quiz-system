<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        Subject::insert([
            [
                'name' => 'Artificial Intelligence',
                'quiz_id' => 1,
            ],
            [
                'name' => 'Machine Learning',
                'quiz_id' => 1,
            ],
            [
                'name' => 'Deep Learning',
                'quiz_id' => 1,
            ],
            [
                'name' => 'Data Science',
                'quiz_id' => 1,
            ],
            [
                'name' => 'Python Basics',
                'quiz_id' => 1,
            ],
        ]);
    }
}
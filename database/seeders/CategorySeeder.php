<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Artificial Intelligence',
            'Machine Learning',
            'Data Science',
            'Web Development',
            'Python',
            'Cyber Security',
            'Cloud Computing',
            'Java Programming',
            'C++ Programming',
            'Database Management'
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate([
                'name' => $cat
            ]);
        }
    }
}
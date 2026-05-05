<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete old users (optional but recommended)
        User::truncate();

        // Create Admin/User
        User::create([
            'name' => 'Priyanka',
            'email' => 'priyanka123@gmail.com',
            'password' => Hash::make('123456'), // ✅ hashed password
        ]);
    }
}
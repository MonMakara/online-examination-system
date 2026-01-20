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
        // 1. Create a specific ADMIN account
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // password is 'password'
            'role' => 'admin',
            'email_verified' => true,
        ]);

        // 2. Create a specific TEACHER account
        User::create([
            'name' => 'Mr. Teacher',
            'email' => 'teacher@example.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'email_verified' => true,
        ]);

        // 3. Create a specific STUDENT account
        User::create([
            'name' => 'Student One',
            'email' => 'student@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'email_verified' => true,
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the first admin user
        User::create([
            'name' => 'Admin One',
            'email' => 'admin1@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Set a password for admin one
            'remember_token' => Str::random(10),
        ]);

        // Create the second admin user
        User::create([
            'name' => 'Admin Two',
            'email' => 'admin2@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Set a password for admin two (you might want a different one)
            'remember_token' => Str::random(10),
        ]);
    }
}
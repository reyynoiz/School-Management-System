<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat akun admin
        User::create([
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@school.id',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'status' => 1,
            ]);

            // Buat akun guru
            User::create([
                'name' => 'Guru Satu',
                'username' => 'guru1',
                'email' => 'guru@school.id',
                'password' => bcrypt('guru123'),
                'role' => 'teacher',
                'status' => 1,
            ]);
        }
}

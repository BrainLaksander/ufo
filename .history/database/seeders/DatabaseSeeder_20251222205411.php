<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create sample users for testing each role
        User::factory()->create([
            'name' => 'Admin Sistem',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Kemahasiswaan',
            'email' => 'kemahasiswaan@example.com',
            'password' => bcrypt('password'),
            'role' => 'kemahasiswaan',
        ]);

        User::factory()->create([
            'name' => 'Pengurus Organisasi',
            'email' => 'pengurus@example.com',
            'password' => bcrypt('password'),
            'role' => 'pengurus',
        ]);

        User::factory()->create([
            'name' => 'Mahasiswa',
            'email' => 'mahasiswa@example.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
        ]);
    }
}

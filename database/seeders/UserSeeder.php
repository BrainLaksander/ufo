<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'kemahasiswaan@unklab.ac.id'],
            [
                'name' => 'Kemahasiswaan',
                'password' => Hash::make('password'),
                'role' => 'kemahasiswaan',
            ]
        );

        User::updateOrCreate(
            ['email' => 'bem.universitas@unklab.ac.id'],
            [
                'name' => 'BEM Universitas',
                'password' => Hash::make('bemuniversitas123'),
                'role' => 'pengurus_ukm',
            ]
        );
    }
}

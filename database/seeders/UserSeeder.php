<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Kemahasiswaan User
        DB::table('users')->updateOrInsert(
            ['email' => 'kemahasiswaan@unklab.ac.id'],
            [
                'name' => 'Admin Kemahasiswaan',
                'password' => Hash::make('password'),
                'role' => 'kemahasiswaan',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Ensure at least one organization exists
        $orgId = DB::table('organizations')->first()?->id;
        if (!$orgId) {
            $orgId = DB::table('organizations')->insertGetId([
                'name' => 'UKM Korps Protokol Mahasiswa',
                'shortname' => 'KPM',
                'description' => 'Organisasi yang menangani protokoler kampus.',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        DB::table('users')->updateOrInsert(
            ['email' => 'pengurus@unklab.ac.id'],
            [
                'name' => 'Pengurus UKM',
                'password' => Hash::make('password'),
                'role' => 'pengurus',
                'organization_id' => $orgId,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Create Mahasiswa User
        DB::table('users')->updateOrInsert(
            ['email' => 'mahasiswa@unklab.ac.id'],
            [
                'name' => 'Mahasiswa Dummy',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        // Link pengurus user to kemahasiswaan_ukm_accounts if table exists
        if (\Illuminate\Support\Facades\Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            $user = DB::table('users')->where('role', 'pengurus')->first();
            if ($user && $orgId) {
                DB::table('kemahasiswaan_ukm_accounts')->updateOrInsert(
                    ['organization_id' => $orgId],
                    [
                        'user_id' => $user->id,
                        'name' => 'Account ' . $user->name,
                        'email' => $user->email,
                        'status' => 'active',
                        'password_hash' => Hash::make('password'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}

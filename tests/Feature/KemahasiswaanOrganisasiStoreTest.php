<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class KemahasiswaanOrganisasiStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_organisasi_without_pengurus_account_creates_only_organization(): void
    {
        $response = $this->withSession([
            'user' => [
                'email' => 'kemahasiswaan@example.com',
                'role' => 'kemahasiswaan',
            ],
        ])->post(route('portal.kemahasiswaan.organisasi.store'), [
            'name' => 'Unit Kegiatan Mahasiswa Sains',
            'shortname' => '',
            'category' => 'Akademik & Teknologi',
            'type' => 'UKM',
            'level' => 'Universitas',
            'field' => 'Sains dan Teknologi',
            'description' => 'Wadah pengembangan minat akademik dan teknologi.',
            'email' => 'ukm-sains@example.com',
            'phone' => '081234567890',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Organisasi baru berhasil ditambahkan.');

        $this->assertDatabaseHas('organizations', [
            'name' => 'Unit Kegiatan Mahasiswa Sains',
            'category' => 'Akademik & Teknologi',
            'type' => 'UKM',
            'level' => 'Universitas',
            'field' => 'Sains dan Teknologi',
            'email' => 'ukm-sains@example.com',
            'phone' => '081234567890',
        ]);

        $this->assertSame(1, DB::table('organizations')->count());
        $this->assertSame(0, DB::table('kemahasiswaan_ukm_accounts')->count());
    }

    public function test_store_organisasi_with_pengurus_account_creates_organization_and_account(): void
    {
        $response = $this->withSession([
            'user' => [
                'email' => 'kemahasiswaan@example.com',
                'role' => 'kemahasiswaan',
            ],
        ])->post(route('portal.kemahasiswaan.organisasi.store'), [
            'name' => 'Badan Eksekutif Mahasiswa',
            'shortname' => 'BEM',
            'category' => 'BEM',
            'type' => 'BEM',
            'level' => 'Universitas',
            'field' => 'Pemerintahan Mahasiswa',
            'description' => 'Organisasi eksekutif mahasiswa tingkat universitas.',
            'email' => 'bem@example.com',
            'phone' => '081298765432',
            'pengurus_name' => 'Andi Pratama',
            'pengurus_email' => 'andi.pratama@example.com',
            'pengurus_position' => 'Ketua',
            'pengurus_password' => 'Rahasia123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $organization = DB::table('organizations')
            ->where('name', 'Badan Eksekutif Mahasiswa')
            ->first();

        $this->assertNotNull($organization);

        $account = DB::table('kemahasiswaan_ukm_accounts')
            ->where('organization_id', $organization->id)
            ->where('email', 'andi.pratama@example.com')
            ->first();

        $this->assertNotNull($account);
        $this->assertSame('Andi Pratama', $account->name);
        $this->assertSame('Ketua', $account->position);
        $this->assertSame('active', $account->status);
        $this->assertNotEmpty($account->password_hash);
        $this->assertTrue(Hash::check('Rahasia123', $account->password_hash));
    }
}
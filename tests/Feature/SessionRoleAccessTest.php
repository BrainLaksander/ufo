<?php

namespace Tests\Feature;

use Tests\TestCase;

class SessionRoleAccessTest extends TestCase
{
    public function test_kemahasiswaan_cannot_access_pengurus_url(): void
    {
        $response = $this->withSession([
            'user' => [
                'email' => 'kemahasiswaan@example.com',
                'role' => 'kemahasiswaan',
            ],
        ])->get('/pengurus');

        $response->assertForbidden();
    }

    public function test_pengurus_cannot_access_kemahasiswaan_url(): void
    {
        $response = $this->withSession([
            'user' => [
                'email' => 'pengurus@example.com',
                'role' => 'pengurus',
            ],
        ])->get('/kemahasiswaan');

        $response->assertForbidden();
    }

    public function test_guest_is_redirected_to_login_for_internal_url(): void
    {
        $response = $this->get('/pengurus');

        $response->assertRedirect(route('login'));
    }
}

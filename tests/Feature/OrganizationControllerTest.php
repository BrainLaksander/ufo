<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Organization;

class OrganizationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_organization()
    {
        $response = $this->postJson('/kemahasiswaan/organisasi', []);
        $response->assertStatus(401);
    }

    public function test_forbidden_for_non_kemahasiswaan_role()
    {
        $user = User::create(['name' => 'User', 'email' => 'u@example.test', 'password' => 'secret', 'role' => 'student']);
        $response = $this->actingAs($user)->postJson('/kemahasiswaan/organisasi', [
            'name' => '',
        ]);
        $response->assertStatus(403);
    }

    public function test_validation_errors_return_422()
    {
        $user = User::create(['name' => 'Admin', 'email' => 'admin@example.test', 'password' => 'secret', 'role' => 'kemahasiswaan']);
        $response = $this->actingAs($user)->postJson('/kemahasiswaan/organisasi', []);
        $response->assertStatus(422);
    }

    public function test_create_organization_and_creates_user_for_chair()
    {
        $user = User::create(['name' => 'Admin', 'email' => 'admin2@example.test', 'password' => 'secret', 'role' => 'kemahasiswaan']);

        $payload = [
            'name' => 'Test Org',
            'category' => 'UKM',
            'type' => 'UKM',
            'field' => 'Seni',
            'chair_name' => 'Chair Person',
            'chair_email' => 'chair@example.test',
        ];

        $response = $this->actingAs($user)->postJson('/kemahasiswaan/organisasi', $payload);
        $response->assertStatus(201);
        $this->assertDatabaseHas('organizations', ['name' => 'Test Org']);
        $this->assertDatabaseMissing('users', ['email' => 'chair@example.test']);

        $json = $response->json();
        // For chair creation we don't return credentials anymore (org account is separate)
        $this->assertArrayNotHasKey('credentials', $json);

    }

    public function test_create_org_creates_org_account_when_provided()
    {
        $user = User::create(['name' => 'Admin', 'email' => 'admin3@example.test', 'password' => 'secret', 'role' => 'kemahasiswaan']);

        $payload = [
            'name' => 'Org With Account',
            'category' => 'UKM',
            'type' => 'UKM',
            'field' => 'Seni',
            'account_email' => 'orgaccount@example.test',
            'account_password' => 's3cr3tP@ss',
        ];

        $response = $this->actingAs($user)->postJson('/kemahasiswaan/organisasi', $payload);
        $response->assertStatus(201);
        $this->assertDatabaseHas('organizations', ['name' => 'Org With Account', 'account_email' => 'orgaccount@example.test']);
        $this->assertDatabaseHas('users', ['email' => 'orgaccount@example.test']);
        $json = $response->json();
        $this->assertEquals('created', $json['credentials']['status']);
    }

    public function test_reset_org_account_password()
    {
        $user = User::create(['name' => 'Admin', 'email' => 'admin4@example.test', 'password' => 'secret', 'role' => 'kemahasiswaan']);

        // create org and linked user
        $orgUser = User::create(['name' => 'OrgUser', 'email' => 'orguser@example.test', 'password' => 'oldpass', 'role' => 'pengurus']);
        $org = Organization::create(['name' => 'Reset Org', 'category' => 'UKM', 'type' => 'UKM', 'field' => 'Seni', 'account_user_id' => $orgUser->id, 'account_email' => $orgUser->email]);

        // provide manual password
        $manual = 'newStrongP@ss1';
        $response = $this->actingAs($user)->postJson('/kemahasiswaan/organisasi/' . $org->id . '/reset-account', ['password' => $manual]);
        $response->assertStatus(200);
        $json = $response->json();
        $this->assertArrayHasKey('credentials', $json);
        $this->assertEquals('reset', $json['credentials']['status']);
        $this->assertEquals('orguser@example.test', $json['credentials']['email']);
        $this->assertArrayHasKey('password', $json['credentials']);
        $this->assertEquals($manual, $json['credentials']['password']);

        // audit log exists
        $this->assertDatabaseHas('organization_account_resets', ['organization_id' => $org->id, 'admin_user_id' => $user->id]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiLeadsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_login_returns_token_and_profile_data(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@test.com',
        ]);
        $user->assignRole('admin');

        $response = $this->postJson('/api/v1/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Authenticated')
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'name', 'email'],
                    'roles',
                    'permissions',
                    'token',
                ],
            ]);

        $this->assertNotEmpty($response->json('data.token'));
    }

    public function test_authenticated_admin_can_list_leads(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@test.com',
        ]);
        $user->assignRole('admin');

        Lead::create(['name' => 'Lead One', 'email' => 'one@test.com', 'status' => 'new']);
        Lead::create(['name' => 'Lead Two', 'email' => 'two@test.com', 'status' => 'new']);

        $login = $this->postJson('/api/v1/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $token = $login->json('data.token');

        $response = $this->withToken($token)->getJson('/api/v1/leads');

        $response->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Leads retrieved')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'email', 'phone', 'status', 'created_at', 'updated_at'],
                ],
            ]);

        $this->assertCount(2, $response->json('data'));
    }

    public function test_user_without_view_leads_permission_cannot_list_leads(): void
    {
        $user = User::factory()->create([
            'email' => 'user@test.com',
        ]);
        $user->assignRole('user');

        $login = $this->postJson('/api/v1/login', [
            'email' => 'user@test.com',
            'password' => 'password',
        ]);

        $token = $login->json('data.token');

        $this->withToken($token)
            ->getJson('/api/v1/leads')
            ->assertForbidden()
            ->assertJsonPath('status', false);
    }

    public function test_guest_cannot_access_leads(): void
    {
        $this->getJson('/api/v1/leads')->assertUnauthorized();
    }
}

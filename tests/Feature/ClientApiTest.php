<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\SaasSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(SaasSeeder::class);
    }

    public function test_owner_can_list_clients(): void
    {
        /** @var User $user */
        $user = User::where('email', 'owner@example.com')->firstOrFail();

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/clients');

        $response->assertOk();
        $response->assertJsonStructure(['data', 'links', 'meta']);
    }

    public function test_owner_can_create_client(): void
    {
        /** @var User $user */
        $user = User::where('email', 'owner@example.com')->firstOrFail();

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/clients', [
            'name' => 'New Client',
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('clients', [
            'name' => 'New Client',
            'company_id' => $user->company_id,
        ]);
    }

    public function test_user_cannot_update_client_from_another_company(): void
    {
        /** @var User $user */
        $user = User::where('email', 'owner@example.com')->firstOrFail();

        $this->actingAs($user, 'sanctum');

        $otherCompanyClient = Client::factory()->create();

        $response = $this->putJson('/api/clients/'.$otherCompanyClient->id, [
            'name' => 'Hacked Name',
        ]);

        $response->assertStatus(422);
    }
}

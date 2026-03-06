<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Sale;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\SaasSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(SaasSeeder::class);
    }

    public function test_owner_can_create_sale_for_own_client(): void
    {
        /** @var User $user */
        $user = User::where('email', 'owner@example.com')->firstOrFail();

        $this->actingAs($user, 'sanctum');

        /** @var Client $client */
        $client = $user->company->clients()->first();

        $response = $this->postJson('/api/sales', [
            'client_id' => $client->id,
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('sales', [
            'client_id' => $client->id,
        ]);
    }

    public function test_owner_cannot_create_sale_for_other_company_client(): void
    {
        /** @var User $user */
        $user = User::where('email', 'owner@example.com')->firstOrFail();

        $this->actingAs($user, 'sanctum');

        $otherClient = Client::factory()->create();

        $response = $this->postJson('/api/sales', [
            'client_id' => $otherClient->id,
        ]);

        $response->assertStatus(422);
    }

    public function test_owner_can_approve_sale_from_own_company(): void
    {
        /** @var User $user */
        $user = User::where('email', 'owner@example.com')->firstOrFail();

        $this->actingAs($user, 'sanctum');

        /** @var Client $client */
        $client = $user->company->clients()->first();

        /** @var Sale $sale */
        $sale = $client->sales()->first();

        $response = $this->postJson('/api/sales/'.$sale->id.'/approve');

        $response->assertOk();

        $this->assertNotNull($sale->fresh()->approved_at);
    }
}
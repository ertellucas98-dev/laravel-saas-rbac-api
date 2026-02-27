<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Company;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Seeder;

class SaasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a single company
        $company = Company::create([
            'name' => 'Acme SaaS Inc.',
            'active' => true,
        ]);

        // Create an owner user for the company
        $owner = User::factory()->create([
            'name' => 'Owner User',
            'email' => 'owner@example.com',
            'company_id' => $company->id,
        ]);

        // Create sample clients for the company
        $clients = collect([
            'Client One',
            'Client Two',
            'Client Three',
        ])->map(function (string $name) use ($company): Client {
            return Client::create([
                'company_id' => $company->id,
                'name' => $name,
            ]);
        });

        // Create sample sales for each client
        $clients->each(function (Client $client): void {
            // You can adjust the number of sales per client as needed
            foreach (range(1, 3) as $i) {
                Sale::create([
                    'client_id' => $client->id,
                ]);
            }
        });
    }
}

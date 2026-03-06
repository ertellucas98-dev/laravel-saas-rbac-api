<?php

namespace App\Services;

use App\Jobs\ApproveSaleJob;
use App\Models\Client;
use App\Models\Sale;
use App\Models\User;
use App\Repositories\SaleRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class SaleService
{
    public function __construct(
        private readonly SaleRepository $sales,
    ) {
    }

    public function listForUser(User $user): LengthAwarePaginator
    {
        return $this->sales->paginateForUser($user);
    }

    public function create(User $user, array $data): Sale
    {
        /** @var Client|null $client */
        $client = Client::query()->where('id', $data['client_id'])->first();

        if (! $client) {
            throw ValidationException::withMessages([
                'client_id' => ['The selected client is invalid.'],
            ]);
        }

        if ($client->company_id !== $user->company_id) {
            throw ValidationException::withMessages([
                'client_id' => ['The selected client does not belong to your company.'],
            ]);
        }

        return $this->sales->createForClient($client->id);
    }

    public function approve(User $user, Sale $sale): Sale
    {
        Gate::authorize('approve', $sale);

        ApproveSaleJob::dispatch($sale, $user->id);

        return $sale->refresh();
    }
}

<?php

namespace App\Services;

use App\Models\Client;
use App\Models\User;
use App\Repositories\ClientRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class ClientService
{
    public function __construct(
        private readonly ClientRepository $clients,
    ) {
    }

    public function listForUser(User $user): LengthAwarePaginator
    {
        return $this->clients->paginateForUser($user);
    }

    public function create(User $user, array $data): Client
    {
        return $this->clients->createForUser($user, $data);
    }

    public function update(User $user, Client $client, array $data): Client
    {
        Gate::authorize('update', $client);

        return $this->clients->update($client, $data);
    }

    public function delete(User $user, Client $client): void
    {
        Gate::authorize('delete', $client);

        $this->clients->delete($client);
    }

    public function ensureClientBelongsToUserCompany(User $user, Client $client): void
    {
        if ($user->company_id !== $client->company_id) {
            throw ValidationException::withMessages([
                'client' => ['The selected client does not belong to your company.'],
            ]);
        }
    }
}

<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class ClientRepository
{
    public function queryForUser(User $user): Builder
    {
        return Client::query()
            ->where('company_id', $user->company_id)
            ->orderBy('name');
    }

    public function allForUser(User $user): Collection
    {
        return $this->queryForUser($user)->get();
    }

    public function paginateForUser(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return $this->queryForUser($user)->paginate($perPage);
    }

    public function createForUser(User $user, array $data): Client
    {
        return Client::create([
            'company_id' => $user->company_id,
            'name' => $data['name'],
        ]);
    }

    public function update(Client $client, array $data): Client
    {
        $client->fill([
            'name' => $data['name'],
        ]);

        $client->save();

        return $client;
    }

    public function delete(Client $client): void
    {
        $client->delete();
    }
}

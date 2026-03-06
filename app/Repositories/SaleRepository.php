<?php

namespace App\Repositories;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class SaleRepository
{
    public function queryForUser(User $user): Builder
    {
        return Sale::query()
            ->with(['client'])
            ->whereHas('client', function ($query) use ($user): void {
                $query->where('company_id', $user->company_id);
            })
            ->orderByDesc('created_at');
    }

    public function paginateForUser(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return $this->queryForUser($user)->paginate($perPage);
    }

    public function createForClient(int $clientId): Sale
    {
        return Sale::create([
            'client_id' => $clientId,
        ]);
    }
}

<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;

class SalePolicy
{
    /**
     * Determine whether the user can view any sales.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the sale.
     */
    public function view(User $user, Sale $sale): bool
    {
        return $user->company_id === $sale->client->company_id;
    }

    /**
     * Determine whether the user can approve the sale.
     */
    public function approve(User $user, Sale $sale): bool
    {
        return $user->company_id === $sale->client->company_id;
    }
}

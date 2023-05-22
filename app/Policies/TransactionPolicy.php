<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TransactionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Transaction $transaction): Response
    {
        return $user->id === $transaction->account->user_id
            ? Response::allow()
            : Response::denyWithStatus(404, "Transaction does not exists");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->accounts->count() > 0
            ? Response::allow()
            : Response::denyWithStatus(422, "No Accounts, found to transact in.");
    }
}

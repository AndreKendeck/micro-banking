<?php

namespace App\Observers;

use App\Enums\TransactionType;
use App\Models\Account;

class AccountObserver
{
    /**
     * Handle the Account "created" event.
     */
    public function created(Account $account): void
    {
        $account->credit(0, sprintf("Account Opened %s", now()->toDateTimeString()));
    }

    /**
     * Handle the Account "updated" event.
     */
    public function updated(Account $account): void
    {
        //
    }

    /**
     * Handle the Account "deleted" event.
     */
    public function deleted(Account $account): void
    {
        //
    }
}

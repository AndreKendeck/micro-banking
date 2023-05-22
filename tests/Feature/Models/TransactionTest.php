<?php

namespace Tests\Feature\Models;

use App\Enums\TransactionType;
use App\Models\Account;
use App\Models\Transaction;
use Tests\TestCase;

class TransactionTest extends TestCase
{

    /** @test */
    public function it_can_be_created()
    {
        $transaction = Transaction::factory()->create();
        $this->assertDatabaseHas('transactions', $transaction->getAttributes());
        $this->assertInstanceOf(Account::class, $transaction->account);
    }


    /** @test */
    public function debits_are_negative()
    {
        $transaction = Transaction::factory()->create(['type' => TransactionType::DEBIT->value]);
        $this->assertTrue($transaction->refresh()->amount->isNegative(), "The value of the debit is {$transaction->amount->isPositive()}");
    }

    /** @test */
    public function credits_are_positive()
    {
        $transaction = Transaction::factory()->create(['type' => TransactionType::CREDIT->value]);
        $this->assertTrue($transaction->refresh()->amount->isPositive());
    }
}

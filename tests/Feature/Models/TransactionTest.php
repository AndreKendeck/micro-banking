<?php

namespace Tests\Feature\Models;

use App\Enums\TransactionType;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        $this->assertTrue($transaction->amount->isNegative());
    }

    /** @test */
    public function credits_are_positive()
    {
        $transaction = Transaction::factory()->create(['type' => TransactionType::CREDIT->value]);
        $this->assertTrue($transaction->amount->isPositive());
    }
}

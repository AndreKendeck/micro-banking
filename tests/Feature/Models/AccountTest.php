<?php

namespace Tests\Feature\Models;

use App\Enums\AccountType;
use App\Enums\TransactionType;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Collection;
use Tests\TestCase;

class AccountTest extends TestCase
{

    /** @test */
    public function it_can_be_created()
    {
        /** @var Account */
        $account = Account::factory()->create();
        $this->assertDatabaseHas('accounts', $account->getAttributes());
        $this->assertDatabaseHas('transactions', [
            'type' => TransactionType::CREDIT->value,
            'amount' => 0,
            'description' => sprintf("Account Opened %s", now()->toDateTimeString()),
            'account_id' => $account->id
        ]);
        $this->assertTrue($account->isNew());
        $this->assertEquals($account->opening_balance, money(0));
    }


    /** @test */
    public function an_account_is_alway_created_with_9_digits()
    {
        $account = Account::factory()->create();
        $this->assertTrue(strlen($account->number) === 9);
    }


    /** @test */
    public function an_account_can_be_fetched_by_type()
    {
        /** @var Collection */
        Account::factory(10)->create();
        /** @var AccountType */
        $accountType = AccountType::from(
            fake()->randomElement(
                array_map(fn (AccountType $accountType) => $accountType->value, AccountType::cases())
            )
        );
        $fetchedAccounts = Account::byType($accountType)->get();
        $fetchedAccounts->each(fn (Account $account) => $this->assertEquals($account->type, $accountType->value));
    }


    /** @test */
    public function an_account_can_be_fetched_by_number()
    {
        /** @var Collection */
        $accounts = Account::factory(10)->create();
        /** @var Account */
        $randomAccount = $accounts->random(1)->first();
        $fetchedAccount = Account::byNumber($randomAccount->number)->first();
        $this->assertTrue($randomAccount->is($fetchedAccount));
    }


    /** @test */
    public function an_account_belongs_to_a_user()
    {
        $account = Account::factory()->create();
        $this->assertInstanceOf(User::class, $account->user);
    }


    /** @test */
    public function an_account_has_many_transactions()
    {
        $account = Account::factory()->create();
        $account->transactions->each(fn (Transaction $transaction) => $this->assertInstanceOf(Transaction::class, $transaction));
    }


    /** @test */
    public function an_account_can_be_credited()
    {
        $account = Account::factory()->create();
        $amountToCredit = rand(10, 1000);
        $this->travel(5)->minutes(function () use ($account, $amountToCredit) {
            /** @var Transaction $transaction */
            $transaction = $account->credit(money($amountToCredit, forceDecimals: true), "Random Credit to Account");
            $this->assertInstanceOf(Transaction::class, $transaction);
            $this->assertDatabaseHas('transactions', $transaction->getAttributes());
            $this->assertEquals(
                $account->closing_balance->getAmount(),
                $account->transactions->sum(fn (Transaction $transaction) => $transaction->amount->getAmount())
            );
        });
    }


    /** @test */
    public function an_account_can_be_debited()
    {
        $account = Account::factory()->create();
        $closingBalance = rand(10, 1000);
        /** @var Transaction $transaction */
        $account->credit(money($closingBalance, forceDecimals: true), "Random Credit to Account");
        $this->travel(5)->minutes(function () use ($closingBalance, $account) {
            $debitToAccount = rand(1, floor($closingBalance * 0.8));
            $transaction = $account->debit(money($debitToAccount, forceDecimals: true), "Random Charge to account");
            $this->assertInstanceOf(Transaction::class, $transaction);
            $this->assertDatabaseHas('transactions', $transaction->getAttributes());
        });
    }


    /** @test */
    public function an_cheq_or_savings_account_cannot_be_debitted_into_a_negavtive()
    {
        /** @var Account */
        $account = Account::factory()->create([
            'type' => fake()->randomElement([AccountType::SAVINGS->value, AccountType::CHEQ->value])
        ]);
        $transaction = $account->debit(money(rand(10, 1000), forceDecimals: true), "Random");
        $this->assertSoftDeleted('transactions', $transaction->getAttributes(), deletedAtColumn: 'voided_at');
        $this->assertTrue($transaction->isVoid());
        $this->assertEquals($account->refresh()->closing_balance->formatByDecimal(), '0.00');
    }


    /** @test */
    public function an_account_can_have_a_negative_balance_if_on_credit()
    {
        $account = Account::factory()->create([
            'type' => AccountType::CREDIT->value
        ]);
        $transaction = $account->debit(money(rand(10, 1000), forceDecimals: true), "Interest");
        $this->assertDatabaseHas('transactions', $transaction->getAttributes());
        $this->assertTrue($account->closing_balance->isNegative());
    }


    /** @test */
    public function voided_transactions_are_not_included_in_the_balance_calculation()
    {
        $account = Account::factory()->create();
        $transaction = $account->credit(money(700.90, forceDecimals: true), "False Money");
        $transaction->void();
        $transaction = $transaction->refresh();
        $this->assertSoftDeleted('transactions', $transaction->getAttributes(), deletedAtColumn: 'voided_at');
        $this->assertEquals($account->closing_balance->getAmount(), 0);
    }
}

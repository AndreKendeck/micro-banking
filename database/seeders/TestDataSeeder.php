<?php

namespace Database\Seeders;

use App\Enums\AccountType;
use App\Enums\TransactionType;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Database\Factories\AccountFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var User $admin */
        $admin = User::firstOrCreate([
            'name' => env('DEFAULT_USER_NAME', 'admin'),
            'password' => bcrypt(env('DEFAULT_USER_PASSWORD', 'password')),
            'email' => env('DEFAULT_USER_EMAIl', 'admin@mail.test')
        ]);

        Account::factory(rand(2, 5))->create([
            'user_id' => $admin->id,
            'created_at' => fake()->dateTimeBetween('-6 months')
        ]);

        User::factory(5)->create()->each(function (User $user) {
            Account::factory(rand(2, 5))->create([
                'user_id' => $user,
                'created_at' => now()->subMonths(7)
            ])->each(function (Account $account) {
                // lets give each account a good sum of money to work with from the start

                $account->credit(
                    fake()->randomFloat(0, 10000, 50000),
                    "Starting Balance",
                    now()->subMonths(6)
                );
                for ($i = 0; $i < rand(30, 50); $i++) {
                    $account = $account->refresh();
                    $occurence = new Carbon(fake()->dateTimeBetween('-6 months'));
                    $shouldDebit = fake()->boolean(75);
                    if ($shouldDebit) {
                        $account->debit(
                            fake()->randomFloat(1, $this->getMaxAmount($account, TransactionType::DEBIT, $occurence)),
                            "Debit on Account on: {$occurence->toDateTimeString()}",
                            $occurence
                        );
                    } else {
                        $account->credit(
                            fake()->randomFloat(1, $this->getMaxAmount($account, TransactionType::CREDIT, $occurence)),
                            "Credit on Account on: {$occurence->toDateTimeString()}",
                            $occurence
                        );
                    }
                }
            });
        });
    }

    /**
     * @return float
     */
    private function getMaxAmount(Account $account, TransactionType $transactionType, Carbon $occurence): float
    {
        switch ($transactionType) {
            case TransactionType::DEBIT:
                if ($account->isCredit()) {
                    return fake()->randomFloat(0, 10000, 50000);
                }
                return ((float) $account->getOpeningBalance($occurence)->getAmount() / 100);
            case TransactionType::CREDIT:
                return (
                    ($account->getOpeningBalance($occurence)->getAmount() * 0.8) / 100
                );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Enums\AccountType;
use App\Models\Account;
use App\Models\User;
use Carbon\Carbon;
use Cknow\Money\Money;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

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

        Account::factory()->count(rand(2, 5))->for($admin)->create();
        $users = User::factory(5)->create();

        $users->each(function (User $user) {
            Account::factory()->count(rand(2, 5))->for($user)->create();
        });

        foreach ($users as $user) {
            $user->accounts->each(function (Account $account) {
                // initial account credit
                $initialAmount = new Money(rand(10000, 50000), forceDecimals: true);

                $account->credit($initialAmount, "Initial Account Credit", now()->subMonths(8));

                for ($i = 0; $i < rand(30, 50); $i++) {
                    $shouldDebit = fake()->boolean(75);
                    $occurence = new Carbon(fake()->dateTimeBetween('-6 months'));
                    $ob = $account->refresh()->getOpeningBalance($occurence);
                    if ($shouldDebit) {
                        /**
                         * Lets have large amounts for debits as well 
                         */
                        $amount = new Money(rand(1, (int) $ob->absolute()->multiply(0.6)->formatByDecimal()), forceDecimals: true);
                        $account->debit(
                            $amount,
                            "Debit on account of {$amount->formatByCurrencySymbol()} on {$occurence->toDateTimeString()}",
                            $occurence
                        );
                    } else {
                        $amount = $ob->isNegative() ? $ob->absolute()->multiply(0.8) : $ob->multiply(0.8);
                        $amount = new Money($amount->formatByDecimal(), forceDecimals: true);
                        $account->credit(
                            $amount,
                            "Credit on account of {$amount->formatByCurrencySymbol()} on {$occurence->toDateTimeString()}",
                            $occurence
                        );
                    }
                }
            });
        }
    }
}

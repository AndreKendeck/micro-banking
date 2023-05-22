<?php

namespace App\Console\Commands;

use App\Models\Account;
use Cknow\Money\Money;
use Illuminate\Console\Command;

class SeedTransactionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:seed 
                            {account : The bank account number}
                            {--count=50 : How many transactions should be processed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed transactions into a specific account.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $accountNumber = $this->argument('account');
        $count = $this->option('count');
        /** @var Account */
        $account = Account::byNumber($accountNumber)->first();
        if (!$account) {
            $this->error("Error: Account: {$accountNumber} not found");
            return 1;
        }
        $this->newLine(3);
        $this->info("Transacting {$count} transactions into account: {$accountNumber}");
        $progress = $this->output->createProgressBar($count);
        for ($i = 0; $i < $count; $i++) {
            $shouldDebit = fake()->boolean(75);
            $shouldDebit
                ? $account->debit(
                    new Money(rand(1, 10000), forceDecimals: true),
                    "Console Generated Debit Transaction",
                )
                : $account->credit(
                    new Money(rand(1, 10000), forceDecimals: true),
                    "Console Generated Credit Transaction"
                );
            $progress->advance();
        }
        $progress->finish();
        $this->info("Transaction seeding complete.");
        return 0;
    }
}

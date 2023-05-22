<?php

namespace Database\Factories;

use App\Enums\TransactionType;
use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(array_map(fn (TransactionType $transactionType) => $transactionType->value, TransactionType::cases())),
            'amount' => fake()->randomFloat(2, 100,1000),
            'description' => fake()->words(10, true),
            'account_id' => Account::factory()->create()->id
        ];
    }
}

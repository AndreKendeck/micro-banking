<?php

namespace Database\Factories;

use App\Enums\AccountType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use \Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $accountType = fake()->randomElement(array_map(fn (AccountType $accountType) => $accountType->value, AccountType::cases()));
        return [
            'type' => $accountType,
            'name' => sprintf("%s %s Account",  fake()->colorName(), $accountType),
            'user_id' => User::factory()->create()->id,
        ];
    }
}

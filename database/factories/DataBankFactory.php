<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataBank>
 */
class DataBankFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number_account' => $this->faker->bankAccountNumber,
            'balance' => 0,
            'balance_special' => 100.00,
            'user_id' => \App\Models\User::factory(),
        ];
    }
}

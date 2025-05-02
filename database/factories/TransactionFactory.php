<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'data_bank_id' => null,
            'amount' => null,
            'type' => null,
            'user_id_receiver' => null,
        ];
    }

    public function deposit(): static
    {
        return $this->state(fn() => [
            'type' => 'deposit',
            'user_id_receiver' => null,
        ]);
    }

    public function transfer(): static
    {
        return $this->state(fn() => [
            'type' => 'transfer',
        ]);
    }
}

<?php

namespace App\Services\Transaction;

use App\Interfaces\Transaction\TransactionInterface;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TransactionService
{
    public function __construct(protected TransactionInterface $transactionRepository) {}

    public function all(): Collection
    {
        return $this->transactionRepository->all();
    }

    public function find(int $id): Transaction
    {
        return $this->transactionRepository->findById($id);
    }

    public function deposit(User $user, int $amount): Transaction
    {
        return $this->transactionRepository->create(
            Transaction::factory()->deposit()->make([
                'user_id' => $user->id,
                'data_bank_id' => $user->dataBank->id,
                'amount' => $amount,
            ])->toArray()
        );
    }

    public function transfer(User $sender, User $receiver, int $amount): Transaction
    {
        return $this->transactionRepository->create(
            Transaction::factory()->transfer()->make([
                'user_id' => $sender->id,
                'user_id_receiver' => $receiver->id,
                'data_bank_id' => $sender->dataBank->id,
                'amount' => $amount,
            ])->toArray()
        );
    }

    public function update(int $id, array $data): bool
    {
        return $this->transactionRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->transactionRepository->delete($id);
    }
}

<?php

namespace App\Services\Transaction;

use App\Interfaces\Transaction\TransactionInterface;
use App\Models\Transaction;
use App\Models\User;
use Dflydev\DotAccessData\Data;
use Illuminate\Database\Eloquent\Collection;
use App\Services\DataBank\DataBankService;

class TransactionService
{
    public function __construct(protected TransactionInterface $transactionRepository, protected DataBankService $dataBankService) {}

    public function all(): Collection
    {
        return $this->transactionRepository->all();
    }

    public function find(int $id): Transaction
    {
        return $this->transactionRepository->findById($id);
    }

    public function getTransactionsByUser(User $user): Collection
    {
        return $this->transactionRepository->getTransactionsByUser($user->id);
    }

    public function getLastTransactionsByUser(User $user): Collection
    {
        return $this->transactionRepository->getLastTransactionsByUser($user->id);
    }

    public function deposit(User $user, float $amount): Transaction
    {
        $result = $this->transactionRepository->create(
            Transaction::factory()->deposit()->make([
                'user_id' => $user->id,
                'data_bank_id' => $user->dataBank->id,
                'amount' => $amount,
            ])->toArray()
        );

        if ($result) {
            $this->dataBankService->update($user->dataBank->id, ['balance' => $user->dataBank->balance + $amount]);
        }

        return $result;
    }

    public function transfer(User $sender, User $receiver, float $amount): Transaction
    {
        $result = $this->transactionRepository->create(
            Transaction::factory()->transfer()->make([
                'user_id' => $sender->id,
                'user_id_receiver' => $receiver->id,
                'data_bank_id' => $sender->dataBank->id,
                'amount' => $amount,
            ])->toArray()
        );

        if ($result) {
            $this->dataBankService->update($sender->dataBank->id, ['balance' => $sender->dataBank->balance - $amount]);
        }

        return $result;
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

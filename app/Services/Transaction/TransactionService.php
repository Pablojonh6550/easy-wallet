<?php

namespace App\Services\Transaction;

use App\Interfaces\Transaction\TransactionInterface;
use App\Models\DataBank;
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
        $dataBank = $user->dataBank;
        $balanceSpecial = $dataBank->balance_special;
        $maxBalanceValue = 100;

        $valueForBalanceSpecial = 0;
        $balanceValue = $amount;

        if ($balanceSpecial < $maxBalanceValue) {
            $reporCheque = min($maxBalanceValue - $balanceSpecial, $amount);
            $valueForBalanceSpecial = $reporCheque;
            $balanceValue = $amount - $reporCheque;
        }

        $transaction = $this->transactionRepository->create(
            Transaction::factory()->deposit()->make([
                'user_id' => $user->id,
                'data_bank_id' => $dataBank->id,
                'amount' => $amount,
            ])->toArray()
        );

        if ($transaction) {
            $this->dataBankService->update($dataBank->id, [
                'balance' => $dataBank->balance + $balanceValue,
                'balance_special' => $balanceSpecial + $valueForBalanceSpecial,
            ]);
        }

        return $transaction;
    }


    public function transfer(User $sender, User $receiver, float $amount): ?Transaction
    {
        $dataBank = $sender->dataBank;
        $balance = $dataBank->balance;
        $overdraft = $dataBank->balance_special;

        if ($balance >= $amount) {
            $newBalance = $balance - $amount;
            $newOverdraft = $overdraft;
        } elseif (($balance + $overdraft) >= $amount) {
            $neededFromOverdraft = $amount - $balance;
            $newBalance = 0;
            $newOverdraft = $overdraft - $neededFromOverdraft;
        } else {
            return null;
        }

        $transaction = $this->transactionRepository->create(
            Transaction::factory()->transfer()->make([
                'user_id' => $sender->id,
                'user_id_receiver' => $receiver->id,
                'data_bank_id' => $dataBank->id,
                'amount' => $amount,
            ])->toArray()
        );

        if (!$transaction) {
            return null;
        }

        $this->dataBankService->update($dataBank->id, [
            'balance' => $newBalance,
            'balance_special' => $newOverdraft
        ]);

        $receiverDataBank = $receiver->dataBank;
        $this->dataBankService->update($receiverDataBank->id, [
            'balance' => $receiverDataBank->balance + $amount
        ]);

        return $transaction;
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

<?php

namespace App\Services\Transaction;

use App\Interfaces\Transaction\TransactionInterface;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use App\Services\DataBank\DataBankService;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Illuminate\Support\Facades\Auth;

class TransactionService
{
    public function __construct(protected TransactionInterface $transactionRepository, protected DataBankService $dataBankService) {}

    /**
     * Retrieve all transactions.
     *
     * @return Collection A collection of all transaction models.
     */
    public function all(): Collection
    {
        return $this->transactionRepository->all();
    }

    /**
     * Find a transaction by its ID.
     *
     * @param int $id The ID of the transaction to find.
     * @return Transaction The found transaction model instance.
     */
    public function find(int $id): Transaction
    {
        return $this->transactionRepository->findById($id);
    }

    /**
     * Gets all transactions made by a user, ordered by most recent.
     *
     * @param User $user The user to get transactions for.
     *
     * @return Collection A collection of transaction models ordered by most recent.
     */
    public function getTransactionsByUser(User $user): Collection
    {
        return $this->transactionRepository->getTransactionsByUser($user->id);
    }

    /**
     * Gets the last 5 transactions for a given user, ordered by most recent.
     * This includes both sent and received transactions.
     *
     * @param User $user The user to get last transactions for.
     *
     * @return Collection A collection of the last 5 transactions for the given user.
     */
    public function getLastTransactionsByUser(User $user): Collection
    {
        return $this->transactionRepository->getLastTransactionsByUser($user->id);
    }

    /**
     * Processes a deposit for a user by updating their data bank balances.
     * If the user's special balance is below a certain threshold, part of the
     * deposited amount is used to replenish it before updating the regular balance.
     *
     * @param User $user The user who is making the deposit.
     * @param float $amount The amount to deposit.
     *
     * @return Transaction The transaction record of the deposit.
     */
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


    /**
     * Transfers a specified amount from the sender to the receiver.
     *
     * This function checks if the sender has sufficient balance or a combination of balance
     * and overdraft to cover the transfer amount. If the transfer is possible, it updates
     * the sender's balance and overdraft, creates a transaction record, and credits the
     * receiver's account with the transferred amount.
     *
     * @param User $sender The user initiating the transfer.
     * @param User $receiver The user receiving the transfer.
     * @param float $amount The amount to be transferred.
     * 
     * @return Transaction|null The transaction record if the transfer is successful, 
     *                          otherwise null if the sender has insufficient funds.
     */
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

    /**
     * Updates a transaction record by its ID.
     *
     * @param int $id The ID of the transaction to update.
     * @param array $data The data to update the transaction with.
     *
     * @return bool True if the update was successful, false otherwise.
     */
    public function update(int $id, array $data): bool
    {
        return $this->transactionRepository->update($id, $data);
    }

    /**
     * Deletes a transaction by its ID.
     *
     * @param int $id The ID of the transaction to delete.
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function delete(int $id): bool
    {
        return $this->transactionRepository->delete($id);
    }

    /**
     * Reverses a transaction by its ID.
     *
     * @param int $transactionId The ID of the transaction to reverse.
     * @return Transaction|null The reversed transaction model instance, or null if the reversal fails.
     * @throws InvalidArgumentException If the transaction does not exist, or if the transaction is a reversal, or if there is not enough balance to reverse the transaction.
     */
    public function reverseTransactionById(int $transactionId): ?Transaction
    {
        return DB::transaction(function () use ($transactionId) {
            $transaction = $this->find($transactionId);

            if (!$transaction) {
                throw new InvalidArgumentException('Transação não encontrada.');
            }

            if ($transaction->type === 'reversal') {
                throw new InvalidArgumentException('Esta transação já é uma reversão e não pode ser revertida.');
            }

            $amount = $transaction->amount;
            $sender = $transaction->user;
            $senderDataBank = $sender->dataBank;

            if ($transaction->type === 'deposit') {
                if ($senderDataBank->balance < $amount) {
                    throw new InvalidArgumentException('Saldo insuficiente para reverter o depósito.');
                }

                $this->dataBankService->update($senderDataBank->id, [
                    'balance' => $senderDataBank->balance - $amount
                ]);
            }

            if ($transaction->type === 'transfer') {
                $receiver = $transaction->receiver;
                $receiverDataBank = $receiver->dataBank;


                $requester = Auth::user();
                $isRequesterReceiver = $requester->id === $transaction->user_id_receiver;
                $isRequesterSender = $requester->id === $transaction->user_id;

                if ($isRequesterReceiver && $receiverDataBank->balance < $amount) {
                    throw new InvalidArgumentException('Você não possui saldo suficiente para reverter a transferência recebida.');
                }

                if ($isRequesterSender && $senderDataBank->balance_special + $senderDataBank->balance < $amount) {
                    throw new InvalidArgumentException('Saldo insuficiente para receber a reversão desta transferência.');
                }

                // Debita do receptor
                $this->dataBankService->update($receiverDataBank->id, [
                    'balance' => $receiverDataBank->balance - $amount
                ]);

                // Credita novamente o remetente
                $this->dataBankService->update($senderDataBank->id, [
                    'balance' => $senderDataBank->balance + $amount
                ]);
            }

            return $this->transactionRepository->create([
                'user_id' => $sender->id,
                'user_id_receiver' => $transaction->user_id_receiver,
                'data_bank_id' => $senderDataBank->id,
                'type' => 'reversal',
                'amount' => $amount,
                'original_transaction_id' => $transaction->id,
            ]);
        });
    }
}

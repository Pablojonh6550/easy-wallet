<?php

namespace App\Repositories\Transaction;

use App\Repositories\BaseRepository;
use App\Interfaces\Transaction\TransactionInterface;
use App\Models\Transaction;

class TransactionRepository extends BaseRepository implements TransactionInterface
{
    public function __construct(protected Transaction $transaction) {}
}

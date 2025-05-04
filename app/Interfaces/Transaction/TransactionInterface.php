<?php

namespace App\Interfaces\Transaction;

use App\Interfaces\BaseInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface TransactionInterface extends BaseInterface
{
    public function getTransactionsByUser(int $id): Collection;
    public function getLastTransactionsByUser(int $id): Collection;
}

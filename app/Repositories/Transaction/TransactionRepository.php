<?php

namespace App\Repositories\Transaction;

use App\Repositories\BaseRepository;
use App\Interfaces\Transaction\TransactionInterface;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;

class TransactionRepository extends BaseRepository implements TransactionInterface
{
    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }

    public function getTransactionsByUser(int $id): Collection
    {
        return $this->model->where('user_id', $id)->get();
    }
    public function getLastTransactionsByUser(int $id): Collection
    {
        return $this->model->where('user_id', $id)->limit(5)->orderBy('created_at', 'desc')->get();
    }
}

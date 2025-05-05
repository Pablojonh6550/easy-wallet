<?php

namespace App\Repositories\DataBank;

use App\Repositories\BaseRepository;
use App\Interfaces\DataBank\DataBankInterface;
use App\Models\DataBank;

class DataBankRepository extends BaseRepository implements DataBankInterface
{
    public function __construct(DataBank $model)
    {
        parent::__construct($model);
    }

    /**
     * Find a DataBank by its account number.
     * 
     * @param int $account The account number to search for.
     * @return ?DataBank The found DataBank instance, or null if no record is found.
     */
    public function findByAccount(int $account): ?DataBank
    {
        return $this->model->where('number_account', $account)->first();
    }
}

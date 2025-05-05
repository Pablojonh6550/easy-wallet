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

    public function findByAccount(int $account): ?DataBank
    {
        return $this->model->where('number_account', $account)->first();
    }
}

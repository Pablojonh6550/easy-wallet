<?php

namespace App\Interfaces\DataBank;

use App\Interfaces\BaseInterface;
use App\Models\DataBank;

interface DataBankInterface extends BaseInterface
{
    public function findByAccount(int $account): ?DataBank;
}

<?php

namespace App\Repositories\DataBank;

use App\Repositories\BaseRepository;
use App\Interfaces\DataBank\DataBankInterface;
use App\Models\DataBank;

class DataBankRepository extends BaseRepository implements DataBankInterface
{
    public function __construct(protected DataBank $dataBank) {}
}

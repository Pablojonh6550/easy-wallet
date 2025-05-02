<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataBank extends Model
{
    protected $table = 'data_banks';

    protected $fillable = [
        'number_account',
        'balance',
        'balance_special',
        'user_id',
    ];
}

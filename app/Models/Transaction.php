<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'data_bank_id',
        'amount',
        'type',
        'user_id_receiver',
    ];
}

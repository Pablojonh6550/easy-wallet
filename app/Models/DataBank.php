<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataBank extends Model
{
    use HasFactory;

    protected $table = 'data_banks';

    protected $fillable = [
        'number_account',
        'balance',
        'balance_special',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

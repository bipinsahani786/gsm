<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    protected $table = 'bank_details';

    protected $fillable = [
        'user_id',
        'type',
        'account_holder',
        'account_number',
        'ifsc_code',
        'bank_name',
        'wallet_address',
        'network',
    ];
}

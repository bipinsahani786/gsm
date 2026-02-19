<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $table = 'withdrawals';

    protected $fillable = [
        'user_id',
        'wallet_type',
        'method',
        'amount',
        'fee',
        'final_amount',
        'account_details',
        'status',
        'admin_remark',
        'transaction_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

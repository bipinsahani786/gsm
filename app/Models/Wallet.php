<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Wallet extends Model
{
    protected $table = 'wallets';

    protected $fillable = [
        'user_id',
        'uid',
        'wallet_id',
        'income_wallet',
        'personal_wallet',
        'total_wallet',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $table = 'deposits';

    protected $fillable = [
        'user_id',
        'amount',
        'method',
        'utr_hash',
        'screenshot',
        'status',
        'remarks',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

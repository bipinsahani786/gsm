<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpgradeReward extends Model
{
    protected $table = 'upgrade_rewards';

    public $fillable = [
        'from_level_id',
        'to_level_id',
        'reward_amount',
        'start_date',
        'end_date',
        'status',
    ];
}

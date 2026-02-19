<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $table = 'positions';
    protected $fillable = [
        'name',
        'salary',
        'team_condition',
        'required_members',
        'icon',
        'status',
    ];
}

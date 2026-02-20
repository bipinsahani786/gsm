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
        'required_level_id',
        'required_directs',
        'icon',
        'status',
    ];

    public function level()
    {
        return $this->belongsTo(Level::class, 'required_level_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}

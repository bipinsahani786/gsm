<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeMethod extends Model
{
    protected $table = 'income_methods';

    protected $fillable = [
        'title',
        'thumbnail',
        'pdf_file',
        'status',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    public $fillable = [
        'app_name',
        'app_logo',
        'is_installed'
    ];
}

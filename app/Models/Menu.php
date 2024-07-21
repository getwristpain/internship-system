<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'route',
        'permission_id',
    ];

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}

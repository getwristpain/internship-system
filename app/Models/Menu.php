<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'label',
        'route',
        'icon',
        'roles',
        'parent_id',
        'order',
    ];

    protected $casts = [
        'roles' => 'array', // To handle the roles as an array
    ];

    // Define relationship for submenus
    public function submenus()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    // Parent menu item
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    // Scope to get only root menus (i.e., menus without a parent)
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }
}

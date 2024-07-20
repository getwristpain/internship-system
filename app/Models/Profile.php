<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'avatar',
        'id_number',
        'position',
        'class',
        'school_year',
        'address',
        'phone',
        'birth_place',
        'birth_date',
        'gender',
        'blood_type',
        'parent_name',
        'parent_address',
        'parent_phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'id_number',
        'class',
        'school_year',
        'avatar',
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

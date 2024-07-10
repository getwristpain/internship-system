<?php

namespace App\Models\Users;

use App\Models\Internship;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'id_number',
        'position',
        'avatar',
        'address',
        'phone',
        'birth_place',
        'birth_date',
        'gender',
        'blood_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function internships()
    {
        return $this->belongsToMany(Internship::class, 'internship_teacher');
    }
}

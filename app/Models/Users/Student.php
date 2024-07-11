<?php

namespace App\Models\Users;

use App\Models\Attendance;
use App\Models\Guidance;
use App\Models\Internship;
use App\Models\Journal;
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

    public function internships()
    {
        return $this->belongsToMany(Internship::class, 'internship_student');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function journals()
    {
        return $this->hasMany(Journal::class);
    }

    public function guidances()
    {
        return $this->hasMany(Guidance::class);
    }
}

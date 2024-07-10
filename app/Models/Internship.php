<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'company_id',
        'quota',
        'registration_start',
        'registration_end',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function students()
    {
        return $this->belongsToMany(Users\Student::class, 'internship_student');
    }

    public function teachers()
    {
        return $this->belongsToMany(Users\Teacher::class, 'internship_teacher');
    }

    public function supervisors()
    {
        return $this->belongsToMany(Users\Supervisor::class, 'internship_supervisor');
    }
}

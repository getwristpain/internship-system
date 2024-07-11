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
        'location',
        'quota',
        'description',
        'requirements',
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

    public function testimonies()
    {
        return $this->hasMany(Testimony::class);
    }

    public function progress()
    {
        return $this->hasMany(Progress::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function guidances()
{
    return $this->hasMany(Guidance::class);
}
}

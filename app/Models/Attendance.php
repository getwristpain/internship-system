<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'internship_id',
        'attendance_date',
        'status',
        'remark',
        'approved_by_supervisor',
        'approved_by_teacher',
    ];

    public function student()
    {
        return $this->belongsTo(Users\Student::class, 'student_id');
    }

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }
}

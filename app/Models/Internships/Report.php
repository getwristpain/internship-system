<?php

namespace App\Models\Internships;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'internship_reports';

    protected $fillable = [
        'student_id',
        'internship_id',
        'program_id',
        'file_path',
        'verified_by',
        'final_grade',
        'graduation_status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'verified_by');
    }
}

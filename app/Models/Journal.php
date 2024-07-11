<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'internship_id',
        'journal_date',
        'competencies',
        'topics',
        'character_values',
        'remark',
        'approved_by_teacher',
        'approved_by_supervisor',
    ];

    public function student()
    {
        return $this->belongsTo(Users\Student::class);
    }

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }
}

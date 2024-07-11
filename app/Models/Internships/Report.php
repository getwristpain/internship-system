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
}

<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'internship_id',
        'report_id',
        'file_path',
        'verified_status',
        'verified_by_teacher',
    ];
}

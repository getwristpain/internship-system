<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guidance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'internship_id',
        'content',
        'remark',
        'approved_by_teacher',
        'approved_by_supervisor',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function internship()
    {
        return $this->belongsTo(Program::class, 'internship_id');
    }
}

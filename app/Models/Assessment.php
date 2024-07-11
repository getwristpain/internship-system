<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'internship_id',
        'score',
        'remark',
    ];

    public function aspects()
    {
        return $this->belongsToMany(Assessments\Aspect::class, 'assessment_aspect', 'assessment_id', 'aspect_id');
    }

    public function indicators()
    {
        return $this->hasManyThrough(Assessments\Indicator::class, Assessments\Aspect::class);
    }
}

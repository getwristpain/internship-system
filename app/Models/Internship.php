<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Internship extends Model
{
    protected $fillable = [
        'program_id',
        'company_id',
        'teacher_id',
        'supervisor_id',
        'status_id',
        'date_start',
        'date_finish'
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'internship_students', 'internship_id', 'student_id');
    }
}

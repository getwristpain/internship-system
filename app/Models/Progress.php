<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'internship_id',
        'stage_id',
        'status',
        'start_date',
        'end_date',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'start_date',
        'end_date',
        'registration_start',
        'registration_end',
    ];

    public function internships()
    {
        return $this->hasMany(Internship::class);
    }
}

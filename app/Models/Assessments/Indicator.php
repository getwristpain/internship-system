<?php

namespace App\Models\Assessments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    use HasFactory;

    protected $table = 'assessment_indicators';

    protected $fillable = [
        'aspect_id',
        'name',
        'description',
    ];

    public function aspect()
    {
        return $this->belongsTo(Aspect::class);
    }
}

<?php

namespace App\Models\Assessments;

use App\Models\Assessment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aspect extends Model
{
    use HasFactory;

    protected $table = 'assessment_aspects';

    protected $fillable = [
        'name',
        'description',
    ];

    public function indicators()
    {
        return $this->hasMany(Indicator::class);
    }

    public function assessments()
    {
        return $this->belongsToMany(Assessment::class, 'assessment_aspect', 'aspect_id', 'assessment_id');
    }
}

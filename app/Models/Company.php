<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'fax',
        'email',
        'contact_person',
        'principal_name',
    ];

    public function supervisors()
    {
        return $this->hasMany(Users\Supervisor::class);
    }
}

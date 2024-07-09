<?php

namespace App\Models\Users;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'position',
        'email',
        'phone',
        'access_key',
        'access_status',
        'access_expires_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}

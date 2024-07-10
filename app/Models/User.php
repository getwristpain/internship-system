<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the log associated with the user.
     */
    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    /**
     * Get the role associated with the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the student associated with the user.
     */
    public function student()
    {
        return $this->belongsTo(Users\Student::class);
    }

    /**
     * Get the teacher associated with the user.
     */
    public function teacher()
    {
        return $this->belongsTo(Users\Teacher::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function testimonies()
    {
        return $this->hasMany(Testimony::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}

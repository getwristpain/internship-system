<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (User $model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Uuid::uuid4();
            }
        });
    }

    // Method to check if the user's email is verified
    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }

    // Method to mark the user's email as verified
    public function markEmailAsVerified(): self
    {
        $this->email_verified_at = now();
        $this->save();

        return $this;
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function accessKey(): HasOne
    {
        return $this->hasOne(AccessKey::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_user');
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }

    public function journals(): HasMany
    {
        return $this->hasMany(Journal::class);
    }

    public function mentorships(): HasMany
    {
        return $this->hasMany(Mentorship::class);
    }

    public function internshipReports(): HasMany
    {
        return $this->hasMany(InternshipReport::class);
    }
}

<?php

namespace App\Models;

use App\Listeners\AssignAuthorRole;
use Ramsey\Uuid\Uuid;
use Illuminate\Auth\Events\Registered;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $listen = [
        Registered::class => [
            AssignAuthorRole::class,
        ],
    ];

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Ensure the model's key is set to a UUID if not already set
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Uuid::uuid4();
            }
        });
    }
    /**
     * Assign roles to the user.
     *
     * @param array $roles
     * @return void
     */
    public function assignRoles(array $roles)
    {
        // Define roles that should automatically grant Author role
        $rolesThatGrantAuthor = ['Owner', 'Admin'];

        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            if (!$this->hasRole($roleName)) {
                $this->assignRole($roleName);
            }
        }

        // Check if the user has any of the roles that grant Author
        $this->grantToRole('Author', $rolesThatGrantAuthor);
    }

    private function grantToRole($name, $roles) {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                $grantToRole = Role::firstOrCreate(['name' => $name]);
                if (!$this->hasRole($grantToRole)) {
                    $this->assignRole($grantToRole);
                }
                break;
            }
        }
    }

    /**
     * Get the profile associated with the user.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }
}

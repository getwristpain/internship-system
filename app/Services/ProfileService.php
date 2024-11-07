<?php

namespace App\Services;

use App\Models\Profile;
use Laravolt\Avatar\Facade as Avatar;

class ProfileService
{
    public static function setDefaultProfile($user)
    {
        Profile::create([
            'user_id' => $user->id,
            'avatar' => Avatar::create($user->name)->toBase64(),
        ]);
    }
}

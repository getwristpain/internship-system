<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AvatarController extends Controller
{
    public function show($userId, $filename)
    {
        $filePath = "avatars/{$userId}/{$filename}";

        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Define the allowed roles
        $allowedRoles = ['Author', 'Staff'];

        if ($user->id !== $userId && !in_array($user->roles->pluck('name')->first(), $allowedRoles)) {
            abort(403, 'Unauthorized');
        }

        if (Storage::disk('private')->exists($filePath)) {
            return response()->file(Storage::disk('private')->path($filePath));
        } else {
            abort(404, 'File not found');
        }
    }
}

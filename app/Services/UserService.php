<?php

namespace App\Services;

use App\Models\User;
use App\Helpers\StatusBadgeMapper;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public static function findUser(string $userId)
    {
        return User::with(['roles', 'status', 'profile'])
            ->find($userId) ?: null;
    }

    public static function getUsers()
    {
        return User::with(['roles', 'status', 'profile'])
            ->orderBy('created_at', 'desc')
            ->get() ?: null;
    }

    /**
     * Get paginated users data with optional role filtering and search functionality.
     *
     * @param string|null $search
     * @param array $roles
     * @param int $perPage
     * @return \Illuminate\Support\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getPaginatedUsers(string $search = null, array $roles = [], int $perPage = 20)
    {
        $query = User::with(['accessKey', 'roles', 'status', 'profile'])
            ->where('users.id', '!=', Auth::id());  // Exclude current authenticated user

        // Apply role filtering if roles are provided
        if (!empty($roles)) {
            $query->role($roles);  // Spatie's role filter
        }

        // Apply search filter if search query is provided
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', '%' . $search . '%')
                    ->orWhere('users.email', 'like', '%' . $search . '%');
            });
        }

        // Join the roles for ordering and select the necessary columns
        $query->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->select('users.*') // Ensure to select users to avoid ambiguity
            ->orderByRaw("CASE WHEN roles.name = 'owner' THEN 0
                            WHEN roles.name = 'student' THEN 2
                            WHEN roles.name = 'teacher' THEN 3
                            WHEN roles.name = 'supervisor' THEN 4
                            WHEN roles.name = 'staff' THEN 5
                            WHEN roles.name = 'admin' THEN 6
                            ELSE 1 END")
            ->orderBy('users.created_at', 'desc')
            ->groupBy('users.id');

        // Paginate the results
        $users = $query->paginate($perPage);

        if (!$users->isEmpty()) {
            foreach ($users->items() as $user) {
                if ($user->status) {
                    // Add badgeClass inside the status object
                    $user->status->badgeClass = StatusBadgeMapper::getStatusBadgeClass($user->status->name);
                }
            }
        }

        return $users->isEmpty() ? collect() : $users;
    }
}

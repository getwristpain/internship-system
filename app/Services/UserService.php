<?php

namespace App\Services;

use App\Models\User;
use App\Helpers\StatusBadgeMapper;
use Illuminate\Support\Facades\Auth;

class UserService
{
    /**
     * Get paginated users data with optional role filtering and search functionality.
     *
     * @param array $roles
     * @param string|null $search
     * @return \Illuminate\Support\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function paginatedUsers(string $search = null, array $roles = [], int $perPage = 20)
    {
        $query = User::with(['roles', 'status', 'profile'])
            ->where('id', '!=', Auth::id())  // Exclude current authenticated user
            ->groupBy('users.id');

        // Apply role filtering if roles are provided
        if (!empty($roles)) {
            $query->role($roles);  // Spatie's role filter
        }

        // Apply search filter if search query is provided
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Order by user's name only, as SQLite doesn't support complex order by like MAX(CASE...)
        $query->orderBy('users.name');

        // Paginate the results
        $users = $query->paginate($perPage);

        // If the result is not empty, map through each user to add badge class inside status
        if (!$users->isEmpty()) {
            $users->getCollection()->transform(function ($user) {
                if ($user->status) {
                    // Add badgeClass inside the status object
                    $user->status->badgeClass = StatusBadgeMapper::getStatusBadgeClass($user->status->name);
                }
                return $user;
            });
        }

        return $users->isEmpty() ? collect() : $users;
    }
}

<?php

namespace App\Services;

use App\Models\User;
use App\Helpers\StatusBadgeMapper;
use Illuminate\Support\Facades\Auth;

class UserService extends Service
{
    public function __construct()
    {
        parent::__construct(new User());
    }

    /**
     * Get a user by ID with relationships.
     *
     * @param string $id
     * @return \App\Models\User|null
     */
    public function getUserById(string $id)
    {
        return $this->getByIdWithRelations($id, ['roles', 'status']);
    }

    /**
     * Get all users with relationships, ordered by created_at.
     *
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public function getUsers()
    {
        return $this->cacheQuery(function ($query) {
            return $query->with(['roles', 'status'])
                ->orderBy('created_at', 'desc')
                ->get();
        });
    }

    /**
     * Get paginated users data with optional role filtering and search functionality.
     *
     * @param string|null $search
     * @param array $roles
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection
     */
    public function getPaginatedUsers(string $search = null, array $roles = [], int $perPage = 20)
    {
        return $this->cacheQuery(function ($query) use ($search, $roles, $perPage) {
            $query = $this->model->with(['accessKey', 'roles', 'status'])
                ->where('users.id', '!=', Auth::id());

            if (!empty($roles)) {
                $query->role($roles);
            }

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('users.name', 'like', '%' . $search . '%')
                        ->orWhere('users.email', 'like', '%' . $search . '%');
                });
            }

            $query->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->select('users.*')
                ->orderByRaw("CASE WHEN roles.name = 'owner' THEN 0
                                    WHEN roles.name = 'student' THEN 2
                                    WHEN roles.name = 'teacher' THEN 3
                                    WHEN roles.name = 'supervisor' THEN 4
                                    WHEN roles.name = 'staff' THEN 5
                                    WHEN roles.name = 'admin' THEN 6
                                    ELSE 1 END")
                ->orderBy('users.created_at', 'desc')
                ->groupBy('users.id');

            $users = $query->paginate($perPage);

            if (!$users->isEmpty()) {
                foreach ($users->items() as $user) {
                    if ($user->status) {
                        $user->status->badgeClass = StatusBadgeMapper::getStatusBadgeClass($user->status->name);
                    }
                }
            }

            return $users->isEmpty() ? collect() : $users;
        });
    }
}

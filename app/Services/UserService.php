<?php

namespace App\Services;

use App\Models\User;
use App\Models\Status;
use App\Services\Service;
use App\Services\StatusService;
use Illuminate\Support\Facades\Validator;

class UserService extends Service
{
    protected array $userData = [
        'roles' => ['guest']
    ];

    protected array $with = ['roles', 'status', 'department', 'classroom'];

    protected StatusService $statusService;

    public function __construct()
    {
        parent::__construct(new User());
        $this->statusService = new StatusService();
    }

    public function prepUserData($userData): array
    {
        return array_merge(
            [
                'roles' => $this->setRoles($userData['roles']),
                'status_id' => $this->setStatus($userData['status'])->id,
            ],
            $userData
        );
    }

    protected function setRoles(...$roles): array
    {
        return $roles ?? $this->userData['roles'];
    }

    protected function setStatus(array $statusData = []): ?Status
    {
        $setStatusData = array_merge([
            'code' => 'user-status-pending',
            'type' => 'user-status',
            'name' => 'Pending',
            'description' => 'Pengguna ini aktif dan memiliki akses ke sistem.',
        ], $statusData);

        return $this->statusService->findOne(['code', $setStatusData['code']], createable: true, data: $setStatusData);
    }

    public function rules(?string $userId = null, array $rules = []): array
    {
        $emailRules = 'required|email|unique:users,email';

        if ($userId) {
            $emailRules = 'required|email|unique:users,email,' . $userId;
        }

        return array_merge([
            'name' => 'required|string|min:5|max:255',
            'email' => $emailRules,
            'password' => 'required|string|confirmed',
            'status_id' => 'required|integer',
            'department_id' => 'nullable|integer',
            'classroom_id' => 'nullable|integer',
        ], $rules);
    }

    public function validate(array $userData, ?string $userId = null, array $rules = [], array $messages = [], array $attributes = []): bool
    {
        $validator = Validator::make($userData, $this->rules($userId, $rules), $messages, $attributes);

        if ($validator->fails()) {
            throw new \Exception($validator->getMessageBag()->toJson());
        }

        return true;
    }

    public function findUser(string $userId, array $with = []): ?User
    {
        return $this->getById($userId, array_merge($this->with, $with));
    }

    public function findUserByRoles(...$roles): ?User
    {
        try {
            return $this->cacheQuery(function ($query) use ($roles) {
                return $query->roles($roles)->first();
            }, 'find_user_by_roles', $roles);
        } catch (\Throwable $th) {
            $this->logger('error', 'Failed to retrieve user data based on roles.', $th);
            throw $th;
        }
    }

    public function getUsers(): ?\Illuminate\Database\Eloquent\Collection
    {
        return $this->getAll(['status', 'department', 'classroom']);
    }

    public function createUser(array $userData): User
    {
        $userData = $this->prepUserData($userData);

        if ($this->validate($userData)) {
            $createdUser = $this->create($userData);
            if (!empty($createdUser) && !empty($userData['roles'])) {
                $createdUser->syncRoles($userData['roles']);
            }

            return $createdUser;
        }
    }

    public function updateUser(string $userId, array $userData): User
    {
        $userData = $this->prepUserData($userData);
        $updatedUser = $this->update($userId, $userData);
        if (!empty($updatedUser) && !empty($userData['roles'])) {
            $updatedUser->syncRoles($userData['roles']);
        }

        return $updatedUser;
    }

    public function deleteUser(string $userId): bool
    {
        return $this->delete($userId);
    }
}

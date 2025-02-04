<?php

namespace App\Services;

use App\Models\User;
use App\Services\Service;

class OwnerService extends Service
{
    protected array $defaultRoles = ['owner', 'admin'];

    protected UserService $userService;

    public function __construct()
    {
        $ownerModel = new User();
        $ownerModel->syncRoles($this->defaultRoles);

        parent::__construct($ownerModel, 'owner');
    }

    public function getOwner(): ?User
    {
        return $this->userService->findUserByRoles('owner');
    }

    public function createOwner($ownerData): User
    {
        $ownerData['roles'] = $this->defaultRoles;

        if ($this->userService->validate($ownerData)) {
            return $this->userService->createUser($ownerData);
        }
    }
}

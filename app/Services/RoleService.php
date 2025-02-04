<?php

namespace App\Services;

use App\DynamicMethodCaller;
use App\Services\Service;
use Spatie\Permission\Models\Role;

class RoleService extends Service
{
    use DynamicMethodCaller;

    public function __construct()
    {
        parent::__construct(new Role());
        $this->setDynamicMethods(['role' => $this->model]);
    }
}

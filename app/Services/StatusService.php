<?php

namespace App\Services;

use App\Models\Status;
use App\Services\Service;

class StatusService extends Service
{
    public function __construct()
    {
        parent::__construct(new Status(), 'status', 60);
    }
}

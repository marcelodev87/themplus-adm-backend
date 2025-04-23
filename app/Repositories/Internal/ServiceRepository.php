<?php

namespace App\Repositories\Internal;

use App\Models\Internal\Service;

class ServiceRepository
{
    protected $model;

    public function __construct(Service $coupon)
    {
        $this->model = $coupon;
    }

    public function getAll()
    {
        return $this->model->orderBy('name', 'asc')->get();
    }

}

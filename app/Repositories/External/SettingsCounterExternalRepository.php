<?php

namespace App\Repositories\External;

use App\Models\External\SettingsCounterExternal;

class SettingsCounterExternalRepository
{
    protected $model;

    public function __construct(SettingsCounterExternal $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }
}

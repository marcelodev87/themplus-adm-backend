<?php

namespace App\Repositories\External;

use App\Models\External\AccountExternal;

class AccountExternalRepository
{
    protected $model;

    public function __construct(AccountExternal $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }
}

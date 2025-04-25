<?php

namespace App\Repositories\External;

use App\Models\External\SubscriptionExternal;

class SubscriptionExternalRepository
{
    protected $model;

    public function __construct(SubscriptionExternal $model)
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

    public function findByName($name)
    {
        return $this->model->where('name', $name)->first();
    }

    public function update($id, array $data)
    {
        $subscription = $this->findById($id);
        if ($subscription) {
            $subscription->update($data);

            return $subscription;
        }

        return null;
    }
}

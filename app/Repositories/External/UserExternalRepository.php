<?php

namespace App\Repositories\External;

use App\Models\External\UserExternal;

class UserExternalRepository
{
    protected $model;

    public function __construct(UserExternal $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getMembersByEnterprise($id)
    {
        return $this->model->where('enterprise_id', $id)->get();
    }

    public function updateMemberUser($id, array $data)
    {
        $member = $this->findById($id);
        if($member){
            $member->update($data);
        }
        return $member;
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

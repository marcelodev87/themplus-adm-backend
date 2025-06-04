<?php

namespace App\Repositories\External;

use App\Models\External\UserExternal;
use Illuminate\Support\Facades\DB;

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
        if ($member) {
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

    public function delete($id)
    {
        $member = $this->findById($id);
        if ($member) {
            DB::connection('external')->table('notifications')->where('user_id', $id)->delete();
            DB::connection('external')->table('registers')->where('user_id', $id)->delete();
            DB::connection('external')->table('feedbacks')->where('user_id', $id)->delete();

            return $member->delete();
        }

        return false;
    }
}

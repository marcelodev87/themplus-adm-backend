<?php

namespace App\Services\External;

use App\Repositories\External\UserExternalRepository;
use App\Rules\External\UserExternalRule;

class UserExternalService
{
    protected $userExternalRepository;
    protected $ruleExternal;

    public function __construct(
        UserExternalRule $ruleExternal,
        UserExternalRepository $userExternalRepository
    ){
        $this->userExternalRepository = $userExternalRepository;
        $this->ruleExternal = $ruleExternal;
    }

    public function updateMemberUser($request)
    {
        $this->ruleExternal->updateMemberUser($request);

        $data = $request->only(['name', 'email', 'position', 'phone', 'active']);

        return $this->userExternalRepository->updateMemberUser($request->id, $data);
    }
}

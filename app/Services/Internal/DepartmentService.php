<?php

namespace App\Services\Internal;

use App\Helpers\DepartmentHelper;
use App\Repositories\Internal\DepartmentRepository;
use App\Rules\DepartmentRule;

class DepartmentService
{
    protected $rule;

    protected $repository;

    public function __construct(
        DepartmentRule $rule,
        DepartmentRepository $repository,
    ) {
        $this->rule = $rule;
        $this->repository = $repository;
    }

    public function create($request)
    {
        $this->rule->create($request);

        DepartmentHelper::existsDepartment(
            null,
            $request->input('name'),
            'create'
        );

        $data = $request->only(['name']);
        $data['parent_id'] = $request->input('parentId') ?? null;

        return $this->repository->create($data);
    }

    public function update($request)
    {
        $this->rule->update($request);

        DepartmentHelper::existsDepartment(
            $request->input('id'),
            $request->input('name'),
            'update'
        );

        $data = $request->only(['name']);
        $data['parent_id'] = $request->input('parentId') ?? null;

        return $this->repository->update($request->input('id'), $data);
    }
}

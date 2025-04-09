<?php

namespace App\Helpers;

use App\Models\Internal\Department;
use App\Models\Internal\User;
use App\Repositories\Internal\DepartmentRepository;
use App\Repositories\Internal\UserRepository;

class DepartmentHelper
{
    public static function existsDepartment($id, $name, $enterpriseId, $mode)
    {
        $userRepository = new UserRepository(new User);
        $departmentRepository = new DepartmentRepository(new Department, $userRepository);

        $department = $departmentRepository->findByName($name, $enterpriseId);
        if ($mode === 'create') {
            if ($department) {
                throw new \Exception('Já existe um departamento igual ou parecido');
            }
        } else {
            if ($department && $department->id !== $id) {
                throw new \Exception('Já existe um departamento igual ou parecido');
            }
        }
    }
}

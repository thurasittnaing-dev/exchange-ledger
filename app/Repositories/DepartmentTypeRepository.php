<?php

namespace App\Repositories;

use App\Interfaces\DepartmentTypeRepositoryInterface;
use App\Models\Master\Department;
use Illuminate\Database\Eloquent\Builder;

class DepartmentTypeRepository implements DepartmentTypeRepositoryInterface
{
    public function getData(): Builder
    {
        return Department::query();
    }
    public function createDepartmentType(array $data): Department
    {
        return Department::create($data);
    }
    public function updateDepartmentType(Department $departmentType, array $data): Department
    {
            $departmentType->update($data);
            return $departmentType;
    }
    public function deleteDepartmentType(Department $departmentType): void
    {
        $departmentType->delete();
    }

    public function createSubDepartment(array $data, Department $department): Department
    {
        $data['parent_id'] = $department->id;

        return Department::create($data);
    }
}

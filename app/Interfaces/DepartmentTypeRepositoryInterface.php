<?php

namespace App\Interfaces;

use App\Models\Master\Department;
use Illuminate\Database\Eloquent\Builder;

interface DepartmentTypeRepositoryInterface
{
    public function getData(): Builder;
    public function createDepartmentType(array $data): Department;
    public function updateDepartmentType(Department $departmentType, array $data): Department;
    public function deleteDepartmentType(Department $departmentType): void;

    public function createSubDepartment(array $data, Department $department): Department;
}

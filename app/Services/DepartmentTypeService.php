<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Master\Department;
use App\Repositories\DepartmentTypeRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class DepartmentTypeService
{
    public function __construct(
        private readonly DepartmentTypeRepository $repository
    ) {}

    public function getData(): Builder
    {
        return $this->repository->getData();
    }

    public function store(array $data): array
    {
        try {
            DB::beginTransaction();
            $this->repository->createDepartmentType($data);

            DB::commit();
            return [
                'status' => true,
                'message' => 'Department type created successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "Department Type Create Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function update(Department $departmentType, array $data): array
    {
        try {
            DB::beginTransaction();
            $departmentType = $this->repository->updateDepartmentType($departmentType, $data);

            DB::commit();
            return [
                'status' => true,
                'message' => 'Department type updated successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "Department Type Update Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function delete(Department $departmentType): array
    {
        try {
            DB::beginTransaction();
            $this->repository->deleteDepartmentType($departmentType);

            DB::commit();
            return [
                'status' => true,
                'message' => 'Department type deleted successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            showError($e, "Department Type Delete Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function storeSubDepartment(array $data, Department $department): array
    {
        try {
            $this->repository->createSubDepartment($data, $department);

            return [
                'status' => true,
                'message' => 'Sub department created successfully.'
            ];
        } catch (\Exception $e) {
            showError($e, "Sub Department Create Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during sub department creation.'
            ];
        }
    }
}

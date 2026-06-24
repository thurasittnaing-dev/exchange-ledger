<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\PermissionRepositoryInterface;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

final class PermissionService
{
    public function __construct(
        private readonly PermissionRepositoryInterface $repository
    ) {}

    public function getData($relations = []): Builder
    {
        return $this->repository->getData($relations);
    }

    public function store(array $data): array
    {
        try {
            $this->repository->createPermission($data);
            return [
                'status' => true,
                'message' => 'Permission created successfully.'
            ];
        } catch (\Exception $e) {
            showError($e, "Permission Create Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function update(Permission $permission, array $data): array
    {
        try {
            $this->repository->updatePermission($permission, $data);
            return [
                'status' => true,
                'message' => 'Permission updated successfully.'
            ];
        } catch (\Exception $e) {
            showError($e, "Permission Update Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }

    public function delete(Permission $permission): array
    {
        try {
            $this->repository->deletePermission($permission);
            return [
                'status' => true,
                'message' => 'Permission deleted successfully.'
            ];
        } catch (\Exception $e) {
            showError($e, "Permission Delete Error: ");
            return [
                'status' => false,
                'message' => 'Something went wrong during creation.'
            ];
        }
    }
}

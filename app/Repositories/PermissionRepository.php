<?php

namespace App\Repositories;

use App\Interfaces\PermissionRepositoryInterface;
use App\Models\Management\Permission;
use Illuminate\Database\Eloquent\Builder;

class PermissionRepository  implements PermissionRepositoryInterface
{
    public function getData($relations = []): Builder
    {
        return Permission::query()->with($relations)->latest();
    }

    public function createPermission(array $data): Permission
    {
        return Permission::create($data);
    }

    public function updatePermission(Permission $permission, array $data): Permission
    {
        $permission->update($data);
        return $permission;
    }

    public function deletePermission(Permission $permission): void
    {
        $permission->delete();
    }
}

<?php

namespace App\Interfaces;

use App\Models\Management\Permission;
use Illuminate\Database\Eloquent\Builder;

interface PermissionRepositoryInterface
{
    public function getData($relations = []): Builder;

    public function createPermission(array $data): Permission;

    public function updatePermission(Permission $permission, array $data): Permission;

    public function deletePermission(Permission $permission): void;
}

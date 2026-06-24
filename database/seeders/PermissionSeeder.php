<?php

namespace Database\Seeders;

use App\Models\Management\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = array(
            [
                'module' => 'User',
                'name' => 'View',
                'code' => 'user-list',
            ],
            [
                'module' => 'User',
                'name' => 'Create',
                'code' => 'user-create',
            ],
            [
                'module' => 'User',
                'name' => 'Edit',
                'code' => 'user-edit',
            ],
            [
                'module' => 'User',
                'name' => 'Delete',
                'code' => 'user-delete',
            ],
            [
                'module' => 'User',
                'name' => 'Export',
                'code' => 'user-export',
            ],
            [
                'module' => 'Permission',
                'name' => 'View',
                'code' => 'permission-list',
            ],
            [
                'module' => 'Permission',
                'name' => 'Create',
                'code' => 'permission-create',
            ],
            [
                'module' => 'Permission',
                'name' => 'Edit',
                'code' => 'permission-edit',
            ],
            [
                'module' => 'Permission',
                'name' => 'Delete',
                'code' => 'permission-delete',
            ],
            [
                'module' => 'Permission',
                'name' => 'Export',
                'code' => 'permission-export',
            ],
            [
                'module' => 'Bank Type',
                'name' => 'View',
                'code' => 'bank-type-list',
            ],
            [
                'module' => 'Bank Type',
                'name' => 'Create',
                'code' => 'bank-type-create',
            ],
            [
                'module' => 'Bank Type',
                'name' => 'Edit',
                'code' => 'bank-type-edit',
            ],
            [
                'module' => 'Bank Type',
                'name' => 'Delete',
                'code' => 'bank-type-delete',
            ],
            [
                'module' => 'Bank Type',
                'name' => 'Export',
                'code' => 'bank-type-export',
            ],
            [
                'module' => 'Account',
                'name' => 'View',
                'code' => 'account-list',
            ],
            [
                'module' => 'Account',
                'name' => 'Create',
                'code' => 'account-create',
            ],
            [
                'module' => 'Account',
                'name' => 'Edit',
                'code' => 'account-edit',
            ],
            [
                'module' => 'Account',
                'name' => 'Delete',
                'code' => 'account-delete',
            ],
            [
                'module' => 'Account',
                'name' => 'Export',
                'code' => 'account-export',
            ],
            [
                'module' => 'Account Balance',
                'name' => 'View',
                'code' => 'account-balance-list',
            ],
            [
                'module' => 'Account Balance',
                'name' => 'Create',
                'code' => 'account-balance-create',
            ],
            [
                'module' => 'Account Balance',
                'name' => 'Export',
                'code' => 'account-balance-export',
            ],
            [
                'module' => 'Cash Money',
                'name' => 'View',
                'code' => 'cash-money-list',
            ],
            [
                'module' => 'Cash Money',
                'name' => 'Create',
                'code' => 'cash-money-create',
            ],
            [
                'module' => 'Cash Money',
                'name' => 'Export',
                'code' => 'cash-money-export',
            ],
        );

        foreach ($permissions as $permission) Permission::create($permission);
    }
}

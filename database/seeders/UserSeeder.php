<?php

namespace Database\Seeders;

use App\Models\Management\Permission;
use App\Models\Management\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = array(
            [
                'name' => 'Developer',
                'username' => 'developer',
                'role' => 'super_admin',
                'password' => bcrypt('P@ssw0rd2026#'),
                'created_at' => now(),
            ],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'role' => 'admin',
                'password' => bcrypt('P@ssw0rd2026'),
                'created_at' => now(),
            ],
        );

        User::insert($users);

        $allPermissionIds = Permission::pluck('id');

        User::where('id', 1)->first()->permissions()->sync($allPermissionIds);
        User::where('id', 2)->first()->permissions()->sync($allPermissionIds);
    }
}

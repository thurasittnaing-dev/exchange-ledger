<?php

namespace Database\Seeders;

use Database\Seeders\DepartmentTypeSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            UserSeeder::class,
            BankTypeSeeder::class,
            AccountSeeder::class,
            TestingSeeder::class,
        ]);
    }
}

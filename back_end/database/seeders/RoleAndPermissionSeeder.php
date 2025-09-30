<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;


class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userRole = Role::firstOrCreate(
    ['name' => 'user'],
    ['display_name' => 'User', 'description' => 'Normal app user']
);
$managerRole = Role::firstOrCreate(
    ['name' => 'manager'],
    ['display_name' => 'Manager', 'description' => 'Manager user']
);
$adminRole = Role::firstOrCreate(
    ['name' => 'admin'],
    ['display_name' => 'Administrator', 'description' => 'Full access admin']
);

    }
}

<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'manage_users', 'description' => 'Manage user accounts'],
            ['name' => 'manage_customers', 'description' => 'Manage customer accounts'],
            ['name' => 'manage_products', 'description' => 'Manage products'],
            ['name' => 'manage_roles', 'description' => 'Manage roles and permissions'],
            ['name' => 'manage_sales', 'description' => 'Manage sales transactions'],
            ['name' => 'view_reports', 'description' => 'View system reports'],
            ['name' => 'manage_settings', 'description' => 'Manage system settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate($permission);
        }
    }
}

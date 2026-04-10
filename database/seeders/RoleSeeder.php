<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'description' => 'Full system access',
                'permissions' => ['manage_users', 'manage_customers', 'manage_products', 'manage_roles', 'manage_sales', 'view_reports', 'manage_settings']
            ],
            [
                'name' => 'Sales Manager',
                'description' => 'Manage sales and customers',
                'permissions' => ['manage_customers', 'manage_sales', 'view_reports']
            ],
            [
                'name' => 'Product Manager',
                'description' => 'Manage products and inventory',
                'permissions' => ['manage_products', 'view_reports']
            ],
            [
                'name' => 'Sales Staff',
                'description' => 'Handle sales transactions',
                'permissions' => ['manage_sales']
            ],
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);

            $role = Role::firstOrCreate(['name' => $roleData['name']], $roleData);

            $permissionIds = Permission::whereIn('name', $permissions)->pluck('id');
            $role->permissions()->sync($permissionIds);
        }
    }
}

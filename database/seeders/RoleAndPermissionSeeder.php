<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Sales role
        $salesRole = Role::firstOrCreate(
            ['name' => 'sales'],
            ['description' => 'Sales staff with access to customer data']
        );

        // Create customer-related permissions
        $permissions = [
            ['name' => 'view_customers', 'description' => 'View customer list'],
            ['name' => 'view_customer_details', 'description' => 'View detailed customer information'],
            ['name' => 'edit_customers', 'description' => 'Edit customer information'],
            ['name' => 'view_sales_dashboard', 'description' => 'Access sales dashboard'],
            ['name' => 'view_transactions', 'description' => 'View sales transactions'],
        ];

        foreach ($permissions as $permissionData) {
            $permission = Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                ['description' => $permissionData['description']]
            );
            
            // Attach all permissions to sales role
            $salesRole->permissions()->syncWithoutDetaching($permission->id);
        }

        $this->command->info('Sales role and permissions created successfully.');
    }
}

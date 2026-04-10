<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Administrator with full access']
        );

        $userRole = Role::firstOrCreate(
            ['name' => 'user'],
            ['description' => 'Regular user']
        );

        $moderatorRole = Role::firstOrCreate(
            ['name' => 'moderator'],
            ['description' => 'Moderator with limited admin tasks']
        );

        // Create permissions
        $permissions = [
            ['name' => 'view.dashboard', 'description' => 'View admin dashboard'],
            ['name' => 'manage.users', 'description' => 'Create, edit, delete users'],
            ['name' => 'view.users', 'description' => 'View users list'],
            ['name' => 'manage.products', 'description' => 'Create, edit, delete products'],
            ['name' => 'view.products', 'description' => 'View products list'],
            ['name' => 'manage.roles', 'description' => 'Manage roles and permissions'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm['name']], ['description' => $perm['description']]);
        }

        // Assign permissions to admin role (all permissions)
        $adminPermissions = Permission::all();
        $adminRole->permissions()->sync($adminPermissions->pluck('id')->toArray());

        // Assign permissions to moderator role
        $moderatorPermissions = Permission::whereIn('name', [
            'view.dashboard',
            'view.users',
            'view.products',
        ])->get();
        $moderatorRole->permissions()->sync($moderatorPermissions->pluck('id')->toArray());

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $admin->syncRoles([$adminRole->id]);

        // Create some demo regular users
        User::factory(5)->create()->each(function ($user) use ($userRole) {
            $user->syncRoles([$userRole->id]);
        });

        // Create some demo products
        $products = [
            [
                'name' => 'Laptop',
                'description' => 'High-performance laptop',
                'sku' => 'LAPTOP-001',
                'price' => 999.99,
                'stock' => 15,
                'category' => 'Electronics',
            ],
            [
                'name' => 'Mouse',
                'description' => 'Wireless mouse',
                'sku' => 'MOUSE-001',
                'price' => 29.99,
                'stock' => 50,
                'category' => 'Accessories',
            ],
            [
                'name' => 'Keyboard',
                'description' => 'Mechanical keyboard',
                'sku' => 'KEYBOARD-001',
                'price' => 79.99,
                'stock' => 30,
                'category' => 'Accessories',
            ],
            [
                'name' => 'Monitor',
                'description' => '4K Ultra HD Monitor',
                'sku' => 'MONITOR-001',
                'price' => 399.99,
                'stock' => 8,
                'category' => 'Electronics',
            ],
            [
                'name' => 'Headphones',
                'description' => 'Noise-cancelling headphones',
                'sku' => 'HEADPHONES-001',
                'price' => 149.99,
                'stock' => 25,
                'category' => 'Audio',
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['sku' => $product['sku']],
                array_merge($product, ['is_active' => true])
            );
        }
    }
}

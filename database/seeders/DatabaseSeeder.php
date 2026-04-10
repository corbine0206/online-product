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
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            CustomerSeeder::class,
            SalesTransactionSeeder::class,
        ]);

        // Create admin user
        $adminRole = Role::where('name', 'Super Admin')->first();
        if ($adminRole) {
            $admin = User::firstOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'name' => 'Admin User',
                    'password' => Hash::make('password'),
                    'is_active' => true,
                ]
            );
            $admin->syncRoles([$adminRole->id]);
        }

        // Create some demo regular users
        $userRole = Role::where('name', 'Sales Staff')->first();
        if ($userRole) {
            User::factory(5)->create()->each(function ($user) use ($userRole) {
                $user->syncRoles([$userRole->id]);
            });
        }

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

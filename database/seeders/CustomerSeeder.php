<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@example.com',
                'phone' => '+1-555-0123',
                'address' => '123 Main St',
                'city' => 'New York',
                'state' => 'NY',
                'postal_code' => '10001',
                'country' => 'USA',
                'birth_date' => '1985-05-15',
                'status' => 'active',
                'total_purchases' => 1250.75,
                'last_purchase_at' => now()->subDays(5),
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.johnson@example.com',
                'phone' => '+1-555-0124',
                'address' => '456 Oak Ave',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'postal_code' => '90001',
                'country' => 'USA',
                'birth_date' => '1990-08-22',
                'status' => 'active',
                'total_purchases' => 890.50,
                'last_purchase_at' => now()->subDays(12),
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Brown',
                'email' => 'michael.brown@example.com',
                'phone' => '+1-555-0125',
                'address' => '789 Pine Rd',
                'city' => 'Chicago',
                'state' => 'IL',
                'postal_code' => '60601',
                'country' => 'USA',
                'birth_date' => '1988-03-10',
                'status' => 'active',
                'total_purchases' => 2100.00,
                'last_purchase_at' => now()->subDays(2),
            ],
            [
                'first_name' => 'Emily',
                'last_name' => 'Davis',
                'email' => 'emily.davis@example.com',
                'phone' => '+1-555-0126',
                'address' => '321 Elm St',
                'city' => 'Houston',
                'state' => 'TX',
                'postal_code' => '77001',
                'country' => 'USA',
                'birth_date' => '1992-11-30',
                'status' => 'inactive',
                'total_purchases' => 450.25,
                'last_purchase_at' => now()->subMonths(3),
            ],
            [
                'first_name' => 'Robert',
                'last_name' => 'Wilson',
                'email' => 'robert.wilson@example.com',
                'phone' => '+1-555-0127',
                'address' => '654 Maple Dr',
                'city' => 'Phoenix',
                'state' => 'AZ',
                'postal_code' => '85001',
                'country' => 'USA',
                'birth_date' => '1987-07-18',
                'status' => 'suspended',
                'total_purchases' => 3200.80,
                'last_purchase_at' => now()->subMonths(6),
            ],
        ];

        foreach ($customers as $customer) {
            Customer::firstOrCreate(['email' => $customer['email']], $customer);
        }
    }
}

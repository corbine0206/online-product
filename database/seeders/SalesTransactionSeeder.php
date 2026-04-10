<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\SalesTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalesTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();
        
        if ($customers->isEmpty()) {
            return;
        }

        $transactions = [
            [
                'customer_email' => 'john.smith@example.com',
                'subtotal' => 1000.00,
                'tax_amount' => 80.00,
                'discount_amount' => 50.00,
                'payment_method' => 'credit_card',
                'payment_status' => 'completed',
                'status' => 'completed',
                'notes' => 'Online order - Electronics',
                'transaction_date' => now()->subDays(5),
            ],
            [
                'customer_email' => 'sarah.johnson@example.com',
                'subtotal' => 750.00,
                'tax_amount' => 60.00,
                'discount_amount' => 20.00,
                'payment_method' => 'cash',
                'payment_status' => 'completed',
                'status' => 'completed',
                'notes' => 'In-store purchase - Clothing',
                'transaction_date' => now()->subDays(12),
            ],
            [
                'customer_email' => 'michael.brown@example.com',
                'subtotal' => 1500.00,
                'tax_amount' => 120.00,
                'discount_amount' => 100.00,
                'payment_method' => 'bank_transfer',
                'payment_status' => 'completed',
                'status' => 'completed',
                'notes' => 'Bulk order - Office supplies',
                'transaction_date' => now()->subDays(2),
            ],
            [
                'customer_email' => 'emily.davis@example.com',
                'subtotal' => 300.00,
                'tax_amount' => 24.00,
                'discount_amount' => 0.00,
                'payment_method' => 'debit_card',
                'payment_status' => 'completed',
                'status' => 'completed',
                'notes' => 'Regular purchase - Home goods',
                'transaction_date' => now()->subMonths(3),
            ],
            [
                'customer_email' => 'robert.wilson@example.com',
                'subtotal' => 2000.00,
                'tax_amount' => 160.00,
                'discount_amount' => 200.00,
                'payment_method' => 'online_payment',
                'payment_status' => 'completed',
                'status' => 'completed',
                'notes' => 'Premium purchase - Luxury items',
                'transaction_date' => now()->subMonths(6),
            ],
            [
                'customer_email' => 'john.smith@example.com',
                'subtotal' => 250.00,
                'tax_amount' => 20.00,
                'discount_amount' => 10.00,
                'payment_method' => 'credit_card',
                'payment_status' => 'pending',
                'status' => 'pending',
                'notes' => 'Pending order - Accessories',
                'transaction_date' => now()->subDays(1),
            ],
        ];

        foreach ($transactions as $transactionData) {
            $customer = $customers->where('email', $transactionData['customer_email'])->first();
            
            if ($customer) {
                $transaction = new SalesTransaction([
                    'transaction_number' => SalesTransaction::generateTransactionNumber(),
                    'subtotal' => $transactionData['subtotal'],
                    'tax_amount' => $transactionData['tax_amount'],
                    'discount_amount' => $transactionData['discount_amount'],
                    'total_amount' => $transactionData['subtotal'] + $transactionData['tax_amount'] - $transactionData['discount_amount'],
                    'payment_method' => $transactionData['payment_method'],
                    'payment_status' => $transactionData['payment_status'],
                    'status' => $transactionData['status'],
                    'notes' => $transactionData['notes'],
                    'transaction_date' => $transactionData['transaction_date'],
                ]);
                
                $transaction->customer()->associate($customer);
                $transaction->save();
            }
        }
    }
}

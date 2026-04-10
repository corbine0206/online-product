<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalesTransaction;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'start_date' => 'nullable|date|before_or_equal:end_date|before_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date|before_or_equal:today',
        ], [
            'start_date.before_or_equal' => 'Start date must be before or equal to end date',
            'end_date.after_or_equal' => 'End date must be after or equal to start date',
            'before_or_equal' => 'Date cannot be in the future',
        ]);

        // Get date range from request or default to last 30 days
        $startDate = $validated['start_date'] ?? Carbon::now()->subDays(30)->format('Y-m-d');
        $endDate = $validated['end_date'] ?? Carbon::now()->format('Y-m-d');
        
        // Security: Limit date range to prevent performance issues
        $maxDays = 365;
        $dateDiff = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));
        if ($dateDiff > $maxDays) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Date range cannot exceed {$maxDays} days for performance reasons.");
        }

        // Basic Statistics
        $totalSales = SalesTransaction::where('status', 'completed')
            ->sum('total_amount');

        $totalTransactions = SalesTransaction::where('status', 'completed')
            ->count();

        $averageTransactionValue = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;

        $totalCustomers = Customer::count();

        // Daily Sales Data for Chart
        $dailySales = SalesTransaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->selectRaw('DATE(transaction_date) as date, SUM(total_amount) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Payment Method Breakdown
        $paymentMethods = SalesTransaction::where('status', 'completed')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('payment_method')
            ->get();

        // Top Customers
        $topCustomers = SalesTransaction::where('status', 'completed')
            ->selectRaw('customer_id, SUM(total_amount) as total_sales, COUNT(*) as transaction_count')
            ->groupBy('customer_id')
            ->orderByDesc('total_sales')
            ->take(10)
            ->get()
            ->map(function($item) {
                $customer = Customer::find($item->customer_id);
                if ($customer) {
                    $customer->total_sales = $item->total_sales;
                    $customer->transaction_count = $item->transaction_count;
                    return $customer;
                }
                return null;
            })
            ->filter(function($customer) {
                return $customer !== null;
            });

        // Monthly Comparison (Last 6 months)
        $monthlyComparison = SalesTransaction::where('transaction_date', '>=', Carbon::now()->subMonths(6))
            ->where('status', 'completed')
            ->selectRaw('YEAR(transaction_date) as year, MONTH(transaction_date) as month, SUM(total_amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Recent Transactions
        $recentTransactions = SalesTransaction::with('customer')
            ->where('status', 'completed')
            ->orderBy('transaction_date', 'desc')
            ->take(10)
            ->get();

        return view('admin.sales-dashboard.index', compact(
            'totalSales',
            'totalTransactions',
            'averageTransactionValue',
            'totalCustomers',
            'dailySales',
            'paymentMethods',
            'topCustomers',
            'monthlyComparison',
            'recentTransactions',
            'startDate',
            'endDate'
        ));
    }

    public function exportReport(Request $request, $format)
    {
        // Validate format parameter
        if (!in_array($format, ['pdf', 'excel'])) {
            return redirect()->back()->with('error', 'Invalid export format');
        }

        // Validate other input
        $validated = $request->validate([
            'start_date' => 'nullable|date|before_or_equal:end_date|before_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date|before_or_equal:today',
        ], [
            'start_date.before_or_equal' => 'Start date must be before or equal to end date',
            'end_date.after_or_equal' => 'End date must be after or equal to start date',
        ]);
        $startDate = $validated['start_date'] ?? Carbon::now()->subDays(30)->format('Y-m-d');
        $endDate = $validated['end_date'] ?? Carbon::now()->format('Y-m-d');
        
        // Security: Limit date range to prevent performance issues
        $maxDays = 365;
        $dateDiff = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));
        if ($dateDiff > $maxDays) {
            return redirect()->back()
                ->with('error', "Date range cannot exceed {$maxDays} days for export.");
        }
        
        // Rate limiting: Prevent excessive exports
        $cacheKey = 'export_' . auth()->id() . '_' . $format;
        if (cache()->has($cacheKey)) {
            return redirect()->back()
                ->with('error', 'Please wait before generating another report. Reports can be generated once per minute.');
        }
        cache()->put($cacheKey, true, 60); // 1 minute cache

        $transactions = SalesTransaction::with('customer')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->orderBy('transaction_date', 'desc')
            ->get();

        if ($format === 'excel') {
            return $this->exportExcel($transactions, $startDate, $endDate);
        } else {
            return $this->exportPDF($transactions, $startDate, $endDate);
        }
    }

    private function exportPDF($transactions, $startDate, $endDate)
    {
        // For now, return a simple view that can be printed
        return view('admin.sales-dashboard.pdf-report', compact(
            'transactions',
            'startDate',
            'endDate'
        ));
    }

    private function exportExcel($transactions, $startDate, $endDate)
    {
        // For now, return a CSV download
        $filename = "sales-report-{$startDate}-to-{$endDate}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'Transaction ID',
                'Transaction Number',
                'Customer Name',
                'Customer Email',
                'Total Amount',
                'Payment Method',
                'Status',
                'Transaction Date',
                'Notes'
            ]);

            // CSV Data
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->id,
                    $transaction->transaction_number,
                    $transaction->customer->full_name,
                    $transaction->customer->email,
                    $transaction->total_amount,
                    $transaction->payment_method,
                    $transaction->status,
                    $transaction->transaction_date->format('Y-m-d H:i:s'),
                    $transaction->notes
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\SalesTransaction;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SalesTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = SalesTransaction::with('customer')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('sales-transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::where('status', 'active')->get();
        return view('sales-transactions.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,credit_card,debit_card,bank_transfer,online_payment',
            'notes' => 'nullable|string|max:1000',
            'transaction_date' => 'required|date',
        ]);

        $validated['total_amount'] = $validated['subtotal'] + $validated['tax_amount'] - $validated['discount_amount'];
        $validated['transaction_number'] = SalesTransaction::generateTransactionNumber();
        $validated['payment_status'] = 'pending';
        $validated['status'] = 'pending';

        $transaction = SalesTransaction::create($validated);

        return redirect()->route('admin.sales-transactions.show', $transaction)
            ->with('success', 'Sales transaction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesTransaction $salesTransaction)
    {
        $salesTransaction->load('customer');
        return view('sales-transactions.show', compact('salesTransaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesTransaction $salesTransaction)
    {
        $customers = Customer::where('status', 'active')->get();
        $salesTransaction->load('customer');
        return view('sales-transactions.edit', compact('salesTransaction', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesTransaction $salesTransaction)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,credit_card,debit_card,bank_transfer,online_payment',
            'payment_status' => 'required|string|max:50',
            'status' => 'required|in:pending,completed,cancelled,refunded',
            'notes' => 'nullable|string|max:1000',
            'transaction_date' => 'required|date',
        ]);

        $validated['total_amount'] = $validated['subtotal'] + $validated['tax_amount'] - $validated['discount_amount'];

        $salesTransaction->update($validated);

        return redirect()->route('admin.sales-transactions.show', $salesTransaction)
            ->with('success', 'Sales transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesTransaction $salesTransaction)
    {
        if ($salesTransaction->status === 'completed') {
            return redirect()->route('admin.sales-transactions.index')
                ->with('error', 'Cannot delete completed sales transaction.');
        }

        $salesTransaction->delete();

        return redirect()->route('admin.sales-transactions.index')
            ->with('success', 'Sales transaction deleted successfully.');
    }
}

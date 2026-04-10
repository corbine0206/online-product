@extends('admin.layout')

@section('title', 'Customer Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Customer Details</h5>
                    <div>
                        <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Personal Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>{{ $customer->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Full Name:</strong></td>
                                    <td>{{ $customer->full_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $customer->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $customer->phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Birth Date:</strong></td>
                                    <td>{{ $customer->birth_date ? $customer->birth_date->format('M d, Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $customer->status === 'active' ? 'success' : ($customer->status === 'inactive' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($customer->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Address Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Address:</strong></td>
                                    <td>{{ $customer->address ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>City:</strong></td>
                                    <td>{{ $customer->city ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>State:</strong></td>
                                    <td>{{ $customer->state ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Postal Code:</strong></td>
                                    <td>{{ $customer->postal_code ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Country:</strong></td>
                                    <td>{{ $customer->country ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Financial Summary</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Total Purchases:</strong></td>
                                    <td>${{ number_format($customer->total_purchases, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Purchase:</strong></td>
                                    <td>{{ $customer->last_purchase_at ? $customer->last_purchase_at->format('M d, Y H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Transactions:</strong></td>
                                    <td>{{ $customer->sales_transactions_count }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Account Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $customer->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Updated:</strong></td>
                                    <td>{{ $customer->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <h6>Sales Transactions</h6>
                    @if($customer->sales_transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Transaction #</th>
                                        <th>Date</th>
                                        <th>Total Amount</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->sales_transactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->transaction_number }}</td>
                                            <td>{{ $transaction->transaction_date->format('M d, Y') }}</td>
                                            <td>${{ number_format($transaction->total_amount, 2) }}</td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('sales-transactions.show', $transaction) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No sales transactions found for this customer.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

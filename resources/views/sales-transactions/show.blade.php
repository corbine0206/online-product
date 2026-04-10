@extends('admin.layout')

@section('title', 'Sales Transaction Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sales Transaction Details</h5>
                    <div>
                        <a href="{{ route('admin.sales-transactions.edit', $salesTransaction) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.sales-transactions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Transaction Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Transaction #:</strong></td>
                                    <td>{{ $salesTransaction->transaction_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Customer:</strong></td>
                                    <td>
                                        <a href="{{ route('admin.customers.show', $salesTransaction->customer) }}">
                                            {{ $salesTransaction->customer->full_name }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Customer Email:</strong></td>
                                    <td>{{ $salesTransaction->customer->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Transaction Date:</strong></td>
                                    <td>{{ $salesTransaction->transaction_date->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Method:</strong></td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $salesTransaction->payment_method)) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Status:</strong></td>
                                    <td>
                                        <span class="badge bg-info">{{ $salesTransaction->payment_status }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $salesTransaction->status === 'completed' ? 'success' : ($salesTransaction->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($salesTransaction->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Financial Details</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Subtotal:</strong></td>
                                    <td>${{ number_format($salesTransaction->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tax Amount:</strong></td>
                                    <td>${{ number_format($salesTransaction->tax_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Discount Amount:</strong></td>
                                    <td>${{ number_format($salesTransaction->discount_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Amount:</strong></td>
                                    <td><strong>${{ number_format($salesTransaction->total_amount, 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($salesTransaction->notes)
                        <hr>
                        <h6>Notes</h6>
                        <p>{{ $salesTransaction->notes }}</p>
                    @endif

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Account Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $salesTransaction->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Updated:</strong></td>
                                    <td>{{ $salesTransaction->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

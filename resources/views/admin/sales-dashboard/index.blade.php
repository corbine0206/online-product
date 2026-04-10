@extends('admin.layout')

@section('title', 'Sales Dashboard')

@section('extra-styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-card {
            border-left: 4px solid #3498db;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
        .chart-container {
            position: relative;
            height: 300px;
        }
        .date-filter-form {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        @media (max-width: 768px) {
            .chart-container {
                height: 250px;
            }
            .stat-card {
                margin-bottom: 1rem;
            }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Date Filter -->
    <div class="date-filter-form">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}" required>
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}" required>
            </div>
            <div class="col-md-6">
                <div class="btn-group w-100" role="group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Apply Filter
                    </button>
                    <a href="{{ route('admin.sales-dashboard.index') }}" class="btn btn-secondary">
                        <i class="fas fa-undo"></i> Reset
                    </a>
                    <button type="button" onclick="window.print()" class="btn btn-info">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 class="card-title text-muted">Total Sales</h5>
                    <h3 class="text-primary">${{ number_format($totalSales, 2) }}</h3>
                    <small class="text-muted">From {{ $startDate }} to {{ $endDate }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 class="card-title text-muted">Transactions</h5>
                    <h3 class="text-success">{{ $totalTransactions }}</h3>
                    <small class="text-muted">Completed transactions</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 class="card-title text-muted">Avg. Transaction</h5>
                    <h3 class="text-warning">${{ number_format($averageTransactionValue, 2) }}</h3>
                    <small class="text-muted">Per transaction value</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 class="card-title text-muted">Total Customers</h5>
                    <h3 class="text-info">{{ $totalCustomers }}</h3>
                    <small class="text-muted">Registered customers</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Daily Sales Trend</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="dailySalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Payment Methods</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="paymentMethodsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Transactions</h5>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.sales-dashboard.export', ['format' => 'pdf']) }}?start_date={{ $startDate }}&end_date={{ $endDate }}" class="btn btn-outline-primary">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                        <a href="{{ route('admin.sales-dashboard.export', ['format' => 'excel']) }}?start_date={{ $startDate }}&end_date={{ $endDate }}" class="btn btn-outline-success">
                            <i class="fas fa-file-excel"></i> Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Transaction #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->transaction_number }}</td>
                                        <td>{{ $transaction->customer->full_name }}</td>
                                        <td class="fw-bold">${{ number_format($transaction->total_amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ ucfirst($transaction->payment_method) }}</span>
                                        </td>
                                        <td>{{ $transaction->transaction_date->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No transactions found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top Customers</h5>
                </div>
                <div class="card-body">
                    @forelse($topCustomers as $customer)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="fw-bold">{{ $customer->full_name }}</div>
                                <small class="text-muted">{{ $customer->transaction_count }} transactions</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-primary">${{ number_format($customer->total_sales, 2) }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">No customer data available</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-scripts')
    <script>
        // Daily Sales Chart
        const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
        const dailySalesData = @json($dailySales);
        
        new Chart(dailySalesCtx, {
            type: 'line',
            data: {
                labels: dailySalesData.map(item => item.date),
                datasets: [{
                    label: 'Daily Sales',
                    data: dailySalesData.map(item => parseFloat(item.total)),
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Payment Methods Chart
        const paymentMethodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
        const paymentMethodsData = @json($paymentMethods);
        
        new Chart(paymentMethodsCtx, {
            type: 'doughnut',
            data: {
                labels: paymentMethodsData.map(item => item.payment_method),
                datasets: [{
                    data: paymentMethodsData.map(item => item.count),
                    backgroundColor: [
                        '#3498db',
                        '#2ecc71',
                        '#f39c12',
                        '#e74c3c',
                        '#9b59b6'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
@endsection

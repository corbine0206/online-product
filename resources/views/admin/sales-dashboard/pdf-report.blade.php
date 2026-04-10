<!DOCTYPE html>
<html>
<head>
    <title>Sales Report - {{ $startDate }} to {{ $endDate }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 12px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #3498db;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }
        .header h2 {
            margin: 10px 0 0 0;
            font-size: 16px;
            color: #7f8c8d;
        }
        .report-info {
            text-align: center;
            margin-bottom: 30px;
            color: #666;
            font-size: 14px;
        }
        .summary {
            margin-bottom: 30px;
        }
        .summary h3 {
            margin-bottom: 15px;
            font-size: 16px;
            color: #2c3e50;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .summary-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .summary-table td:first-child {
            font-weight: bold;
            width: 50%;
        }
        .summary-table td:last-child {
            text-align: right;
            font-weight: bold;
        }
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .transaction-table th, 
        .transaction-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        .transaction-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 11px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 11px;
            page-break-inside: avoid;
        }
        .status-completed {
            background-color: #d4edda;
            color: #155724;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sales Report</h1>
        <h2>Online Product Management System</h2>
    </div>

    <div class="report-info">
        <strong>Report Period:</strong> {{ $startDate }} to {{ $endDate }}<br>
        <strong>Generated on:</strong> {{ now()->format('F d, Y H:i:s') }}
    </div>

    <div class="summary">
        <h3>Summary</h3>
        <table class="summary-table">
            <tr>
                <td>Total Transactions:</td>
                <td class="text-right">{{ $transactions->count() }}</td>
            </tr>
            <tr>
                <td>Total Sales:</td>
                <td class="text-right">${{ number_format($transactions->sum('total_amount'), 2) }}</td>
            </tr>
            <tr>
                <td>Average Transaction:</td>
                <td class="text-right">${{ number_format($transactions->avg('total_amount'), 2) }}</td>
            </tr>
        </table>
    </div>

    <h3>Transaction Details</h3>
    <table class="transaction-table">
        <thead>
            <tr>
                <th>Transaction #</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Amount</th>
                <th>Payment Method</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->transaction_number }}</td>
                    <td>{{ $transaction->customer->full_name }}</td>
                    <td>{{ $transaction->customer->email }}</td>
                    <td class="text-right">${{ number_format($transaction->total_amount, 2) }}</td>
                    <td>{{ ucfirst($transaction->payment_method) }}</td>
                    <td>
                        @if($transaction->status === 'completed')
                            <span class="status-completed">Completed</span>
                        @elseif($transaction->status === 'pending')
                            <span class="status-pending">Pending</span>
                        @else
                            <span class="status-failed">{{ ucfirst($transaction->status) }}</span>
                        @endif
                    </td>
                    <td>{{ $transaction->transaction_date->format('M d, Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No transactions found for the selected period</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>This report was generated automatically from the Online Product Management System.</p>
        <p>For questions or concerns, please contact the system administrator.</p>
    </div>
</body>
</html>

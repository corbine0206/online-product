<!DOCTYPE html>
<html>
<head>
    <title>Sales Report - {{ $startDate }} to {{ $endDate }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #3498db;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .report-info {
            text-align: center;
            margin-bottom: 30px;
            color: #666;
        }
        .summary {
            margin-bottom: 30px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
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
            font-size: 12px;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                margin: 10px;
            }
            .page-break {
                page-break-before: always;
            }
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
        <div class="summary-row">
            <span>Total Transactions:</span>
            <span>{{ $transactions->count() }}</span>
        </div>
        <div class="summary-row">
            <span>Total Sales:</span>
            <span class="text-right">${{ number_format($transactions->sum('total_amount'), 2) }}</span>
        </div>
        <div class="summary-row">
            <span>Average Transaction:</span>
            <span class="text-right">${{ number_format($transactions->avg('total_amount'), 2) }}</span>
        </div>
    </div>

    <h3>Transaction Details</h3>
    <table>
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
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; 
                              @if($transaction->status === 'completed') background-color: #d4edda; color: #155724;
                              @elseif($transaction->status === 'pending') background-color: #fff3cd; color: #856404;
                              @else background-color: #f8d7da; color: #721c24; @endif">
                            {{ ucfirst($transaction->status) }}
                        </span>
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

    <div class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Print Report
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
            Close
        </button>
    </div>
</body>
</html>

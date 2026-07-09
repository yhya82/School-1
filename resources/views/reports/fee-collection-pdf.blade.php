<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #1f2937; }
        h1 { font-size: 18px; color: #16243f; margin-bottom: 0; }
        p.subtitle { color: #6b7280; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #d1d5db; padding: 6px 8px; text-align: left; }
        th { background-color: #eef2f8; text-transform: uppercase; font-size: 10px; color: #4b5563; }
        tfoot td { font-weight: bold; background-color: #f9fafb; }
    </style>
</head>
<body>
    <h1>Fee Collection Report</h1>
    <p class="subtitle">{{ \Carbon\Carbon::parse($startDate)->format('M j, Y') }} &ndash; {{ \Carbon\Carbon::parse($endDate)->format('M j, Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Fee</th>
                <th>Amount Paid</th>
                <th>Method</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payments as $payment)
                <tr>
                    <td>{{ $payment->invoice->student->user->name }}</td>
                    <td>{{ $payment->invoice->feeStructure->name }}</td>
                    <td>{{ number_format($payment->amount_paid, 2) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                    <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr><td colspan="5">No payments recorded in this period.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Total</td>
                <td>{{ number_format($total, 2) }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>

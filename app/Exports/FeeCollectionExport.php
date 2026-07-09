<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FeeCollectionExport implements FromCollection, WithHeadings
{
    public function __construct(protected string $startDate, protected string $endDate)
    {
    }

    public function collection()
    {
        return Payment::with(['invoice.student.user', 'invoice.feeStructure'])
            ->whereDate('payment_date', '>=', $this->startDate)
            ->whereDate('payment_date', '<=', $this->endDate)
            ->orderBy('payment_date')
            ->get()
            ->map(fn (Payment $payment) => [
                $payment->invoice->student->user->name,
                $payment->invoice->feeStructure->name,
                $payment->amount_paid,
                ucfirst(str_replace('_', ' ', $payment->payment_method)),
                $payment->payment_date->format('Y-m-d'),
                $payment->transaction_ref,
            ]);
    }

    public function headings(): array
    {
        return ['Student', 'Fee', 'Amount Paid', 'Method', 'Payment Date', 'Transaction Ref'];
    }
}

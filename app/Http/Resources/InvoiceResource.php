<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'student_name' => $this->student->user->name,
            'fee_structure' => $this->feeStructure?->name,
            'amount_due' => (float) $this->amount_due,
            'amount_paid' => (float) $this->payments->sum('amount_paid'),
            'due_date' => $this->due_date?->format('Y-m-d'),
            'status' => $this->status,
        ];
    }
}

<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount_due' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'status' => 'required|in:unpaid,partial,paid,overdue',
        ];
    }
}

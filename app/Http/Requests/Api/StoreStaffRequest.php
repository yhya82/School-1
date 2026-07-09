<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'employee_no' => 'required|string|max:30|unique:staff,employee_no',
            'designation' => 'nullable|string|max:50',
            'department' => 'nullable|string|max:50',
            'joining_date' => 'required|date',
            'role' => 'required|in:teacher,staff',
        ];
    }
}

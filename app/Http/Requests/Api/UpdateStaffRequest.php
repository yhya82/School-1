<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $staff = $this->route('staff');

        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,'.$staff->user_id,
            'employee_no' => 'required|string|max:30|unique:staff,employee_no,'.$staff->id,
            'designation' => 'nullable|string|max:50',
            'department' => 'nullable|string|max:50',
            'joining_date' => 'required|date',
        ];
    }
}

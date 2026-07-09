<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'section_id' => 'required|exists:sections,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
        ];
    }
}

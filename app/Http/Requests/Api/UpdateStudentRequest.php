<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $student = $this->route('student');

        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,'.$student->user_id,
            'admission_no' => 'required|string|max:30|unique:students,admission_no,'.$student->id,
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'dob' => 'required|date|before:today',
            'gender' => 'required|in:M,F,O',
            'admission_date' => 'required|date',
            'status' => 'required|in:active,graduated,withdrawn',
        ];
    }
}

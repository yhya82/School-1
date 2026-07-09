<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExamResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $maxMarks = $this->route('exam_result')?->examSubject?->max_marks ?? 0;

        return [
            'marks_obtained' => "required|numeric|min:0|max:{$maxMarks}",
        ];
    }
}

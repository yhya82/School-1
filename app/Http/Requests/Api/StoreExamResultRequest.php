<?php

namespace App\Http\Requests\Api;

use App\Models\ExamSubject;
use Illuminate\Foundation\Http\FormRequest;

class StoreExamResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $examSubject = ExamSubject::find($this->input('exam_subject_id'));
        $maxMarks = $examSubject->max_marks ?? 0;

        return [
            'exam_subject_id' => 'required|exists:exam_subjects,id',
            'student_id' => 'required|exists:students,id',
            'marks_obtained' => "required|numeric|min:0|max:{$maxMarks}",
        ];
    }
}

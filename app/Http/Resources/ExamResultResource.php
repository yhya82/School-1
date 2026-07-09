<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'student_name' => $this->student->user->name,
            'exam_subject_id' => $this->exam_subject_id,
            'subject' => $this->examSubject->classSubject->subject->name,
            'marks_obtained' => (float) $this->marks_obtained,
            'max_marks' => $this->examSubject->max_marks,
            'grade' => $this->grade,
        ];
    }
}

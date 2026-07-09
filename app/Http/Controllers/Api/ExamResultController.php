<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreExamResultRequest;
use App\Http\Requests\Api\UpdateExamResultRequest;
use App\Http\Resources\ExamResultResource;
use App\Models\ExamResult;
use App\Models\ExamSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ExamResultController extends Controller
{
    protected function relations(): array
    {
        return ['student.user', 'examSubject.classSubject.subject'];
    }

    public function index(Request $request)
    {
        Gate::authorize('viewAny', ExamResult::class);

        $results = ExamResult::with($this->relations())
            ->when($request->query('exam_subject_id'), fn ($query, $value) => $query->where('exam_subject_id', $value))
            ->when($request->query('student_id'), fn ($query, $value) => $query->where('student_id', $value))
            ->paginate(20);

        return ExamResultResource::collection($results);
    }

    public function show(ExamResult $examResult)
    {
        Gate::authorize('view', $examResult);

        return new ExamResultResource($examResult->load($this->relations()));
    }

    public function store(StoreExamResultRequest $request)
    {
        $data = $request->validated();

        $examSubject = ExamSubject::findOrFail($data['exam_subject_id']);
        Gate::authorize('enterResults', $examSubject);

        $result = ExamResult::updateOrCreate(
            ['exam_subject_id' => $data['exam_subject_id'], 'student_id' => $data['student_id']],
            ['marks_obtained' => $data['marks_obtained'], 'grade' => $this->gradeFor((float) $data['marks_obtained'], $examSubject)]
        );

        return (new ExamResultResource($result->load($this->relations())))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateExamResultRequest $request, ExamResult $examResult)
    {
        Gate::authorize('enterResults', $examResult->examSubject);

        $data = $request->validated();

        $examResult->update([
            'marks_obtained' => $data['marks_obtained'],
            'grade' => $this->gradeFor((float) $data['marks_obtained'], $examResult->examSubject),
        ]);

        return new ExamResultResource($examResult->fresh($this->relations()));
    }

    protected function gradeFor(float $marks, $examSubject): string
    {
        $percentage = $examSubject->max_marks > 0 ? ($marks / $examSubject->max_marks) * 100 : 0;

        return match (true) {
            $percentage >= 90 => 'A',
            $percentage >= 75 => 'B',
            $percentage >= 60 => 'C',
            $marks >= $examSubject->pass_marks => 'D',
            default => 'F',
        };
    }
}

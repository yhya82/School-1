<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreExamRequest;
use App\Http\Requests\Api\UpdateExamRequest;
use App\Http\Resources\ExamResource;
use App\Models\Exam;
use Illuminate\Support\Facades\Gate;

class ExamController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Exam::class);

        return ExamResource::collection(
            Exam::with('academicYear')->orderByDesc('start_date')->paginate(20)
        );
    }

    public function show(Exam $exam)
    {
        Gate::authorize('view', $exam);

        return new ExamResource($exam->load('academicYear'));
    }

    public function store(StoreExamRequest $request)
    {
        Gate::authorize('create', Exam::class);

        $exam = Exam::create($request->validated());

        return (new ExamResource($exam->load('academicYear')))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateExamRequest $request, Exam $exam)
    {
        Gate::authorize('update', $exam);

        $exam->update($request->validated());

        return new ExamResource($exam->fresh('academicYear'));
    }

    public function destroy(Exam $exam)
    {
        Gate::authorize('delete', $exam);

        $exam->delete();

        return response()->json(null, 204);
    }
}

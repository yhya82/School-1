<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreAttendanceRequest;
use App\Http\Requests\Api\UpdateAttendanceRequest;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Attendance::class);

        $attendances = Attendance::with(['student.user', 'section'])
            ->when($request->query('section_id'), fn ($query, $value) => $query->where('section_id', $value))
            ->when($request->query('date'), fn ($query, $value) => $query->whereDate('date', $value))
            ->orderByDesc('date')
            ->paginate(20);

        return AttendanceResource::collection($attendances);
    }

    public function show(Attendance $attendance)
    {
        Gate::authorize('view', $attendance);

        return new AttendanceResource($attendance->load(['student.user', 'section']));
    }

    public function store(StoreAttendanceRequest $request)
    {
        Gate::authorize('create', Attendance::class);

        $data = $request->validated();

        $exists = Attendance::where('student_id', $data['student_id'])
            ->whereDate('date', $data['date'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => __('Attendance for this student and date already exists.'),
            ], 422);
        }

        $staff = Auth::user()->staff;

        $attendance = Attendance::create([
            ...$data,
            'marked_by' => $staff?->id,
        ]);

        return (new AttendanceResource($attendance->load(['student.user', 'section'])))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateAttendanceRequest $request, Attendance $attendance)
    {
        Gate::authorize('update', $attendance);

        $attendance->update($request->validated());

        return new AttendanceResource($attendance->fresh(['student.user', 'section']));
    }
}

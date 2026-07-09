<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreStudentRequest;
use App\Http\Requests\Api\UpdateStudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Student::class);

        $students = Student::with(['user', 'schoolClass', 'section', 'academicYear'])
            ->when($request->query('class_id'), fn ($query, $value) => $query->where('class_id', $value))
            ->where('status', 'active')
            ->orderBy('admission_no')
            ->paginate(20);

        return StudentResource::collection($students);
    }

    public function show(Student $student)
    {
        Gate::authorize('view', $student);

        return new StudentResource($student->load(['user', 'schoolClass', 'section', 'academicYear']));
    }

    public function store(StoreStudentRequest $request)
    {
        Gate::authorize('create', Student::class);

        $data = $request->validated();

        $password = Str::password(12);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($password),
        ]);
        $user->assignRole('student');

        $student = Student::create([
            ...collect($data)->except(['name', 'email'])->toArray(),
            'user_id' => $user->id,
            'status' => 'active',
        ]);

        return (new StudentResource($student->load(['user', 'schoolClass', 'section', 'academicYear'])))
            ->additional(['generated_password' => $password])
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        Gate::authorize('update', $student);

        $data = $request->validated();

        $student->user->update(['name' => $data['name'], 'email' => $data['email']]);
        $student->update(collect($data)->except(['name', 'email'])->toArray());

        return new StudentResource($student->fresh(['user', 'schoolClass', 'section', 'academicYear']));
    }

    public function destroy(Student $student)
    {
        Gate::authorize('delete', $student);

        $user = $student->user;
        $student->delete();
        $user?->delete();

        return response()->json(null, 204);
    }
}

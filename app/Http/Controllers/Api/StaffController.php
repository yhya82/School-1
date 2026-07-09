<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreStaffRequest;
use App\Http\Requests\Api\UpdateStaffRequest;
use App\Http\Resources\StaffResource;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Staff::class);

        return StaffResource::collection(
            Staff::with('user')->orderBy('employee_no')->paginate(20)
        );
    }

    public function show(Staff $staff)
    {
        Gate::authorize('view', $staff);

        return new StaffResource($staff->load('user'));
    }

    public function store(StoreStaffRequest $request)
    {
        Gate::authorize('create', Staff::class);

        $data = $request->validated();

        $password = Str::password(12);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($password),
        ]);
        $user->assignRole($data['role']);

        $staff = Staff::create([
            'user_id' => $user->id,
            'employee_no' => $data['employee_no'],
            'designation' => $data['designation'] ?? null,
            'department' => $data['department'] ?? null,
            'joining_date' => $data['joining_date'],
        ]);

        return (new StaffResource($staff->load('user')))
            ->additional(['generated_password' => $password])
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateStaffRequest $request, Staff $staff)
    {
        Gate::authorize('update', $staff);

        $data = $request->validated();

        $staff->user->update(['name' => $data['name'], 'email' => $data['email']]);
        $staff->update(collect($data)->except(['name', 'email'])->toArray());

        return new StaffResource($staff->fresh('user'));
    }

    public function destroy(Staff $staff)
    {
        Gate::authorize('delete', $staff);

        $user = $staff->user;
        $staff->delete();
        $user?->delete();

        return response()->json(null, 204);
    }
}

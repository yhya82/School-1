<?php

namespace Tests\Feature\Staff;

use App\Livewire\Staff\LeaveCreate;
use App\Livewire\Staff\LeaveEdit;
use App\Livewire\Staff\StaffAttendanceCreate;
use App\Livewire\Staff\StaffCreate;
use App\Models\Leave;
use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StaffManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    protected function admin(): User
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        return $admin;
    }

    public function test_teacher_can_view_staff_directory_but_not_create(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $this->actingAs($teacher)->get('/staff')->assertOk();
        $this->actingAs($teacher)->get('/staff/create')->assertForbidden();
    }

    public function test_teacher_cannot_view_attendance_or_leaves(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $this->actingAs($teacher)->get('/staff/attendance')->assertForbidden();
        $this->actingAs($teacher)->get('/staff/leaves')->assertForbidden();
    }

    public function test_admin_can_create_a_staff_member_with_a_linked_user_account(): void
    {
        $admin = $this->admin();

        Livewire::actingAs($admin)
            ->test(StaffCreate::class)
            ->set('name', 'Jane Teacher')
            ->set('email', 'jane.teacher@example.com')
            ->set('employee_no', 'EMP-0001')
            ->set('designation', 'Teacher')
            ->set('department', 'Science')
            ->set('joining_date', '2024-01-10')
            ->set('role', 'teacher')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', ['email' => 'jane.teacher@example.com']);
        $staff = Staff::where('employee_no', 'EMP-0001')->firstOrFail();
        $this->assertTrue($staff->user->hasRole('teacher'));
    }

    public function test_admin_cannot_mark_duplicate_attendance_for_same_staff_and_date(): void
    {
        $admin = $this->admin();
        $staff = Staff::factory()->create();

        Livewire::actingAs($admin)
            ->test(StaffAttendanceCreate::class)
            ->set('staff_id', $staff->id)
            ->set('date', '2024-05-01')
            ->set('status', 'present')
            ->call('save');

        Livewire::actingAs($admin)
            ->test(StaffAttendanceCreate::class)
            ->set('staff_id', $staff->id)
            ->set('date', '2024-05-01')
            ->set('status', 'absent')
            ->call('save')
            ->assertHasErrors('date');

        $this->assertDatabaseCount('staff_attendances', 1);
    }

    public function test_admin_can_create_and_approve_a_leave_request(): void
    {
        $admin = $this->admin();
        $staff = Staff::factory()->create();

        Livewire::actingAs($admin)
            ->test(LeaveCreate::class)
            ->set('staff_id', $staff->id)
            ->set('leave_type', 'sick')
            ->set('start_date', '2024-05-01')
            ->set('end_date', '2024-05-03')
            ->call('save')
            ->assertHasNoErrors();

        $leave = Leave::where('staff_id', $staff->id)->firstOrFail();
        $this->assertSame('pending', $leave->status);

        Livewire::actingAs($admin)
            ->test(LeaveEdit::class, ['leave' => $leave])
            ->set('status', 'approved')
            ->call('save');

        $this->assertSame('approved', $leave->fresh()->status);
    }

    public function test_deleting_a_staff_member_also_deletes_their_user_account(): void
    {
        $admin = $this->admin();
        $staff = Staff::factory()->create();
        $userId = $staff->user_id;

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Staff\StaffIndex::class)
            ->call('delete', $staff->id);

        $this->assertDatabaseMissing('staff', ['id' => $staff->id]);
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }
}

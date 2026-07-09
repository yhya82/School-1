<?php

namespace Tests\Feature\Api;

use App\Models\Attendance;
use App\Models\Section;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    protected function tokenFor(User $user): string
    {
        return $user->createToken('test')->plainTextToken;
    }

    protected function teacherWithStaffProfile(): User
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');
        Staff::factory()->create(['user_id' => $teacher->id]);

        return $teacher;
    }

    public function test_teacher_can_mark_attendance(): void
    {
        $teacher = $this->teacherWithStaffProfile();
        $section = Section::factory()->create();
        $student = Student::factory()->create(['section_id' => $section->id]);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->tokenFor($teacher))
            ->postJson('/api/v1/attendances', [
                'student_id' => $student->id,
                'section_id' => $section->id,
                'date' => now()->format('Y-m-d'),
                'status' => 'present',
            ]);

        $response->assertCreated()->assertJsonPath('data.status', 'present');
    }

    public function test_marking_duplicate_attendance_for_same_student_and_date_is_rejected(): void
    {
        $teacher = $this->teacherWithStaffProfile();
        $section = Section::factory()->create();
        $student = Student::factory()->create(['section_id' => $section->id]);
        Attendance::factory()->create([
            'student_id' => $student->id,
            'section_id' => $section->id,
            'date' => now()->format('Y-m-d'),
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->tokenFor($teacher))
            ->postJson('/api/v1/attendances', [
                'student_id' => $student->id,
                'section_id' => $section->id,
                'date' => now()->format('Y-m-d'),
                'status' => 'absent',
            ]);

        $response->assertStatus(422);
    }

    public function test_student_cannot_mark_attendance(): void
    {
        $studentUser = User::factory()->create();
        $studentUser->assignRole('student');
        $section = Section::factory()->create();
        $student = Student::factory()->create(['section_id' => $section->id]);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->tokenFor($studentUser))
            ->postJson('/api/v1/attendances', [
                'student_id' => $student->id,
                'section_id' => $section->id,
                'date' => now()->format('Y-m-d'),
                'status' => 'present',
            ]);

        $response->assertForbidden();
    }
}

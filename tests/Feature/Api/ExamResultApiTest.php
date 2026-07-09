<?php

namespace Tests\Feature\Api;

use App\Models\ClassSubject;
use App\Models\ExamSubject;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamResultApiTest extends TestCase
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

    public function test_assigned_teacher_can_enter_results(): void
    {
        $teacherUser = User::factory()->create();
        $teacherUser->assignRole('teacher');
        $staff = Staff::factory()->create(['user_id' => $teacherUser->id]);
        $classSubject = ClassSubject::factory()->create(['teacher_id' => $staff->id]);
        $examSubject = ExamSubject::factory()->create(['class_subject_id' => $classSubject->id, 'max_marks' => 100, 'pass_marks' => 40]);
        $student = Student::factory()->create(['class_id' => $classSubject->class_id]);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->tokenFor($teacherUser))
            ->postJson('/api/v1/exam-results', [
                'exam_subject_id' => $examSubject->id,
                'student_id' => $student->id,
                'marks_obtained' => 85,
            ]);

        $response->assertCreated()->assertJsonPath('data.grade', 'B');
    }

    public function test_unassigned_teacher_cannot_enter_results(): void
    {
        $teacherUser = User::factory()->create();
        $teacherUser->assignRole('teacher');
        Staff::factory()->create(['user_id' => $teacherUser->id]);

        $classSubject = ClassSubject::factory()->create();
        $examSubject = ExamSubject::factory()->create(['class_subject_id' => $classSubject->id]);
        $student = Student::factory()->create(['class_id' => $classSubject->class_id]);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->tokenFor($teacherUser))
            ->postJson('/api/v1/exam-results', [
                'exam_subject_id' => $examSubject->id,
                'student_id' => $student->id,
                'marks_obtained' => 85,
            ]);

        $response->assertForbidden();
    }

    public function test_marks_cannot_exceed_max_marks(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $examSubject = ExamSubject::factory()->create(['max_marks' => 50]);
        $student = Student::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer '.$this->tokenFor($admin))
            ->postJson('/api/v1/exam-results', [
                'exam_subject_id' => $examSubject->id,
                'student_id' => $student->id,
                'marks_obtained' => 60,
            ]);

        $response->assertUnprocessable();
    }
}

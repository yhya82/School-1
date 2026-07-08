<?php

namespace Tests\Feature\Academics;

use App\Livewire\Academics\AttendanceMark;
use App\Livewire\Academics\ExamResultsEntry;
use App\Livewire\Academics\TimetableCreate;
use App\Models\ClassSubject;
use App\Models\Exam;
use App\Models\ExamSubject;
use App\Models\Section;
use App\Models\SchoolClass;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AcademicsOperationsTest extends TestCase
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

    public function test_admin_cannot_create_duplicate_timetable_slot_for_same_section_day_and_time(): void
    {
        $admin = $this->admin();
        $section = Section::factory()->create();
        $classSubject = ClassSubject::factory()->create();

        Livewire::actingAs($admin)
            ->test(TimetableCreate::class)
            ->set('section_id', $section->id)
            ->set('class_subject_id', $classSubject->id)
            ->set('day_of_week', 1)
            ->set('start_time', '09:00')
            ->set('end_time', '10:00')
            ->call('save');

        Livewire::actingAs($admin)
            ->test(TimetableCreate::class)
            ->set('section_id', $section->id)
            ->set('class_subject_id', $classSubject->id)
            ->set('day_of_week', 1)
            ->set('start_time', '09:00')
            ->set('end_time', '11:00')
            ->call('save')
            ->assertHasErrors('start_time');

        $this->assertDatabaseCount('timetable_slots', 1);
    }

    public function test_assigned_teacher_can_enter_exam_results_but_unassigned_teacher_cannot(): void
    {
        $class = SchoolClass::factory()->create();
        $section = Section::factory()->create(['class_id' => $class->id]);

        $teacherUser = User::factory()->create();
        $teacherUser->assignRole('teacher');
        $teacherStaff = Staff::factory()->create(['user_id' => $teacherUser->id]);

        $otherTeacherUser = User::factory()->create();
        $otherTeacherUser->assignRole('teacher');
        Staff::factory()->create(['user_id' => $otherTeacherUser->id]);

        $classSubject = ClassSubject::factory()->create(['class_id' => $class->id, 'teacher_id' => $teacherStaff->id]);
        $exam = Exam::factory()->create();
        $examSubject = ExamSubject::factory()->create([
            'exam_id' => $exam->id,
            'class_subject_id' => $classSubject->id,
            'max_marks' => 100,
            'pass_marks' => 40,
        ]);

        $student = Student::factory()->create(['class_id' => $class->id, 'section_id' => $section->id, 'status' => 'active']);

        // Assigned teacher can enter results.
        Livewire::actingAs($teacherUser)
            ->test(ExamResultsEntry::class, ['examSubject' => $examSubject])
            ->set("marks.{$student->id}", 85)
            ->call('save');

        $this->assertDatabaseHas('exam_results', [
            'exam_subject_id' => $examSubject->id,
            'student_id' => $student->id,
            'marks_obtained' => 85,
        ]);

        // Unassigned teacher is forbidden.
        $this->actingAs($otherTeacherUser)
            ->get("/academics/exam-results/{$examSubject->id}/enter")
            ->assertForbidden();
    }

    public function test_marks_cannot_exceed_max_marks(): void
    {
        $admin = $this->admin();
        $classSubject = ClassSubject::factory()->create();
        $exam = Exam::factory()->create();
        $examSubject = ExamSubject::factory()->create([
            'exam_id' => $exam->id,
            'class_subject_id' => $classSubject->id,
            'max_marks' => 50,
            'pass_marks' => 20,
        ]);
        $student = Student::factory()->create(['class_id' => $classSubject->class_id, 'status' => 'active']);

        Livewire::actingAs($admin)
            ->test(ExamResultsEntry::class, ['examSubject' => $examSubject])
            ->set("marks.{$student->id}", 999)
            ->call('save')
            ->assertHasErrors(["marks.{$student->id}"]);
    }

    public function test_marking_attendance_twice_for_the_same_day_updates_instead_of_duplicating(): void
    {
        $admin = $this->admin();
        $section = Section::factory()->create();
        $student = Student::factory()->create(['section_id' => $section->id, 'status' => 'active']);

        Livewire::actingAs($admin)
            ->test(AttendanceMark::class)
            ->set('section_id', $section->id)
            ->set('date', '2024-05-01')
            ->set("statuses.{$student->id}", 'present')
            ->call('save');

        Livewire::actingAs($admin)
            ->test(AttendanceMark::class)
            ->set('section_id', $section->id)
            ->set('date', '2024-05-01')
            ->set("statuses.{$student->id}", 'absent')
            ->call('save');

        $this->assertDatabaseCount('attendances', 1);
        $this->assertDatabaseHas('attendances', [
            'student_id' => $student->id,
            'status' => 'absent',
        ]);
    }
}

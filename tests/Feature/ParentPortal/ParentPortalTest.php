<?php

namespace Tests\Feature\ParentPortal;

use App\Livewire\Students\GuardianCreate;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamSubject;
use App\Models\ClassSubject;
use App\Models\FeeStructure;
use App\Models\Guardian;
use App\Models\Invoice;
use App\Models\Section;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ParentPortalTest extends TestCase
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

    protected function parentUserFor(Student $student): User
    {
        $parentUser = User::factory()->create();
        $parentUser->assignRole('parent');
        $guardian = Guardian::factory()->create(['user_id' => $parentUser->id]);
        $student->guardians()->attach($guardian->id);

        return $parentUser;
    }

    public function test_guardian_can_view_own_childs_attendance_results_and_invoices(): void
    {
        $student = Student::factory()->create();
        $parentUser = $this->parentUserFor($student);

        Attendance::factory()->create(['student_id' => $student->id, 'section_id' => $student->section_id]);

        $classSubject = ClassSubject::factory()->create(['class_id' => $student->class_id]);
        $exam = Exam::factory()->create();
        $examSubject = ExamSubject::factory()->create(['exam_id' => $exam->id, 'class_subject_id' => $classSubject->id]);
        ExamResult::factory()->create(['exam_subject_id' => $examSubject->id, 'student_id' => $student->id]);

        $feeStructure = FeeStructure::factory()->create(['class_id' => $student->class_id]);
        Invoice::factory()->create(['student_id' => $student->id, 'fee_structure_id' => $feeStructure->id]);

        $this->actingAs($parentUser)->get('/portal')->assertOk()->assertSee($student->user->name);
        $this->actingAs($parentUser)->get("/portal/children/{$student->id}/attendance")->assertOk();
        $this->actingAs($parentUser)->get("/portal/children/{$student->id}/results")->assertOk();
        $this->actingAs($parentUser)->get("/portal/children/{$student->id}/invoices")->assertOk();
    }

    public function test_guardian_cannot_view_another_familys_child(): void
    {
        $ownChild = Student::factory()->create();
        $parentUser = $this->parentUserFor($ownChild);

        $otherChild = Student::factory()->create();

        $this->actingAs($parentUser)->get("/portal/children/{$otherChild->id}/attendance")->assertForbidden();
        $this->actingAs($parentUser)->get("/portal/children/{$otherChild->id}/results")->assertForbidden();
        $this->actingAs($parentUser)->get("/portal/children/{$otherChild->id}/invoices")->assertForbidden();
    }

    public function test_non_parent_cannot_access_the_portal(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $this->actingAs($teacher)->get('/portal')->assertForbidden();
    }

    public function test_admin_can_create_a_guardian_with_portal_access(): void
    {
        $admin = $this->admin();

        Livewire::actingAs($admin)
            ->test(GuardianCreate::class)
            ->set('name', 'Jane Parent')
            ->set('phone', '555-1234')
            ->set('relationship', 'Mother')
            ->set('email', 'jane.parent@example.com')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', ['email' => 'jane.parent@example.com']);
        $guardian = Guardian::where('name', 'Jane Parent')->firstOrFail();
        $this->assertNotNull($guardian->user_id);
        $this->assertTrue($guardian->user->hasRole('parent'));
    }

    public function test_admin_can_create_a_guardian_without_portal_access(): void
    {
        $admin = $this->admin();

        Livewire::actingAs($admin)
            ->test(GuardianCreate::class)
            ->set('name', 'No Portal Parent')
            ->set('phone', '555-5678')
            ->set('relationship', 'Father')
            ->call('save')
            ->assertHasNoErrors();

        $guardian = Guardian::where('name', 'No Portal Parent')->firstOrFail();
        $this->assertNull($guardian->user_id);
    }
}

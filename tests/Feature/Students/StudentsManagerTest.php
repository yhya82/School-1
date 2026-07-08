<?php

namespace Tests\Feature\Students;

use App\Livewire\Students\StudentCreate;
use App\Livewire\Students\StudentEdit;
use App\Models\AcademicYear;
use App\Models\Guardian;
use App\Models\Section;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class StudentsManagerTest extends TestCase
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

    public function test_student_cannot_view_students_index(): void
    {
        $student = User::factory()->create();
        $student->assignRole('student');

        $this->actingAs($student)->get('/students')->assertForbidden();
    }

    public function test_admin_can_create_a_student_with_a_linked_user_account(): void
    {
        $admin = $this->admin();
        $class = SchoolClass::factory()->create();
        $section = Section::factory()->create(['class_id' => $class->id]);
        $year = AcademicYear::factory()->create();
        $guardian = Guardian::factory()->create();

        Livewire::actingAs($admin)
            ->test(StudentCreate::class)
            ->set('name', 'Jane Student')
            ->set('email', 'jane.student@example.com')
            ->set('admission_no', 'ADM-0001')
            ->set('class_id', $class->id)
            ->set('section_id', $section->id)
            ->set('academic_year_id', $year->id)
            ->set('dob', '2012-05-01')
            ->set('gender', 'F')
            ->set('admission_date', '2024-01-10')
            ->set('selectedGuardianIds', [$guardian->id])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', ['email' => 'jane.student@example.com']);
        $student = Student::where('admission_no', 'ADM-0001')->firstOrFail();
        $this->assertTrue($student->user->hasRole('student'));
        $this->assertTrue($student->guardians->pluck('id')->contains($guardian->id));
    }

    public function test_section_must_belong_to_the_selected_class(): void
    {
        $admin = $this->admin();
        $classA = SchoolClass::factory()->create();
        $classB = SchoolClass::factory()->create();
        $sectionOfB = Section::factory()->create(['class_id' => $classB->id]);
        $year = AcademicYear::factory()->create();

        Livewire::actingAs($admin)
            ->test(StudentCreate::class)
            ->set('name', 'Mismatch Student')
            ->set('email', 'mismatch@example.com')
            ->set('admission_no', 'ADM-0002')
            ->set('class_id', $classA->id)
            ->set('section_id', $sectionOfB->id)
            ->set('academic_year_id', $year->id)
            ->set('dob', '2012-05-01')
            ->set('gender', 'M')
            ->set('admission_date', '2024-01-10')
            ->call('save')
            ->assertHasErrors('section_id');

        $this->assertDatabaseMissing('students', ['admission_no' => 'ADM-0002']);
    }

    public function test_admin_can_upload_and_delete_a_student_document(): void
    {
        Storage::fake('public');

        $admin = $this->admin();
        $student = Student::factory()->create();

        Livewire::actingAs($admin)
            ->test(StudentEdit::class, ['student' => $student])
            ->set('documentType', 'birth_certificate')
            ->set('documentFile', UploadedFile::fake()->create('cert.pdf', 100))
            ->call('uploadDocument')
            ->assertHasNoErrors();

        $document = StudentDocument::where('student_id', $student->id)->firstOrFail();
        Storage::disk('public')->assertExists($document->file_path);

        Livewire::actingAs($admin)
            ->test(StudentEdit::class, ['student' => $student])
            ->call('deleteDocument', $document->id);

        $this->assertDatabaseMissing('student_documents', ['id' => $document->id]);
        Storage::disk('public')->assertMissing($document->file_path);
    }

    public function test_deleting_a_student_also_deletes_their_user_account(): void
    {
        $admin = $this->admin();
        $student = Student::factory()->create();
        $userId = $student->user_id;

        $this->actingAs($admin)
            ->get('/students')
            ->assertOk();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Students\StudentIndex::class)
            ->call('delete', $student->id);

        $this->assertDatabaseMissing('students', ['id' => $student->id]);
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    public function test_teacher_can_view_guardians_but_not_create_one(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $this->actingAs($teacher)->get('/students/guardians')->assertOk();
        $this->actingAs($teacher)->get('/students/guardians/create')->assertForbidden();

        $this->assertDatabaseMissing('guardians', ['name' => 'Hacker Parent']);
    }
}

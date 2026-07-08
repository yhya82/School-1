<?php

namespace Tests\Feature\Academics;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/academics/years')->assertRedirect('/login');
    }

    public function test_student_cannot_view_academic_years(): void
    {
        $student = User::factory()->create();
        $student->assignRole('student');

        $this->actingAs($student)
            ->get('/academics/years')
            ->assertForbidden();
    }

    public function test_admin_can_view_and_create_academic_years(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)->get('/academics/years')->assertOk();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Academics\AcademicYears::class)
            ->set('name', '2031/2032')
            ->set('start_date', '2031-01-01')
            ->set('end_date', '2032-01-01')
            ->call('save');

        $this->assertDatabaseHas('academic_years', ['name' => '2031/2032']);
    }

    public function test_teacher_can_view_but_not_create_subjects(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $this->actingAs($teacher)->get('/academics/subjects')->assertOk();

        Livewire::actingAs($teacher)
            ->test(\App\Livewire\Academics\Subjects::class)
            ->set('name', 'Hacked Subject')
            ->set('code', 'HACK1')
            ->call('save')
            ->assertForbidden();

        $this->assertDatabaseMissing('subjects', ['code' => 'HACK1']);
    }

    public function test_admin_cannot_assign_the_same_subject_to_a_class_twice(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $class = SchoolClass::factory()->create();
        $subject = Subject::factory()->create();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Academics\ClassSubjects::class)
            ->set('class_id', $class->id)
            ->set('subject_id', $subject->id)
            ->call('save');

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Academics\ClassSubjects::class)
            ->set('class_id', $class->id)
            ->set('subject_id', $subject->id)
            ->call('save')
            ->assertHasErrors('subject_id');

        $this->assertDatabaseCount('class_subject', 1);
    }
}

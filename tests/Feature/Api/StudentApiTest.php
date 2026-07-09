<?php

namespace Tests\Feature\Api;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentApiTest extends TestCase
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

    protected function admin(): User
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        return $admin;
    }

    public function test_student_cannot_list_students(): void
    {
        $student = User::factory()->create();
        $student->assignRole('student');

        $this->withHeader('Authorization', 'Bearer '.$this->tokenFor($student))
            ->getJson('/api/v1/students')
            ->assertForbidden();
    }

    public function test_admin_can_list_students(): void
    {
        Student::factory()->count(2)->create();

        $this->withHeader('Authorization', 'Bearer '.$this->tokenFor($this->admin()))
            ->getJson('/api/v1/students')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_admin_can_create_a_student_with_linked_account(): void
    {
        $class = SchoolClass::factory()->create();
        $section = Section::factory()->create(['class_id' => $class->id]);
        $year = AcademicYear::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer '.$this->tokenFor($this->admin()))
            ->postJson('/api/v1/students', [
                'name' => 'Jane Student',
                'email' => 'jane@school.test',
                'admission_no' => 'ADM-99999',
                'class_id' => $class->id,
                'section_id' => $section->id,
                'academic_year_id' => $year->id,
                'dob' => '2010-01-01',
                'gender' => 'F',
                'admission_date' => now()->format('Y-m-d'),
            ]);

        $response->assertCreated()->assertJsonPath('data.email', 'jane@school.test');
        $this->assertDatabaseHas('users', ['email' => 'jane@school.test']);
        $this->assertDatabaseHas('students', ['admission_no' => 'ADM-99999']);
    }

    public function test_admin_can_delete_a_student_and_its_user_account(): void
    {
        $student = Student::factory()->create();
        $userId = $student->user_id;

        $this->withHeader('Authorization', 'Bearer '.$this->tokenFor($this->admin()))
            ->deleteJson("/api/v1/students/{$student->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('students', ['id' => $student->id]);
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }
}

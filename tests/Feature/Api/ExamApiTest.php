<?php

namespace Tests\Feature\Api;

use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamApiTest extends TestCase
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

    public function test_admin_can_create_and_delete_an_exam(): void
    {
        $year = AcademicYear::factory()->create();
        $header = ['Authorization' => 'Bearer '.$this->tokenFor($this->admin())];

        $createResponse = $this->withHeaders($header)->postJson('/api/v1/exams', [
            'academic_year_id' => $year->id,
            'name' => 'Mid Term',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(5)->format('Y-m-d'),
        ]);

        $createResponse->assertCreated()->assertJsonPath('data.name', 'Mid Term');
        $examId = $createResponse->json('data.id');

        $this->withHeaders($header)->deleteJson("/api/v1/exams/{$examId}")->assertNoContent();
        $this->assertDatabaseMissing('exams', ['id' => $examId]);
    }

    public function test_teacher_cannot_create_an_exam(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');
        $year = AcademicYear::factory()->create();

        $this->withHeader('Authorization', 'Bearer '.$this->tokenFor($teacher))
            ->postJson('/api/v1/exams', [
                'academic_year_id' => $year->id,
                'name' => 'Mid Term',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => now()->addDays(5)->format('Y-m-d'),
            ])
            ->assertForbidden();
    }

    public function test_teacher_can_view_exams(): void
    {
        Exam::factory()->create();
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $this->withHeader('Authorization', 'Bearer '.$this->tokenFor($teacher))
            ->getJson('/api/v1/exams')
            ->assertOk();
    }
}

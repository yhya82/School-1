<?php

namespace Tests\Feature\Api;

use App\Models\Staff;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffApiTest extends TestCase
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

    public function test_teacher_can_view_but_not_create_staff(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');
        $header = ['Authorization' => 'Bearer '.$this->tokenFor($teacher)];

        $this->withHeaders($header)->getJson('/api/v1/staff')->assertOk();

        $this->withHeaders($header)->postJson('/api/v1/staff', [
            'name' => 'New Teacher',
            'email' => 'newteacher@school.test',
            'employee_no' => 'EMP-88888',
            'joining_date' => now()->format('Y-m-d'),
            'role' => 'teacher',
        ])->assertForbidden();
    }

    public function test_admin_can_create_a_staff_member_with_linked_account(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->tokenFor($this->admin()))
            ->postJson('/api/v1/staff', [
                'name' => 'New Teacher',
                'email' => 'newteacher@school.test',
                'employee_no' => 'EMP-88888',
                'joining_date' => now()->format('Y-m-d'),
                'role' => 'teacher',
            ]);

        $response->assertCreated()->assertJsonPath('data.email', 'newteacher@school.test');
        $this->assertDatabaseHas('users', ['email' => 'newteacher@school.test']);
    }

    public function test_admin_can_update_a_staff_member(): void
    {
        $staff = Staff::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer '.$this->tokenFor($this->admin()))
            ->putJson("/api/v1/staff/{$staff->id}", [
                'name' => $staff->user->name,
                'email' => $staff->user->email,
                'employee_no' => $staff->employee_no,
                'designation' => 'Head of Science',
                'department' => $staff->department,
                'joining_date' => $staff->joining_date->format('Y-m-d'),
            ]);

        $response->assertOk()->assertJsonPath('data.designation', 'Head of Science');
    }
}

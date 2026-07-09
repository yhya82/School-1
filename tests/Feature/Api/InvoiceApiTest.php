<?php

namespace Tests\Feature\Api;

use App\Models\FeeStructure;
use App\Models\Invoice;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceApiTest extends TestCase
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

    public function test_teacher_cannot_view_invoices(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $this->withHeader('Authorization', 'Bearer '.$this->tokenFor($teacher))
            ->getJson('/api/v1/invoices')
            ->assertForbidden();
    }

    public function test_admin_can_create_an_invoice(): void
    {
        $student = Student::factory()->create();
        $feeStructure = FeeStructure::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer '.$this->tokenFor($this->admin()))
            ->postJson('/api/v1/invoices', [
                'student_id' => $student->id,
                'fee_structure_id' => $feeStructure->id,
                'amount_due' => 500,
                'due_date' => now()->addMonth()->format('Y-m-d'),
            ]);

        $response->assertCreated()->assertJsonPath('data.status', 'unpaid');
    }

    public function test_duplicate_invoice_for_same_student_and_fee_structure_is_rejected(): void
    {
        $invoice = Invoice::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer '.$this->tokenFor($this->admin()))
            ->postJson('/api/v1/invoices', [
                'student_id' => $invoice->student_id,
                'fee_structure_id' => $invoice->fee_structure_id,
                'amount_due' => 500,
                'due_date' => now()->addMonth()->format('Y-m-d'),
            ]);

        $response->assertStatus(422);
    }
}

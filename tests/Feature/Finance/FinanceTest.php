<?php

namespace Tests\Feature\Finance;

use App\Livewire\Finance\ExpenseCreate;
use App\Livewire\Finance\FeeStructureEdit;
use App\Livewire\Finance\PaymentCreate;
use App\Models\FeeStructure;
use App\Models\Invoice;
use App\Models\SchoolClass;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FinanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    protected function admin(bool $withStaffProfile = false): User
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        if ($withStaffProfile) {
            Staff::factory()->create(['user_id' => $admin->id]);
        }

        return $admin;
    }

    public function test_teacher_cannot_access_finance_routes(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $this->actingAs($teacher)->get('/finance/fee-structures')->assertForbidden();
        $this->actingAs($teacher)->get('/finance/invoices')->assertForbidden();
        $this->actingAs($teacher)->get('/finance/expenses')->assertForbidden();
    }

    public function test_generating_invoices_skips_students_who_already_have_one(): void
    {
        $admin = $this->admin();
        $class = SchoolClass::factory()->create();
        $feeStructure = FeeStructure::factory()->create(['class_id' => $class->id]);

        $alreadyInvoiced = Student::factory()->create(['class_id' => $class->id, 'status' => 'active']);
        $newStudent = Student::factory()->create(['class_id' => $class->id, 'status' => 'active']);
        $withdrawn = Student::factory()->create(['class_id' => $class->id, 'status' => 'withdrawn']);

        Invoice::factory()->create([
            'student_id' => $alreadyInvoiced->id,
            'fee_structure_id' => $feeStructure->id,
        ]);

        Livewire::actingAs($admin)
            ->test(FeeStructureEdit::class, ['feeStructure' => $feeStructure])
            ->set('due_date', '2024-06-01')
            ->call('generateInvoices');

        $this->assertDatabaseCount('invoices', 2);
        $this->assertDatabaseHas('invoices', ['student_id' => $newStudent->id, 'fee_structure_id' => $feeStructure->id]);
        $this->assertDatabaseMissing('invoices', ['student_id' => $withdrawn->id, 'fee_structure_id' => $feeStructure->id]);
    }

    public function test_recording_a_payment_updates_invoice_status_to_partial_then_paid(): void
    {
        $admin = $this->admin();
        $invoice = Invoice::factory()->create(['amount_due' => 100, 'status' => 'unpaid']);

        Livewire::actingAs($admin)
            ->test(PaymentCreate::class)
            ->set('invoice_id', $invoice->id)
            ->set('amount_paid', 40)
            ->set('payment_date', '2024-06-01')
            ->set('payment_method', 'cash')
            ->call('save');

        $this->assertSame('partial', $invoice->fresh()->status);

        Livewire::actingAs($admin)
            ->test(PaymentCreate::class)
            ->set('invoice_id', $invoice->id)
            ->set('amount_paid', 60)
            ->set('payment_date', '2024-06-02')
            ->set('payment_method', 'cash')
            ->call('save');

        $this->assertSame('paid', $invoice->fresh()->status);
    }

    public function test_admin_with_a_staff_profile_can_log_an_expense(): void
    {
        $admin = $this->admin(withStaffProfile: true);

        Livewire::actingAs($admin)
            ->test(ExpenseCreate::class)
            ->set('category', 'Utilities')
            ->set('amount', 250)
            ->set('expense_date', '2024-06-01')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('expenses', ['category' => 'Utilities']);
    }

    public function test_admin_without_a_staff_profile_gets_a_friendly_error_logging_an_expense(): void
    {
        $admin = $this->admin();

        Livewire::actingAs($admin)
            ->test(ExpenseCreate::class)
            ->set('category', 'Utilities')
            ->set('amount', 250)
            ->set('expense_date', '2024-06-01')
            ->call('save')
            ->assertHasErrors('category');

        $this->assertDatabaseMissing('expenses', ['category' => 'Utilities']);
    }
}

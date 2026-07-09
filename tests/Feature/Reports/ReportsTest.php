<?php

namespace Tests\Feature\Reports;

use App\Livewire\Reports\ReportsIndex;
use App\Models\ClassSubject;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamSubject;
use App\Models\FeeStructure;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReportsTest extends TestCase
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

    public function test_non_admin_cannot_access_reports(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $this->actingAs($teacher)->get('/reports')->assertForbidden();
    }

    public function test_admin_can_view_reports_page(): void
    {
        $this->actingAs($this->admin())->get('/reports')->assertOk();
    }

    public function test_admin_can_export_students_excel(): void
    {
        Student::factory()->create();

        Livewire::actingAs($this->admin())
            ->test(ReportsIndex::class)
            ->call('exportStudents')
            ->assertFileDownloaded('students.xlsx');
    }

    public function test_admin_can_export_fee_collection_excel_and_pdf(): void
    {
        $invoice = Invoice::factory()->create();
        Payment::factory()->create(['invoice_id' => $invoice->id, 'payment_date' => now()]);

        $component = Livewire::actingAs($this->admin())->test(ReportsIndex::class);

        $component->call('exportFeeCollectionExcel')->assertFileDownloaded('fee-collection.xlsx');
        $component->call('exportFeeCollectionPdf')->assertFileDownloaded('fee-collection.pdf');
    }

    public function test_admin_can_export_attendance_summary(): void
    {
        Livewire::actingAs($this->admin())
            ->test(ReportsIndex::class)
            ->call('exportAttendanceSummary')
            ->assertFileDownloaded('attendance-summary.xlsx');
    }

    public function test_admin_can_download_a_report_card(): void
    {
        $student = Student::factory()->create();
        $classSubject = ClassSubject::factory()->create(['class_id' => $student->class_id]);
        $exam = Exam::factory()->create();
        $examSubject = ExamSubject::factory()->create(['exam_id' => $exam->id, 'class_subject_id' => $classSubject->id]);
        ExamResult::factory()->create(['exam_subject_id' => $examSubject->id, 'student_id' => $student->id]);

        Livewire::actingAs($this->admin())
            ->test(ReportsIndex::class)
            ->set('student_id', $student->id)
            ->set('exam_id', $exam->id)
            ->call('downloadReportCard')
            ->assertFileDownloaded("report-card-{$student->admission_no}.pdf");
    }
}

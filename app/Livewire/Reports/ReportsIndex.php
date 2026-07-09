<?php

namespace App\Livewire\Reports;

use App\Exports\AttendanceSummaryExport;
use App\Exports\FeeCollectionExport;
use App\Exports\StudentsExport;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Payment;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.app')]
class ReportsIndex extends Component
{
    public string $start_date = '';

    public string $end_date = '';

    public ?int $student_id = null;

    public ?int $exam_id = null;

    public function mount(): void
    {
        abort_unless(Auth::user()->hasRole('admin'), 403);

        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date = now()->endOfMonth()->format('Y-m-d');
    }

    protected function dateRangeRules(): array
    {
        return [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];
    }

    public function exportStudents()
    {
        return Excel::download(new StudentsExport, 'students.xlsx');
    }

    public function exportFeeCollectionExcel()
    {
        $this->validate($this->dateRangeRules());

        return Excel::download(
            new FeeCollectionExport($this->start_date, $this->end_date),
            'fee-collection.xlsx'
        );
    }

    public function exportFeeCollectionPdf()
    {
        $this->validate($this->dateRangeRules());

        $payments = Payment::with(['invoice.student.user', 'invoice.feeStructure'])
            ->whereDate('payment_date', '>=', $this->start_date)
            ->whereDate('payment_date', '<=', $this->end_date)
            ->orderBy('payment_date')
            ->get();

        $pdf = Pdf::loadView('reports.fee-collection-pdf', [
            'payments' => $payments,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'total' => $payments->sum('amount_paid'),
        ]);

        return response()->streamDownload(
            fn () => print $pdf->output(),
            'fee-collection.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }

    public function exportAttendanceSummary()
    {
        $this->validate($this->dateRangeRules());

        return Excel::download(
            new AttendanceSummaryExport($this->start_date, $this->end_date),
            'attendance-summary.xlsx'
        );
    }

    public function downloadReportCard()
    {
        $this->validate([
            'student_id' => 'required|exists:students,id',
            'exam_id' => 'required|exists:exams,id',
        ]);

        $student = Student::with('user', 'schoolClass', 'section')->findOrFail($this->student_id);
        $exam = Exam::findOrFail($this->exam_id);

        $results = ExamResult::where('student_id', $student->id)
            ->whereHas('examSubject', fn ($q) => $q->where('exam_id', $exam->id))
            ->with(['examSubject.classSubject.subject'])
            ->get();

        $pdf = Pdf::loadView('reports.report-card-pdf', [
            'student' => $student,
            'exam' => $exam,
            'results' => $results,
            'totalObtained' => $results->sum('marks_obtained'),
            'totalMax' => $results->sum(fn ($result) => $result->examSubject->max_marks),
        ]);

        return response()->streamDownload(
            fn () => print $pdf->output(),
            "report-card-{$student->admission_no}.pdf",
            ['Content-Type' => 'application/pdf']
        );
    }

    public function render()
    {
        return view('livewire.reports.reports-index', [
            'students' => Student::with('user')->where('status', 'active')->orderBy('admission_no')->get(),
            'exams' => Exam::orderByDesc('start_date')->get(),
        ]);
    }
}

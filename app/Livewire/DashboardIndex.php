<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\Invoice;
use App\Models\Leave;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class DashboardIndex extends Component
{
    public function render()
    {
        $user = Auth::user();
        $canViewAcademics = $user->hasAnyRole(['admin', 'teacher']);
        $isAdmin = $user->hasRole('admin');

        return view('livewire.dashboard-index', [
            'canViewAcademics' => $canViewAcademics,
            'isAdmin' => $isAdmin,
            'studentCount' => $canViewAcademics ? Student::where('status', 'active')->count() : null,
            'staffCount' => $canViewAcademics ? Staff::count() : null,
            'classCount' => $canViewAcademics ? SchoolClass::count() : null,
            'sectionCount' => $canViewAcademics ? Section::count() : null,
            'outstandingInvoices' => $isAdmin ? Invoice::where('status', '!=', 'paid')->count() : null,
            'outstandingAmount' => $isAdmin
                ? Invoice::with('payments')->where('status', '!=', 'paid')->get()->sum(fn ($invoice) => $invoice->amount_due - $invoice->payments->sum('amount_paid'))
                : null,
            'upcomingExams' => $canViewAcademics
                ? Exam::where('start_date', '>=', now())->orderBy('start_date')->limit(5)->get()
                : collect(),
            'pendingLeaves' => $isAdmin
                ? Leave::with('staff.user')->where('status', 'pending')->orderByDesc('start_date')->limit(5)->get()
                : collect(),
        ]);
    }
}

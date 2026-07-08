<?php

use App\Livewire\Academics\AcademicYearCreate;
use App\Livewire\Academics\AcademicYearEdit;
use App\Livewire\Academics\AcademicYearIndex;
use App\Livewire\Academics\AttendanceIndex;
use App\Livewire\Academics\AttendanceMark;
use App\Livewire\Academics\ClassCreate;
use App\Livewire\Academics\ClassEdit;
use App\Livewire\Academics\ClassIndex;
use App\Livewire\Academics\ClassSubjectCreate;
use App\Livewire\Academics\ClassSubjectEdit;
use App\Livewire\Academics\ClassSubjectIndex;
use App\Livewire\Academics\ExamCreate;
use App\Livewire\Academics\ExamEdit;
use App\Livewire\Academics\ExamIndex;
use App\Livewire\Academics\ExamResultsEntry;
use App\Livewire\Academics\SubjectCreate;
use App\Livewire\Academics\SubjectEdit;
use App\Livewire\Academics\SubjectIndex;
use App\Livewire\Academics\TimetableCreate;
use App\Livewire\Academics\TimetableEdit;
use App\Livewire\Academics\TimetableIndex;
use App\Livewire\Finance\ExpenseCreate;
use App\Livewire\Finance\ExpenseEdit;
use App\Livewire\Finance\ExpenseIndex;
use App\Livewire\Finance\FeeStructureCreate;
use App\Livewire\Finance\FeeStructureEdit;
use App\Livewire\Finance\FeeStructureIndex;
use App\Livewire\Finance\InvoiceCreate;
use App\Livewire\Finance\InvoiceEdit;
use App\Livewire\Finance\InvoiceIndex;
use App\Livewire\Finance\PaymentCreate;
use App\Livewire\Finance\PaymentEdit;
use App\Livewire\Finance\PaymentIndex;
use App\Livewire\Staff\LeaveCreate;
use App\Livewire\Staff\LeaveEdit;
use App\Livewire\Staff\LeaveIndex;
use App\Livewire\Staff\StaffAttendanceCreate;
use App\Livewire\Staff\StaffAttendanceEdit;
use App\Livewire\Staff\StaffAttendanceIndex;
use App\Livewire\Staff\StaffCreate;
use App\Livewire\Staff\StaffEdit;
use App\Livewire\Staff\StaffIndex;
use App\Livewire\Students\GuardianCreate;
use App\Livewire\Students\GuardianEdit;
use App\Livewire\Students\GuardianIndex;
use App\Livewire\Students\StudentCreate;
use App\Livewire\Students\StudentEdit;
use App\Livewire\Students\StudentIndex;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified', 'role:admin|teacher'])
    ->prefix('academics')
    ->name('academics.')
    ->group(function () {
        Route::get('years', AcademicYearIndex::class)->name('years.index');
        Route::get('years/create', AcademicYearCreate::class)->name('years.create');
        Route::get('years/{year}/edit', AcademicYearEdit::class)->name('years.edit');

        Route::get('classes', ClassIndex::class)->name('classes.index');
        Route::get('classes/create', ClassCreate::class)->name('classes.create');
        Route::get('classes/{class}/edit', ClassEdit::class)->name('classes.edit');

        Route::get('subjects', SubjectIndex::class)->name('subjects.index');
        Route::get('subjects/create', SubjectCreate::class)->name('subjects.create');
        Route::get('subjects/{subject}/edit', SubjectEdit::class)->name('subjects.edit');

        Route::get('class-subjects', ClassSubjectIndex::class)->name('class-subjects.index');
        Route::get('class-subjects/create', ClassSubjectCreate::class)->name('class-subjects.create');
        Route::get('class-subjects/{classSubject}/edit', ClassSubjectEdit::class)->name('class-subjects.edit');

        Route::get('timetable', TimetableIndex::class)->name('timetable.index');
        Route::get('timetable/create', TimetableCreate::class)->name('timetable.create');
        Route::get('timetable/{timetableSlot}/edit', TimetableEdit::class)->name('timetable.edit');

        Route::get('exams', ExamIndex::class)->name('exams.index');
        Route::get('exams/create', ExamCreate::class)->name('exams.create');
        Route::get('exams/{exam}/edit', ExamEdit::class)->name('exams.edit');
        Route::get('exam-results/{examSubject}/enter', ExamResultsEntry::class)->name('exam-results.enter');

        Route::get('attendance', AttendanceIndex::class)->name('attendance.index');
        Route::get('attendance/mark', AttendanceMark::class)->name('attendance.mark');
    });

Route::middleware(['auth', 'verified', 'role:admin|teacher'])
    ->prefix('students')
    ->name('students.')
    ->group(function () {
        Route::get('/', StudentIndex::class)->name('students.index');
        Route::get('create', StudentCreate::class)->name('students.create');
        Route::get('{student}/edit', StudentEdit::class)->name('students.edit');

        Route::get('guardians', GuardianIndex::class)->name('guardians.index');
        Route::get('guardians/create', GuardianCreate::class)->name('guardians.create');
        Route::get('guardians/{guardian}/edit', GuardianEdit::class)->name('guardians.edit');
    });

Route::middleware(['auth', 'verified', 'role:admin|teacher'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {
        Route::get('/', StaffIndex::class)->name('staff.index');
        Route::get('create', StaffCreate::class)->name('staff.create');
        Route::get('{staff}/edit', StaffEdit::class)->name('staff.edit');

        Route::get('attendance', StaffAttendanceIndex::class)->name('attendance.index');
        Route::get('attendance/create', StaffAttendanceCreate::class)->name('attendance.create');
        Route::get('attendance/{staffAttendance}/edit', StaffAttendanceEdit::class)->name('attendance.edit');

        Route::get('leaves', LeaveIndex::class)->name('leaves.index');
        Route::get('leaves/create', LeaveCreate::class)->name('leaves.create');
        Route::get('leaves/{leave}/edit', LeaveEdit::class)->name('leaves.edit');
    });

Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('finance')
    ->name('finance.')
    ->group(function () {
        Route::get('fee-structures', FeeStructureIndex::class)->name('fee-structures.index');
        Route::get('fee-structures/create', FeeStructureCreate::class)->name('fee-structures.create');
        Route::get('fee-structures/{feeStructure}/edit', FeeStructureEdit::class)->name('fee-structures.edit');

        Route::get('invoices', InvoiceIndex::class)->name('invoices.index');
        Route::get('invoices/create', InvoiceCreate::class)->name('invoices.create');
        Route::get('invoices/{invoice}/edit', InvoiceEdit::class)->name('invoices.edit');

        Route::get('payments', PaymentIndex::class)->name('payments.index');
        Route::get('payments/create', PaymentCreate::class)->name('payments.create');
        Route::get('payments/{payment}/edit', PaymentEdit::class)->name('payments.edit');

        Route::get('expenses', ExpenseIndex::class)->name('expenses.index');
        Route::get('expenses/create', ExpenseCreate::class)->name('expenses.create');
        Route::get('expenses/{expense}/edit', ExpenseEdit::class)->name('expenses.edit');
    });

require __DIR__.'/auth.php';

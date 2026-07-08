<?php

use App\Livewire\Academics\AcademicYearCreate;
use App\Livewire\Academics\AcademicYearEdit;
use App\Livewire\Academics\AcademicYearIndex;
use App\Livewire\Academics\ClassCreate;
use App\Livewire\Academics\ClassEdit;
use App\Livewire\Academics\ClassIndex;
use App\Livewire\Academics\ClassSubjectCreate;
use App\Livewire\Academics\ClassSubjectEdit;
use App\Livewire\Academics\ClassSubjectIndex;
use App\Livewire\Academics\SubjectCreate;
use App\Livewire\Academics\SubjectEdit;
use App\Livewire\Academics\SubjectIndex;
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

require __DIR__.'/auth.php';

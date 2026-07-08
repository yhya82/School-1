<?php

use App\Livewire\Academics\AcademicYears;
use App\Livewire\Academics\ClassesManager;
use App\Livewire\Academics\ClassSubjects;
use App\Livewire\Academics\Subjects;
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
        Route::get('years', AcademicYears::class)->name('years');
        Route::get('classes', ClassesManager::class)->name('classes');
        Route::get('subjects', Subjects::class)->name('subjects');
        Route::get('class-subjects', ClassSubjects::class)->name('class-subjects');
    });

require __DIR__.'/auth.php';

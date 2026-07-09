<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\ExamResultController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\StudentController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('me', [AuthController::class, 'me'])->name('me');

        Route::apiResource('students', StudentController::class);
        Route::apiResource('staff', StaffController::class);
        Route::apiResource('attendances', AttendanceController::class)->only(['index', 'show', 'store', 'update']);
        Route::apiResource('exams', ExamController::class);
        Route::apiResource('exam-results', ExamResultController::class)->only(['index', 'show', 'store', 'update']);
        Route::apiResource('invoices', InvoiceController::class)->only(['index', 'show', 'store', 'update']);
    });
});

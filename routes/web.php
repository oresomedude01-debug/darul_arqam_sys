<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\EnrollmentController;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Students Management
Route::resource('students', StudentController::class);
Route::put('/students/{id}/update-status', [StudentController::class, 'updateStatus'])->name('students.update-status');

// Teachers Management
Route::resource('teachers', TeacherController::class);

// Classes Management
Route::resource('classes', ClassController::class);

// Attendance Management
Route::resource('attendance', AttendanceController::class);
Route::post('/attendance/mark', [AttendanceController::class, 'mark'])->name('attendance.mark');

// Grades Management
Route::resource('grades', GradeController::class);
Route::post('/grades/bulk-upload', [GradeController::class, 'bulkUpload'])->name('grades.bulk-upload');

// Registration Tokens Management
Route::resource('tokens', TokenController::class);
Route::post('/tokens/bulk-disable', [TokenController::class, 'bulkDisable'])->name('tokens.bulk-disable');
Route::post('/tokens/bulk-enable', [TokenController::class, 'bulkEnable'])->name('tokens.bulk-enable');
Route::post('/tokens/validate', [TokenController::class, 'validate'])->name('tokens.validate');

// Public Enrollment (No authentication required)
Route::prefix('enroll')->name('enrollment.')->group(function () {
    // Step 1: Token validation
    Route::get('/', [EnrollmentController::class, 'showTokenForm'])->name('token');
    Route::post('/validate-token', [EnrollmentController::class, 'validateToken'])->name('validate-token');

    // Step 2: Student details
    Route::get('/student-details', [EnrollmentController::class, 'showStep1'])->name('step1');
    Route::post('/student-details', [EnrollmentController::class, 'processStep1'])->name('process-step1');

    // Step 3: Previous school
    Route::get('/previous-school', [EnrollmentController::class, 'showStep2'])->name('step2');
    Route::post('/previous-school', [EnrollmentController::class, 'processStep2'])->name('process-step2');

    // Step 4: Health information
    Route::get('/health-information', [EnrollmentController::class, 'showStep3'])->name('step3');
    Route::post('/health-information', [EnrollmentController::class, 'processStep3'])->name('process-step3');

    // Step 5: Parent/Guardian
    Route::get('/parent-guardian', [EnrollmentController::class, 'showStep4'])->name('step4');
    Route::post('/parent-guardian', [EnrollmentController::class, 'processStep4'])->name('process-step4');

    // Success page
    Route::get('/success', [EnrollmentController::class, 'success'])->name('success');
});


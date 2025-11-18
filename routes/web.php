<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\TokenController;

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

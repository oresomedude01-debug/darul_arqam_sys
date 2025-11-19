<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ClassSubjectController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LocaleController;

// Landing Page (Public)
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Language Switcher
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

// Authentication Routes
require __DIR__.'/auth.php';

// Protected Admin Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Students Management
    Route::resource('students', StudentController::class);
    Route::put('/students/{id}/update-status', [StudentController::class, 'updateStatus'])->name('students.update-status');
    Route::get('/students/{id}/print', [StudentController::class, 'print'])->name('students.print');
    Route::get('/students-export', [StudentController::class, 'export'])->name('students.export');
    Route::get('/students-import', [StudentController::class, 'importForm'])->name('students.import-form');
    Route::post('/students-import', [StudentController::class, 'import'])->name('students.import');
    Route::get('/students-template', [StudentController::class, 'downloadTemplate'])->name('students.template');

    // Teachers Management
    Route::resource('teachers', TeacherController::class);
    Route::get('/teachers-export', [TeacherController::class, 'exportCsv'])->name('teachers.export');
    Route::get('/teachers/{teacher}/assign', [TeacherController::class, 'assign'])->name('teachers.assign');
    Route::post('/teachers/{teacher}/assign', [TeacherController::class, 'updateAssignments'])->name('teachers.update-assignments');

    // Classes Management
    Route::resource('classes', ClassController::class);
    Route::get('/classes-export', [ClassController::class, 'exportCsv'])->name('classes.export');

    // Class-Subject Assignment Management
    Route::get('/classes/{class}/subjects', [ClassSubjectController::class, 'index'])->name('classes.subjects.index');
    Route::post('/classes/{class}/subjects', [ClassSubjectController::class, 'store'])->name('classes.subjects.store');
    Route::put('/classes/{class}/subjects/{subject}', [ClassSubjectController::class, 'update'])->name('classes.subjects.update');
    Route::delete('/classes/{class}/subjects/{subject}', [ClassSubjectController::class, 'destroy'])->name('classes.subjects.destroy');

    // Timetable Management
    Route::get('/classes/{class}/timetable', [TimetableController::class, 'index'])->name('classes.timetable.index');
    Route::post('/classes/{class}/timetable', [TimetableController::class, 'store'])->name('classes.timetable.store');
    Route::post('/classes/{class}/timetable/bulk', [TimetableController::class, 'bulkStore'])->name('classes.timetable.bulk-store');
    Route::put('/classes/{class}/timetable/{timetable}', [TimetableController::class, 'update'])->name('classes.timetable.update');
    Route::delete('/classes/{class}/timetable/{timetable}', [TimetableController::class, 'destroy'])->name('classes.timetable.destroy');

    // Subjects Management
    Route::resource('subjects', SubjectController::class);

    // Attendance Management
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/records', [AttendanceController::class, 'records'])->name('attendance.records');
    Route::get('/attendance/student/{student}', [AttendanceController::class, 'studentProfile'])->name('attendance.student-profile');
    Route::post('/attendance/mark-all-present', [AttendanceController::class, 'markAllPresent'])->name('attendance.mark-all-present');

    // Grades Management
    Route::resource('grades', GradeController::class);
    Route::post('/grades/bulk-upload', [GradeController::class, 'bulkUpload'])->name('grades.bulk-upload');

    // Registration Tokens Management
    Route::resource('tokens', TokenController::class);
    Route::post('/tokens/bulk-disable', [TokenController::class, 'bulkDisable'])->name('tokens.bulk-disable');
    Route::post('/tokens/bulk-enable', [TokenController::class, 'bulkEnable'])->name('tokens.bulk-enable');
    Route::post('/tokens/validate', [TokenController::class, 'validate'])->name('tokens.validate');
});

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


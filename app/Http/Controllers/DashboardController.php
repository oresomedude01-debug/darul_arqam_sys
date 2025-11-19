<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Grade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalClasses = SchoolClass::count();

        // Calculate today's attendance
        $today = Carbon::today();
        $todayAttendance = Attendance::whereDate('date', $today)->get();
        $presentCount = $todayAttendance->where('status', 'present')->count();
        $totalAttendanceRecords = $todayAttendance->count();
        $attendancePercentage = $totalAttendanceRecords > 0
            ? round(($presentCount / $totalAttendanceRecords) * 100, 1)
            : 0;

        // Get last 7 days attendance data for chart
        $last7Days = [];
        $attendanceData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $last7Days[] = $date->format('D');

            $dayAttendance = Attendance::whereDate('date', $date)->get();
            $dayPresent = $dayAttendance->where('status', 'present')->count();
            $dayTotal = $dayAttendance->count();
            $attendanceData[] = $dayTotal > 0 ? round(($dayPresent / $dayTotal) * 100) : 0;
        }

        // Get recent activities (last 10 records from various sources)
        $recentActivities = collect();

        // Recent students
        $recentStudents = Student::latest()->take(3)->get()->map(function($student) {
            return [
                'type' => 'student',
                'icon' => 'fa-user-plus',
                'color' => 'blue',
                'title' => $student->first_name . ' ' . $student->last_name . ' was registered in ' . ($student->class->name ?? 'a class'),
                'time' => $student->created_at->diffForHumans()
            ];
        });

        // Recent attendance records
        $recentAttendance = Attendance::with('student.class')
            ->latest()
            ->take(2)
            ->get()
            ->map(function($attendance) {
                return [
                    'type' => 'attendance',
                    'icon' => 'fa-clipboard-check',
                    'color' => 'green',
                    'title' => 'Attendance marked for ' . ($attendance->student->class->name ?? 'Class'),
                    'time' => $attendance->created_at->diffForHumans()
                ];
            });

        // Recent grades
        $recentGrades = Grade::with('student', 'examType')
            ->latest()
            ->take(2)
            ->get()
            ->map(function($grade) {
                return [
                    'type' => 'grade',
                    'icon' => 'fa-file-alt',
                    'color' => 'purple',
                    'title' => ($grade->examType->name ?? 'Exam') . ' results uploaded for ' . ($grade->student->first_name ?? 'student'),
                    'time' => $grade->created_at->diffForHumans()
                ];
            });

        // Recent teachers
        $recentTeachers = Teacher::latest()->take(2)->get()->map(function($teacher) {
            return [
                'type' => 'teacher',
                'icon' => 'fa-chalkboard-teacher',
                'color' => 'orange',
                'title' => ($teacher->title ?? 'Mr./Mrs.') . ' ' . $teacher->first_name . ' ' . $teacher->last_name . ' joined as teacher',
                'time' => $teacher->created_at->diffForHumans()
            ];
        });

        $recentActivities = $recentActivities
            ->merge($recentStudents)
            ->merge($recentAttendance)
            ->merge($recentGrades)
            ->merge($recentTeachers)
            ->sortByDesc('time')
            ->take(5);

        // Get upcoming events
        $upcomingEvents = Event::where('start_date', '>=', Carbon::today())
            ->orderBy('start_date')
            ->take(3)
            ->get();

        // Get class performance data
        $classPerformance = SchoolClass::with(['students'])
            ->get()
            ->map(function($class) {
                $students = $class->students;
                $studentCount = $students->count();

                // Calculate average grade
                $avgGrade = 0;
                if ($studentCount > 0) {
                    $totalGrades = Grade::whereIn('student_id', $students->pluck('id'))->avg('grade');
                    $avgGrade = $totalGrades ? round($totalGrades, 1) : 0;
                }

                // Calculate attendance rate for the class
                $attendanceRate = 0;
                if ($studentCount > 0) {
                    $last30Days = Carbon::today()->subDays(30);
                    $classAttendance = Attendance::whereIn('student_id', $students->pluck('id'))
                        ->where('date', '>=', $last30Days)
                        ->get();

                    $presentCount = $classAttendance->where('status', 'present')->count();
                    $totalRecords = $classAttendance->count();
                    $attendanceRate = $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 1) : 0;
                }

                // Determine status based on average grade
                $status = 'Average';
                $statusBadge = 'warning';
                if ($avgGrade >= 80) {
                    $status = 'Excellent';
                    $statusBadge = 'success';
                } elseif ($avgGrade >= 70) {
                    $status = 'Good';
                    $statusBadge = 'primary';
                }

                return [
                    'id' => $class->id,
                    'name' => $class->name,
                    'student_count' => $studentCount,
                    'avg_grade' => $avgGrade,
                    'attendance_rate' => $attendanceRate,
                    'status' => $status,
                    'status_badge' => $statusBadge
                ];
            })
            ->sortByDesc('avg_grade')
            ->take(5);

        return view('dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'totalClasses',
            'attendancePercentage',
            'last7Days',
            'attendanceData',
            'recentActivities',
            'upcomingEvents',
            'classPerformance'
        ));
    }
}

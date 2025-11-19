<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Attendance;
use App\Models\Grade;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index()
    {
        // Overview of all available reports
        $reportCategories = [
            [
                'name' => 'Attendance Reports',
                'icon' => 'fa-clipboard-check',
                'color' => 'blue',
                'description' => 'Daily, weekly, and monthly attendance statistics',
                'route' => 'reports.attendance',
                'count' => 5
            ],
            [
                'name' => 'Grade Reports',
                'icon' => 'fa-chart-bar',
                'color' => 'green',
                'description' => 'Class performance, individual grades, and analytics',
                'route' => 'reports.grades',
                'count' => 4
            ],
            [
                'name' => 'Student Reports',
                'icon' => 'fa-user-graduate',
                'color' => 'purple',
                'description' => 'Individual student progress and comprehensive reports',
                'route' => 'reports.students',
                'count' => 3
            ],
            [
                'name' => 'Teacher Reports',
                'icon' => 'fa-chalkboard-teacher',
                'color' => 'orange',
                'description' => 'Teacher performance and activity reports',
                'route' => 'reports.teachers',
                'count' => 2
            ]
        ];

        return view('reports.index', compact('reportCategories'));
    }

    public function attendance()
    {
        // Get attendance statistics for the last 30 days
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        $attendanceData = Attendance::whereBetween('date', [$startDate, $endDate])
            ->selectRaw('date,
                COUNT(*) as total,
                SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        // Class-wise attendance
        $classAttendance = SchoolClass::with('students')->get()->map(function($class) use ($startDate, $endDate) {
            $students = $class->students;
            $studentCount = $students->count();

            if ($studentCount == 0) {
                return null;
            }

            $attendance = Attendance::whereIn('student_id', $students->pluck('id'))
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            $totalRecords = $attendance->count();
            $presentCount = $attendance->where('status', 'present')->count();
            $absentCount = $attendance->where('status', 'absent')->count();
            $lateCount = $attendance->where('status', 'late')->count();

            return [
                'class_name' => $class->name,
                'student_count' => $studentCount,
                'present_count' => $presentCount,
                'absent_count' => $absentCount,
                'late_count' => $lateCount,
                'attendance_rate' => $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 1) : 0
            ];
        })->filter();

        return view('reports.attendance', compact('attendanceData', 'classAttendance', 'startDate', 'endDate'));
    }

    public function grades()
    {
        // Get grade statistics by class
        $classGrades = SchoolClass::with(['students'])->get()->map(function($class) {
            $students = $class->students;
            $studentCount = $students->count();

            if ($studentCount == 0) {
                return null;
            }

            $grades = Grade::whereIn('student_id', $students->pluck('id'))->get();

            $avgGrade = $grades->avg('grade') ?? 0;
            $highestGrade = $grades->max('grade') ?? 0;
            $lowestGrade = $grades->min('grade') ?? 0;
            $passingCount = $grades->where('grade', '>=', 50)->count();
            $failingCount = $grades->where('grade', '<', 50)->count();

            return [
                'class_name' => $class->name,
                'student_count' => $studentCount,
                'avg_grade' => round($avgGrade, 2),
                'highest_grade' => $highestGrade,
                'lowest_grade' => $lowestGrade,
                'passing_count' => $passingCount,
                'failing_count' => $failingCount,
                'total_grades' => $grades->count()
            ];
        })->filter();

        // Grade distribution
        $gradeDistribution = [
            'A (90-100)' => Grade::whereBetween('grade', [90, 100])->count(),
            'B (80-89)' => Grade::whereBetween('grade', [80, 89])->count(),
            'C (70-79)' => Grade::whereBetween('grade', [70, 79])->count(),
            'D (60-69)' => Grade::whereBetween('grade', [60, 69])->count(),
            'F (0-59)' => Grade::where('grade', '<', 60)->count(),
        ];

        return view('reports.grades', compact('classGrades', 'gradeDistribution'));
    }

    public function students()
    {
        // Get student statistics
        $totalStudents = Student::count();
        $activeStudents = Student::where('status', 'active')->count();
        $inactiveStudents = Student::where('status', 'inactive')->count();
        $graduatedStudents = Student::where('status', 'graduated')->count();

        // Students by class
        $studentsByClass = SchoolClass::withCount('students')->get()->map(function($class) {
            return [
                'class_name' => $class->name,
                'student_count' => $class->students_count,
                'capacity' => $class->capacity ?? 40,
                'utilization' => $class->capacity ? round(($class->students_count / $class->capacity) * 100, 1) : 0
            ];
        });

        // Recent enrollments
        $recentEnrollments = Student::latest()->take(10)->get();

        return view('reports.students', compact(
            'totalStudents',
            'activeStudents',
            'inactiveStudents',
            'graduatedStudents',
            'studentsByClass',
            'recentEnrollments'
        ));
    }

    public function teachers()
    {
        // Get teacher statistics
        $totalTeachers = Teacher::count();
        $activeTeachers = Teacher::where('status', 'active')->count();
        $inactiveTeachers = Teacher::where('status', 'inactive')->count();

        // Teachers by subject (demo data)
        $teachersBySubject = [
            ['subject' => 'Mathematics', 'count' => 8],
            ['subject' => 'Science', 'count' => 7],
            ['subject' => 'English', 'count' => 6],
            ['subject' => 'Islamic Studies', 'count' => 5],
            ['subject' => 'Arabic', 'count' => 5],
            ['subject' => 'Social Studies', 'count' => 4],
            ['subject' => 'Physical Education', 'count' => 3],
        ];

        // Recent hires
        $recentHires = Teacher::latest()->take(10)->get();

        return view('reports.teachers', compact(
            'totalTeachers',
            'activeTeachers',
            'inactiveTeachers',
            'teachersBySubject',
            'recentHires'
        ));
    }
}

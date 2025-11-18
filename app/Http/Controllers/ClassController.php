<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassController extends Controller
{
    /**
     * Display a listing of classes.
     */
    public function index(Request $request)
    {
        $query = SchoolClass::with('classTeacher');

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filter by academic year
        if ($request->filled('academic_year')) {
            $query->byAcademicYear($request->academic_year);
        }

        // Filter by teacher
        if ($request->filled('teacher_id')) {
            $query->byTeacher($request->teacher_id);
        }

        // Sorting
        $sortField = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');

        // Validate sort field
        $allowedSorts = ['name', 'class_code', 'capacity', 'current_enrollment', 'status', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Pagination
        $classes = $query->paginate(15)->withQueryString();

        // Calculate stats
        $stats = [
            'total' => SchoolClass::count(),
            'active' => SchoolClass::where('status', 'active')->count(),
            'inactive' => SchoolClass::where('status', 'inactive')->count(),
            'full' => SchoolClass::whereColumn('current_enrollment', '>=', 'capacity')->count(),
        ];

        // Get all teachers for filter
        $teachers = Teacher::active()->orderBy('first_name')->get();

        // Get unique academic years
        $academicYears = SchoolClass::distinct()->pluck('academic_year')->filter()->sort()->values();

        return view('classes.index', compact('classes', 'stats', 'teachers', 'academicYears'));
    }

    /**
     * Show the form for creating a new class.
     */
    public function create()
    {
        $teachers = Teacher::active()->orderBy('first_name')->get();

        return view('classes.create', compact('teachers'));
    }

    /**
     * Store a newly created class in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'class_code' => 'required|string|max:255|unique:school_classes,class_code',
            'class_teacher_id' => 'nullable|exists:teachers,id',
            'capacity' => 'required|integer|min:1',
            'current_enrollment' => 'nullable|integer|min:0',
            'room_number' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:255',
            'status' => ['required', Rule::in(['active', 'inactive', 'archived'])],
            'description' => 'nullable|string',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
        ]);

        // Set default current enrollment if not provided
        if (!isset($validated['current_enrollment'])) {
            $validated['current_enrollment'] = 0;
        }

        $class = SchoolClass::create($validated);

        return redirect()
            ->route('classes.show', $class)
            ->with('success', 'Class created successfully!');
    }

    /**
     * Display the specified class.
     */
    public function show(SchoolClass $class)
    {
        $class->load('classTeacher', 'students');

        return view('classes.show', compact('class'));
    }

    /**
     * Show the form for editing the specified class.
     */
    public function edit(SchoolClass $class)
    {
        $teachers = Teacher::active()->orderBy('first_name')->get();

        return view('classes.edit', compact('class', 'teachers'));
    }

    /**
     * Update the specified class in storage.
     */
    public function update(Request $request, SchoolClass $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'class_code' => ['required', 'string', 'max:255', Rule::unique('school_classes', 'class_code')->ignore($class->id)],
            'class_teacher_id' => 'nullable|exists:teachers,id',
            'capacity' => 'required|integer|min:1',
            'current_enrollment' => 'nullable|integer|min:0',
            'room_number' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:255',
            'status' => ['required', Rule::in(['active', 'inactive', 'archived'])],
            'description' => 'nullable|string',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
        ]);

        $class->update($validated);

        return redirect()
            ->route('classes.show', $class)
            ->with('success', 'Class updated successfully!');
    }

    /**
     * Remove the specified class from storage.
     */
    public function destroy(SchoolClass $class)
    {
        $class->delete();

        return redirect()
            ->route('classes.index')
            ->with('success', 'Class deleted successfully!');
    }

    /**
     * Export classes to CSV
     */
    public function exportCsv(Request $request)
    {
        $query = SchoolClass::with('classTeacher');

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('academic_year')) {
            $query->byAcademicYear($request->academic_year);
        }

        $classes = $query->orderBy('name')->get();

        $filename = 'classes_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($classes) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'Class Code',
                'Class Name',
                'Section',
                'Class Teacher',
                'Capacity',
                'Current Enrollment',
                'Available Seats',
                'Room Number',
                'Academic Year',
                'Status',
            ]);

            // Add data rows
            foreach ($classes as $class) {
                fputcsv($file, [
                    $class->class_code,
                    $class->name,
                    $class->section ?? '-',
                    $class->classTeacher ? $class->classTeacher->full_name : '-',
                    $class->capacity,
                    $class->current_enrollment,
                    $class->available_seats,
                    $class->room_number ?? '-',
                    $class->academic_year ?? '-',
                    ucfirst($class->status),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

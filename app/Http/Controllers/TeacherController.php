<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    /**
     * Display a listing of the teachers.
     */
    public function index(Request $request)
    {
        $query = Teacher::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Filter by subject
        if ($request->filled('subject')) {
            $query->bySubject($request->subject);
        }

        // Filter by class
        if ($request->filled('class')) {
            $query->byClass($request->class);
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        // Validate sort field to prevent SQL injection
        $allowedSorts = ['first_name', 'last_name', 'employee_id', 'email', 'status', 'date_joined', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Pagination
        $teachers = $query->paginate(15)->withQueryString();

        // Calculate stats
        $stats = [
            'total' => Teacher::count(),
            'active' => Teacher::where('status', 'active')->count(),
            'inactive' => Teacher::where('status', 'inactive')->count(),
            'on_leave' => Teacher::where('status', 'on_leave')->count(),
        ];

        return view('teachers.index', compact('teachers', 'stats'));
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create()
    {
        // Get unique subjects and classes from existing teachers for dropdown suggestions
        $existingSubjects = Teacher::whereNotNull('subjects')
            ->get()
            ->pluck('subjects')
            ->flatten()
            ->unique()
            ->values()
            ->toArray();

        $existingClasses = Teacher::whereNotNull('classes')
            ->get()
            ->pluck('classes')
            ->flatten()
            ->unique()
            ->values()
            ->toArray();

        return view('teachers.create', compact('existingSubjects', 'existingClasses'));
    }

    /**
     * Store a newly created teacher in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:255|unique:teachers,employee_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:teachers,email',
            'phone' => 'required|string|max:255',
            'gender' => ['required', Rule::in(['male', 'female'])],
            'date_of_birth' => 'nullable|date|before:today',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'subjects' => 'nullable|array',
            'subjects.*' => 'string|max:255',
            'classes' => 'nullable|array',
            'classes.*' => 'string|max:255',
            'date_joined' => 'nullable|date',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => ['required', Rule::in(['active', 'inactive', 'on_leave'])],
            'salary' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('teachers/profiles', 'public');
            $validated['profile_picture'] = $path;
        }

        // Set default country if not provided
        if (empty($validated['country'])) {
            $validated['country'] = 'Nigeria';
        }

        $teacher = Teacher::create($validated);

        return redirect()
            ->route('teachers.show', $teacher)
            ->with('success', 'Teacher added successfully!');
    }

    /**
     * Display the specified teacher.
     */
    public function show(Teacher $teacher)
    {
        return view('teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit(Teacher $teacher)
    {
        // Get unique subjects and classes from existing teachers for dropdown suggestions
        $existingSubjects = Teacher::whereNotNull('subjects')
            ->get()
            ->pluck('subjects')
            ->flatten()
            ->unique()
            ->values()
            ->toArray();

        $existingClasses = Teacher::whereNotNull('classes')
            ->get()
            ->pluck('classes')
            ->flatten()
            ->unique()
            ->values()
            ->toArray();

        return view('teachers.edit', compact('teacher', 'existingSubjects', 'existingClasses'));
    }

    /**
     * Update the specified teacher in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'string', 'max:255', Rule::unique('teachers', 'employee_id')->ignore($teacher->id)],
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('teachers', 'email')->ignore($teacher->id)],
            'phone' => 'required|string|max:255',
            'gender' => ['required', Rule::in(['male', 'female'])],
            'date_of_birth' => 'nullable|date|before:today',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'subjects' => 'nullable|array',
            'subjects.*' => 'string|max:255',
            'classes' => 'nullable|array',
            'classes.*' => 'string|max:255',
            'date_joined' => 'nullable|date',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => ['required', Rule::in(['active', 'inactive', 'on_leave'])],
            'salary' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($teacher->profile_picture) {
                Storage::disk('public')->delete($teacher->profile_picture);
            }

            $path = $request->file('profile_picture')->store('teachers/profiles', 'public');
            $validated['profile_picture'] = $path;
        }

        $teacher->update($validated);

        return redirect()
            ->route('teachers.show', $teacher)
            ->with('success', 'Teacher updated successfully!');
    }

    /**
     * Remove the specified teacher from storage (soft delete).
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher deleted successfully!');
    }

    /**
     * Export teachers to CSV
     */
    public function exportCsv(Request $request)
    {
        $query = Teacher::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $teachers = $query->orderBy('first_name')->get();

        $filename = 'teachers_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($teachers) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'Employee ID',
                'First Name',
                'Last Name',
                'Email',
                'Phone',
                'Gender',
                'Date of Birth',
                'Qualification',
                'Subjects',
                'Classes',
                'Date Joined',
                'Status',
                'Salary',
            ]);

            // Add data rows
            foreach ($teachers as $teacher) {
                fputcsv($file, [
                    $teacher->employee_id,
                    $teacher->first_name,
                    $teacher->last_name,
                    $teacher->email,
                    $teacher->phone,
                    ucfirst($teacher->gender),
                    $teacher->date_of_birth?->format('Y-m-d') ?? '',
                    $teacher->qualification ?? '',
                    $teacher->subjects_list,
                    $teacher->classes_list,
                    $teacher->date_joined?->format('Y-m-d') ?? '',
                    ucfirst($teacher->status),
                    $teacher->salary ? number_format($teacher->salary, 2) : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show assign classes/subjects form
     */
    public function assign(Teacher $teacher)
    {
        // Get all unique subjects and classes from the system
        $allSubjects = Teacher::whereNotNull('subjects')
            ->get()
            ->pluck('subjects')
            ->flatten()
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        $allClasses = Teacher::whereNotNull('classes')
            ->get()
            ->pluck('classes')
            ->flatten()
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        // Common class levels for Nigerian schools
        $commonClasses = [
            'Nursery 1', 'Nursery 2', 'Nursery 3',
            'Primary 1', 'Primary 2', 'Primary 3', 'Primary 4', 'Primary 5', 'Primary 6',
            'JSS 1', 'JSS 2', 'JSS 3',
            'SSS 1', 'SSS 2', 'SSS 3',
        ];

        // Merge with existing classes
        $allClasses = array_unique(array_merge($allClasses, $commonClasses));
        sort($allClasses);

        // Common subjects
        $commonSubjects = [
            'Mathematics', 'English Language', 'Basic Science', 'Social Studies',
            'Arabic', 'Islamic Studies', 'Qur\'an', 'Hadith',
            'Computer Science', 'Physical Education', 'Creative Arts',
            'Biology', 'Chemistry', 'Physics', 'Geography', 'Economics',
            'Government', 'Literature', 'History',
        ];

        // Merge with existing subjects
        $allSubjects = array_unique(array_merge($allSubjects, $commonSubjects));
        sort($allSubjects);

        return view('teachers.assign', compact('teacher', 'allSubjects', 'allClasses'));
    }

    /**
     * Update teacher's assigned classes and subjects
     */
    public function updateAssignments(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'subjects' => 'nullable|array',
            'subjects.*' => 'string|max:255',
            'classes' => 'nullable|array',
            'classes.*' => 'string|max:255',
        ]);

        $teacher->update($validated);

        return redirect()
            ->route('teachers.show', $teacher)
            ->with('success', 'Assignments updated successfully!');
    }
}

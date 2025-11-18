<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\RegistrationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource with filters
     */
    public function index(Request $request)
    {
        $query = Student::query();

        // Apply search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Apply class filter
        if ($request->filled('class')) {
            $query->where('class_level', $request->class);
        }

        // Apply gender filter
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default to active students only
            $query->where('status', 'active');
        }

        // Apply session filter
        if ($request->filled('session')) {
            $query->where('session_year', $request->session);
        }

        // Apply admission year filter
        if ($request->filled('admission_year')) {
            $query->whereYear('admission_date', $request->admission_year);
        }

        // Get students with pagination
        $students = $query->with('registrationToken')
            ->latest('created_at')
            ->paginate(20);

        // Get statistics
        $stats = [
            'total' => Student::count(),
            'active' => Student::active()->count(),
            'pending' => Student::pending()->count(),
            'male' => Student::where('gender', 'male')->count(),
            'female' => Student::where('gender', 'female')->count(),
            'new_this_month' => Student::whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))
                ->count(),
        ];

        return view('students.index', compact('students', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Personal Information
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'blood_group' => 'nullable|string|max:10',
            'nationality' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'place_of_birth' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',

            // Academic Information
            'class_level' => 'required|string',
            'section' => 'nullable|string',
            'session_year' => 'required|string',
            'roll_number' => 'nullable|string|max:50',
            'status' => 'required|in:pending,active,inactive,graduated,withdrawn,suspended',

            // Contact Information
            'email' => 'nullable|email|unique:students,email',
            'phone' => 'nullable|string|max:20',

            // Parent/Guardian Information - Primary
            'parent1_name' => 'nullable|string|max:255',
            'parent1_relationship' => 'nullable|string|max:50',
            'parent1_phone' => 'nullable|string|max:20',
            'parent1_email' => 'nullable|email|max:255',
            'parent1_occupation' => 'nullable|string|max:255',

            // Parent/Guardian Information - Secondary
            'parent2_name' => 'nullable|string|max:255',
            'parent2_relationship' => 'nullable|string|max:50',
            'parent2_phone' => 'nullable|string|max:20',
            'parent2_email' => 'nullable|email|max:255',
            'parent2_occupation' => 'nullable|string|max:255',

            // Previous School Information
            'previous_school_name' => 'nullable|string|max:255',
            'previous_school_address' => 'nullable|string',
            'previous_school_grade' => 'nullable|string|max:100',
            'previous_school_year' => 'nullable|integer|min:2000|max:' . date('Y'),
            'previous_school_reason' => 'nullable|string',

            // Health & Medical Information
            'allergies' => 'nullable|array',
            'allergies.*' => 'string|max:255',
            'medical_conditions' => 'nullable|string',
            'medications' => 'nullable|string',
            'emergency_medical_consent' => 'nullable|boolean',
            'special_needs' => 'nullable|string',

            // Additional Information
            'notes' => 'nullable|string',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('students/photos', 'public');
        }

        // Convert allergies array to JSON if present
        if (isset($validated['allergies'])) {
            $validated['allergies'] = json_encode($validated['allergies']);
        }

        // Convert emergency_medical_consent to boolean
        $validated['emergency_medical_consent'] = $request->has('emergency_medical_consent');

        // Generate admission number automatically
        $validated['admission_number'] = Student::generateAdmissionNumber();
        $validated['admission_date'] = now();
        $validated['created_by'] = auth()->id() ?? 1;

        $student = Student::create($validated);

        return redirect()
            ->route('students.show', $student->id)
            ->with('success', 'Student created successfully! Admission Number: ' . $student->admission_number);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = Student::with('registrationToken')->findOrFail($id);
        return view('students.show', compact('student'));
    }

    /**
     * Display printable student profile
     */
    public function print(string $id)
    {
        $student = Student::with('registrationToken')->findOrFail($id);
        return view('students.print', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            // Personal Information
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'blood_group' => 'nullable|string|max:10',
            'nationality' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'place_of_birth' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',

            // Academic Information
            'class_level' => 'required|string',
            'section' => 'nullable|string',
            'session_year' => 'required|string',
            'roll_number' => 'nullable|string|max:50',
            'status' => 'required|in:pending,active,inactive,graduated,withdrawn,suspended',

            // Contact Information
            'email' => 'nullable|email|unique:students,email,' . $id,
            'phone' => 'nullable|string|max:20',

            // Parent/Guardian Information - Primary
            'parent1_name' => 'nullable|string|max:255',
            'parent1_relationship' => 'nullable|string|max:50',
            'parent1_phone' => 'nullable|string|max:20',
            'parent1_email' => 'nullable|email|max:255',
            'parent1_occupation' => 'nullable|string|max:255',

            // Parent/Guardian Information - Secondary
            'parent2_name' => 'nullable|string|max:255',
            'parent2_relationship' => 'nullable|string|max:50',
            'parent2_phone' => 'nullable|string|max:20',
            'parent2_email' => 'nullable|email|max:255',
            'parent2_occupation' => 'nullable|string|max:255',

            // Previous School Information
            'previous_school_name' => 'nullable|string|max:255',
            'previous_school_address' => 'nullable|string',
            'previous_school_grade' => 'nullable|string|max:100',
            'previous_school_year' => 'nullable|integer|min:2000|max:' . date('Y'),
            'previous_school_reason' => 'nullable|string',

            // Health & Medical Information
            'allergies' => 'nullable|array',
            'allergies.*' => 'string|max:255',
            'medical_conditions' => 'nullable|string',
            'medications' => 'nullable|string',
            'emergency_medical_consent' => 'nullable|boolean',
            'special_needs' => 'nullable|string',

            // Additional Information
            'notes' => 'nullable|string',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($student->photo_path) {
                Storage::disk('public')->delete($student->photo_path);
            }
            $validated['photo_path'] = $request->file('photo')->store('students/photos', 'public');
        }

        // Convert allergies array to JSON if present
        if (isset($validated['allergies'])) {
            $validated['allergies'] = json_encode($validated['allergies']);
        }

        // Convert emergency_medical_consent to boolean
        $validated['emergency_medical_consent'] = $request->has('emergency_medical_consent');

        $validated['updated_by'] = auth()->id() ?? 1;

        $student->update($validated);

        return redirect()
            ->route('students.show', $student->id)
            ->with('success', 'Student updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::findOrFail($id);
        $student->delete(); // Soft delete

        return redirect()
            ->route('students.index')
            ->with('success', 'Student deleted successfully!');
    }

    /**
     * Update student status
     */
    public function updateStatus(Request $request, string $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,active,inactive,graduated,withdrawn,suspended',
        ]);

        $student->update($validated);

        return redirect()
            ->route('students.show', $student->id)
            ->with('success', 'Student status updated successfully!');
    }

    /**
     * Export students to CSV
     */
    public function export(Request $request)
    {
        $query = Student::query();

        // Apply filters if provided
        if ($request->filled('class')) {
            $query->where('class_level', $request->class);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('session')) {
            $query->where('session_year', $request->session);
        }

        $students = $query->get();

        $filename = 'students_export_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'Admission Number',
                'First Name',
                'Middle Name',
                'Last Name',
                'Date of Birth',
                'Gender',
                'Blood Group',
                'Nationality',
                'Religion',
                'Place of Birth',
                'Address',
                'Email',
                'Phone',
                'Class Level',
                'Section',
                'Session Year',
                'Roll Number',
                'Status',
                'Parent 1 Name',
                'Parent 1 Relationship',
                'Parent 1 Phone',
                'Parent 1 Email',
                'Parent 2 Name',
                'Parent 2 Relationship',
                'Parent 2 Phone',
                'Parent 2 Email',
                'Previous School',
                'Admission Date',
            ]);

            // Add student data
            foreach ($students as $student) {
                fputcsv($file, [
                    $student->admission_number,
                    $student->first_name,
                    $student->middle_name,
                    $student->last_name,
                    $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '',
                    $student->gender,
                    $student->blood_group,
                    $student->nationality,
                    $student->religion,
                    $student->place_of_birth,
                    $student->address,
                    $student->email,
                    $student->phone,
                    $student->class_level,
                    $student->section,
                    $student->session_year,
                    $student->roll_number,
                    $student->status,
                    $student->parent1_name,
                    $student->parent1_relationship,
                    $student->parent1_phone,
                    $student->parent1_email,
                    $student->parent2_name,
                    $student->parent2_relationship,
                    $student->parent2_phone,
                    $student->parent2_email,
                    $student->previous_school_name,
                    $student->admission_date ? $student->admission_date->format('Y-m-d') : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show import form
     */
    public function importForm()
    {
        return view('students.import');
    }

    /**
     * Import students from CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        $csv = array_map('str_getcsv', file($path));
        $header = array_shift($csv); // Remove header row

        $imported = 0;
        $skipped = 0;
        $errors = [];

        foreach ($csv as $index => $row) {
            try {
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                // Map CSV columns to database fields
                $data = [
                    'first_name' => $row[1] ?? null,
                    'middle_name' => $row[2] ?? null,
                    'last_name' => $row[3] ?? null,
                    'date_of_birth' => $row[4] ?? null,
                    'gender' => $row[5] ?? null,
                    'blood_group' => $row[6] ?? null,
                    'nationality' => $row[7] ?? 'Nigerian',
                    'religion' => $row[8] ?? null,
                    'place_of_birth' => $row[9] ?? null,
                    'address' => $row[10] ?? null,
                    'email' => $row[11] ?? null,
                    'phone' => $row[12] ?? null,
                    'class_level' => $row[13] ?? null,
                    'section' => $row[14] ?? null,
                    'session_year' => $row[15] ?? '2024/2025',
                    'roll_number' => $row[16] ?? null,
                    'status' => $row[17] ?? 'pending',
                    'parent1_name' => $row[18] ?? null,
                    'parent1_relationship' => $row[19] ?? null,
                    'parent1_phone' => $row[20] ?? null,
                    'parent1_email' => $row[21] ?? null,
                    'parent2_name' => $row[22] ?? null,
                    'parent2_relationship' => $row[23] ?? null,
                    'parent2_phone' => $row[24] ?? null,
                    'parent2_email' => $row[25] ?? null,
                    'previous_school_name' => $row[26] ?? null,
                ];

                // Validate required fields
                if (empty($data['first_name']) || empty($data['last_name']) || empty($data['class_level'])) {
                    $skipped++;
                    $errors[] = "Row " . ($index + 2) . ": Missing required fields (First Name, Last Name, or Class Level)";
                    continue;
                }

                // Generate admission number
                $data['admission_number'] = Student::generateAdmissionNumber();
                $data['admission_date'] = now();
                $data['created_by'] = auth()->id() ?? 1;

                Student::create($data);
                $imported++;

            } catch (\Exception $e) {
                $skipped++;
                $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        $message = "Import completed: {$imported} students imported successfully";
        if ($skipped > 0) {
            $message .= ", {$skipped} rows skipped";
        }

        return redirect()
            ->route('students.index')
            ->with('success', $message)
            ->with('import_errors', $errors);
    }

    /**
     * Download CSV template
     */
    public function downloadTemplate()
    {
        $filename = 'students_import_template.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'Admission Number (Auto-generated)',
                'First Name *',
                'Middle Name',
                'Last Name *',
                'Date of Birth (YYYY-MM-DD)',
                'Gender (male/female)',
                'Blood Group',
                'Nationality',
                'Religion',
                'Place of Birth',
                'Address',
                'Email',
                'Phone',
                'Class Level *',
                'Section',
                'Session Year',
                'Roll Number',
                'Status (active/pending/inactive)',
                'Parent 1 Name',
                'Parent 1 Relationship',
                'Parent 1 Phone',
                'Parent 1 Email',
                'Parent 2 Name',
                'Parent 2 Relationship',
                'Parent 2 Phone',
                'Parent 2 Email',
                'Previous School',
                'Admission Date (Auto-generated)',
            ]);

            // Add sample data row
            fputcsv($file, [
                'Leave empty',
                'Ahmed',
                'Hassan',
                'Ibrahim',
                '2010-01-15',
                'male',
                'O+',
                'Nigerian',
                'Islam',
                'Lagos',
                '123 Main Street, Lagos',
                'parent@example.com',
                '+234 XXX XXX XXXX',
                'Primary 5',
                'A',
                '2024/2025',
                '001',
                'active',
                'Mr. Ibrahim Hassan',
                'Father',
                '+234 XXX XXX XXXX',
                'father@example.com',
                'Mrs. Fatima Hassan',
                'Mother',
                '+234 XXX XXX XXXX',
                'mother@example.com',
                'ABC Primary School',
                'Leave empty',
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

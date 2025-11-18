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
}

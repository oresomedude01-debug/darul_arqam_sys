<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationToken;
use App\Models\Student;
use Carbon\Carbon;

class EnrollmentController extends Controller
{
    /**
     * Show token validation form (Step 1)
     */
    public function showTokenForm()
    {
        return view('enrollment.token');
    }

    /**
     * Validate token and proceed to enrollment (Step 1 submission)
     */
    public function validateToken(Request $request)
    {
        $request->validate([
            'token_code' => 'required|string',
        ]);

        $token = RegistrationToken::where('token_code', $request->token_code)->first();

        if (!$token) {
            return back()->withErrors(['token_code' => 'Invalid token code. Please check and try again.']);
        }

        if (!$token->isValid()) {
            $reason = 'This token is no longer valid.';

            if ($token->status === 'consumed') {
                $reason = 'This token has already been used.';
            } elseif ($token->status === 'disabled') {
                $reason = 'This token has been disabled.';
            } elseif ($token->status === 'expired' || ($token->expires_at && Carbon::now()->isAfter($token->expires_at))) {
                $reason = 'This token has expired.';
            }

            return back()->withErrors(['token_code' => $reason]);
        }

        // Store token in session
        session([
            'enrollment_token_id' => $token->id,
            'enrollment_token_code' => $token->token_code,
            'enrollment_data' => [],
        ]);

        return redirect()->route('enrollment.step1');
    }

    /**
     * Show student details form (Step 2)
     */
    public function showStep1()
    {
        if (!session('enrollment_token_id')) {
            return redirect()->route('enrollment.token')->withErrors(['token' => 'Please enter a valid token first.']);
        }

        return view('enrollment.step1', [
            'data' => session('enrollment_data', [])
        ]);
    }

    /**
     * Process student details (Step 2 submission)
     */
    public function processStep1(Request $request)
    {
        $validated = $request->validate([
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
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('students/photos', 'public');
        }

        // Merge with existing data
        $enrollmentData = array_merge(session('enrollment_data', []), $validated);
        session(['enrollment_data' => $enrollmentData]);

        return redirect()->route('enrollment.step2');
    }

    /**
     * Show previous school form (Step 3)
     */
    public function showStep2()
    {
        if (!session('enrollment_token_id')) {
            return redirect()->route('enrollment.token');
        }

        return view('enrollment.step2', [
            'data' => session('enrollment_data', [])
        ]);
    }

    /**
     * Process previous school (Step 3 submission)
     */
    public function processStep2(Request $request)
    {
        $validated = $request->validate([
            'previous_school_name' => 'nullable|string|max:255',
            'previous_school_address' => 'nullable|string',
            'previous_school_grade' => 'nullable|string|max:100',
            'previous_school_year' => 'nullable|integer|min:2000|max:' . date('Y'),
            'previous_school_reason' => 'nullable|string',
        ]);

        $enrollmentData = array_merge(session('enrollment_data', []), $validated);
        session(['enrollment_data' => $enrollmentData]);

        return redirect()->route('enrollment.step3');
    }

    /**
     * Show health information form (Step 4)
     */
    public function showStep3()
    {
        if (!session('enrollment_token_id')) {
            return redirect()->route('enrollment.token');
        }

        return view('enrollment.step3', [
            'data' => session('enrollment_data', [])
        ]);
    }

    /**
     * Process health information (Step 4 submission)
     */
    public function processStep3(Request $request)
    {
        $validated = $request->validate([
            'allergies' => 'nullable|array',
            'allergies.*' => 'string|max:255',
            'medical_conditions' => 'nullable|string',
            'medications' => 'nullable|string',
            'emergency_medical_consent' => 'nullable|boolean',
            'special_needs' => 'nullable|string',
        ]);

        // Convert allergies to JSON if present
        if (isset($validated['allergies'])) {
            $validated['allergies'] = $validated['allergies'];
        }

        $validated['emergency_medical_consent'] = $request->has('emergency_medical_consent');

        $enrollmentData = array_merge(session('enrollment_data', []), $validated);
        session(['enrollment_data' => $enrollmentData]);

        return redirect()->route('enrollment.step4');
    }

    /**
     * Show parent/guardian form (Step 5)
     */
    public function showStep4()
    {
        if (!session('enrollment_token_id')) {
            return redirect()->route('enrollment.token');
        }

        return view('enrollment.step4', [
            'data' => session('enrollment_data', [])
        ]);
    }

    /**
     * Process parent/guardian and complete enrollment (Step 5 submission)
     */
    public function processStep4(Request $request)
    {
        $validated = $request->validate([
            'parent1_name' => 'required|string|max:255',
            'parent1_relationship' => 'required|string|max:50',
            'parent1_phone' => 'required|string|max:20',
            'parent1_email' => 'required|email|max:255',
            'parent1_occupation' => 'nullable|string|max:255',
            'parent2_name' => 'nullable|string|max:255',
            'parent2_relationship' => 'nullable|string|max:50',
            'parent2_phone' => 'nullable|string|max:20',
            'parent2_email' => 'nullable|email|max:255',
            'parent2_occupation' => 'nullable|string|max:255',
        ]);

        // Merge all enrollment data
        $enrollmentData = array_merge(session('enrollment_data', []), $validated);

        // Get token
        $token = RegistrationToken::findOrFail(session('enrollment_token_id'));

        // Create student
        $studentData = $enrollmentData;
        $studentData['admission_number'] = Student::generateAdmissionNumber();
        $studentData['admission_date'] = now();
        $studentData['status'] = 'pending'; // Public enrollments start as pending
        $studentData['registration_token_id'] = $token->id;
        $studentData['session_year'] = $token->session_year ?? '2024/2025';
        $studentData['class_level'] = $token->class_level ?? 'Primary 1';

        // Convert allergies to JSON
        if (isset($studentData['allergies'])) {
            $studentData['allergies'] = json_encode($studentData['allergies']);
        }

        $student = Student::create($studentData);

        // Mark token as consumed
        $token->markAsConsumed($student->id, $request->ip());

        // Store admission number in session for success page
        session(['enrollment_admission_number' => $student->admission_number]);

        // Clear enrollment data
        session()->forget(['enrollment_token_id', 'enrollment_token_code', 'enrollment_data']);

        return redirect()->route('enrollment.success');
    }

    /**
     * Show success page
     */
    public function success()
    {
        $admissionNumber = session('enrollment_admission_number');

        if (!$admissionNumber) {
            return redirect()->route('enrollment.token');
        }

        // Clear admission number from session after displaying
        session()->forget('enrollment_admission_number');

        return view('enrollment.success', [
            'admissionNumber' => $admissionNumber
        ]);
    }
}

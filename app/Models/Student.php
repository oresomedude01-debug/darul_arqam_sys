<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        // Admission Details
        'admission_number',
        'admission_date',
        'status',
        'registration_token_id',

        // Personal Information
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'gender',
        'nationality',
        'religion',
        'place_of_birth',
        'address',
        'photo_path',
        'blood_group',

        // Academic Information
        'class_level',
        'section',
        'session_year',
        'roll_number',

        // Contact Information
        'email',
        'phone',

        // Previous School Information
        'previous_school_name',
        'previous_school_address',
        'previous_school_grade',
        'previous_school_year',
        'previous_school_reason',
        'previous_result_path',

        // Parent/Guardian Information
        'parent1_name',
        'parent1_relationship',
        'parent1_phone',
        'parent1_email',
        'parent1_occupation',
        'parent2_name',
        'parent2_relationship',
        'parent2_phone',
        'parent2_email',
        'parent2_occupation',
        'emergency_contact_name',
        'emergency_contact_phone',
        'preferred_contact_method',

        // Health & Medical Information
        'allergies',
        'medical_conditions',
        'medications',
        'emergency_medical_consent',
        'special_needs',

        // Additional Information
        'notes',

        // System Fields
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'admission_date' => 'date',
        'date_of_birth' => 'date',
        'allergies' => 'array',
        'emergency_medical_consent' => 'boolean',
    ];

    /**
     * Generate admission number
     * Format: YYYYMMPPTTTT
     * - YYYY = Year
     * - MM = Month
     * - PP = Position in month (2 digits)
     * - TTTT = Total position (4 digits)
     */
    public static function generateAdmissionNumber(): string
    {
        $year = date('Y');
        $month = date('m');

        // Get count for this month
        $monthCount = self::whereYear('admission_date', $year)
            ->whereMonth('admission_date', $month)
            ->count() + 1;

        // Get total count (all time)
        $totalCount = self::count() + 1;

        // Format: YYYYMMPPTTTT
        $admissionNumber = sprintf(
            '%s%s%02d%04d',
            $year,
            $month,
            $monthCount,
            $totalCount
        );

        return $admissionNumber;
    }

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute(): string
    {
        $parts = array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
        ]);

        return implode(' ', $parts);
    }

    /**
     * Get age
     */
    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->date_of_birth)->age;
    }

    /**
     * Relationships
     */
    public function registrationToken()
    {
        return $this->belongsTo(RegistrationToken::class);
    }

    /**
     * Get all attendance records for this student
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get all grades/results for this student
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForSession($query, string $session)
    {
        return $query->where('session_year', $session);
    }

    public function scopeForClass($query, string $classLevel)
    {
        return $query->where('class_level', $classLevel);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('admission_number', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });
    }
}

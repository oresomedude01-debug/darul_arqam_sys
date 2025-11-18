<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolClass extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'section',
        'class_code',
        'class_teacher_id',
        'subject_teachers',
        'capacity',
        'current_enrollment',
        'room_number',
        'academic_year',
        'status',
        'description',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'subject_teachers' => 'array',
        'capacity' => 'integer',
        'current_enrollment' => 'integer',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Get the full class name with section
     */
    public function getFullNameAttribute(): string
    {
        return $this->section
            ? "{$this->name} - {$this->section}"
            : $this->name;
    }

    /**
     * Get the enrollment percentage
     */
    public function getEnrollmentPercentageAttribute(): float
    {
        if ($this->capacity == 0) {
            return 0;
        }
        return round(($this->current_enrollment / $this->capacity) * 100, 1);
    }

    /**
     * Get available seats
     */
    public function getAvailableSeatsAttribute(): int
    {
        return max(0, $this->capacity - $this->current_enrollment);
    }

    /**
     * Check if class is full
     */
    public function getIsFullAttribute(): bool
    {
        return $this->current_enrollment >= $this->capacity;
    }

    /**
     * Relationship: Class Teacher
     */
    public function classTeacher()
    {
        return $this->belongsTo(Teacher::class, 'class_teacher_id');
    }

    /**
     * Relationship: Students
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'class_level', 'name')
            ->where('section', $this->section);
    }

    /**
     * Scope: Active classes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Search
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('section', 'like', "%{$search}%")
                ->orWhere('class_code', 'like', "%{$search}%")
                ->orWhere('room_number', 'like', "%{$search}%");
        });
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter by academic year
     */
    public function scopeByAcademicYear($query, string $year)
    {
        return $query->where('academic_year', $year);
    }

    /**
     * Scope: Filter by teacher
     */
    public function scopeByTeacher($query, int $teacherId)
    {
        return $query->where('class_teacher_id', $teacherId);
    }
}

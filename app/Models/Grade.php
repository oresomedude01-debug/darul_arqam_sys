<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'school_class_id',
        'exam_type_id',
        'term',
        'session',
        'score',
        'grade',
        'remark',
        'recorded_by',
    ];

    protected $casts = [
        'score' => 'decimal:2',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function examType()
    {
        return $this->belongsTo(ExamType::class);
    }

    public function recorder()
    {
        return $this->belongsTo(Teacher::class, 'recorded_by');
    }

    // Scopes
    public function scopeForTerm($query, $term, $session)
    {
        return $query->where('term', $term)->where('session', $session);
    }

    public function scopeForClass($query, $classId)
    {
        return $query->where('school_class_id', $classId);
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForSubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeForExamType($query, $examTypeId)
    {
        return $query->where('exam_type_id', $examTypeId);
    }

    // Helpers
    public static function calculateGrade($score)
    {
        $gradeScale = GradeScale::where('min_score', '<=', $score)
            ->where('max_score', '>=', $score)
            ->orderBy('min_score', 'desc')
            ->first();

        return $gradeScale ? $gradeScale->grade : 'F';
    }

    public static function getGradeRemark($score)
    {
        $gradeScale = GradeScale::where('min_score', '<=', $score)
            ->where('max_score', '>=', $score)
            ->orderBy('min_score', 'desc')
            ->first();

        return $gradeScale ? $gradeScale->remark : 'Fail';
    }

    // Auto-calculate grade when score is set
    protected static function booted()
    {
        static::saving(function ($grade) {
            if ($grade->score !== null) {
                $grade->grade = self::calculateGrade($grade->score);
            }
        });
    }
}

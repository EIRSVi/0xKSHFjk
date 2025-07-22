<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'code',
        'description',
        'subject_id',
        'teacher_id',
        'grade_level',
        'academic_year',
        'semester',
        'max_students',
        'status',
        'schedule',
        'room',
        'created_by',
    ];

    protected $casts = [
        'schedule' => 'array',
        'max_students' => 'integer',
    ];

    /**
     * Get the subject this class belongs to
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the teacher assigned to this class
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the user who created this class
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all students enrolled in this class
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'class_enrollments', 'class_id', 'student_id')
                    ->withPivot(['enrolled_date', 'status', 'final_grade', 'notes'])
                    ->withTimestamps();
    }

    /**
     * Get all enrollments for this class
     */
    public function enrollments()
    {
        return $this->hasMany(ClassEnrollment::class, 'class_id');
    }

    /**
     * Get all quizzes for this class
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'class_id');
    }

    /**
     * Scope for active classes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get enrolled students count
     */
    public function getEnrolledStudentsCountAttribute()
    {
        return $this->students()->wherePivot('status', 'active')->count();
    }

    /**
     * Check if class is full
     */
    public function isFull()
    {
        return $this->enrolled_students_count >= $this->max_students;
    }
}

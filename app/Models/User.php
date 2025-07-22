<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'first_name',
        'last_name',
        'role',
        'status',
        'profile_photo',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'student_id',
        'employee_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
        ];
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is teacher
     */
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    /**
     * Check if user is student
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Classes where this user is a teacher
     */
    public function teachingClasses()
    {
        return $this->hasMany(ClassModel::class, 'teacher_id');
    }

    /**
     * Classes where this user is enrolled as a student
     */
    public function enrolledClasses()
    {
        return $this->belongsToMany(ClassModel::class, 'class_enrollments', 'student_id', 'class_id')
                    ->withPivot(['enrolled_date', 'status', 'final_grade', 'notes'])
                    ->withTimestamps();
    }

    /**
     * Quizzes created by this user (teachers/admins)
     */
    public function createdQuizzes()
    {
        return $this->hasMany(Quiz::class, 'created_by');
    }

    /**
     * Quiz attempts by this user (students)
     */
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class, 'student_id');
    }

    /**
     * Subjects created by this user (admins)
     */
    public function createdSubjects()
    {
        return $this->hasMany(Subject::class, 'created_by');
    }

    /**
     * Classes created by this user (admins)
     */
    public function createdClasses()
    {
        return $this->hasMany(ClassModel::class, 'created_by');
    }
}

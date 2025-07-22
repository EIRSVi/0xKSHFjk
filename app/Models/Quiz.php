<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'class_id',
        'subject_id',
        'created_by',
        'access_code',
        'time_limit',
        'total_marks',
        'total_questions',
        'allow_retakes',
        'max_attempts',
        'randomize_questions',
        'show_results_immediately',
        'show_correct_answers',
        'available_from',
        'available_until',
        'status',
        'settings',
        'instructions',
        'pass_percentage',
    ];

    protected $casts = [
        'time_limit' => 'integer',
        'total_marks' => 'decimal:2',
        'total_questions' => 'integer',
        'allow_retakes' => 'boolean',
        'max_attempts' => 'integer',
        'randomize_questions' => 'boolean',
        'show_results_immediately' => 'boolean',
        'show_correct_answers' => 'boolean',
        'available_from' => 'datetime',
        'available_until' => 'datetime',
        'settings' => 'array',
        'pass_percentage' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($quiz) {
            if (!$quiz->access_code) {
                $quiz->access_code = self::generateAccessCode();
            }
        });
    }

    /**
     * Generate a unique 6-digit access code
     */
    public static function generateAccessCode()
    {
        do {
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('access_code', $code)->exists());
        
        return $code;
    }

    /**
     * Get the class this quiz belongs to
     */
    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    /**
     * Get the subject this quiz belongs to
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the user who created this quiz
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all questions for this quiz
     */
    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order_index');
    }

    /**
     * Get all attempts for this quiz
     */
    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Scope for published quizzes
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for available quizzes
     */
    public function scopeAvailable($query)
    {
        $now = now();
        return $query->where('status', 'published')
                    ->where(function ($q) use ($now) {
                        $q->whereNull('available_from')
                          ->orWhere('available_from', '<=', $now);
                    })
                    ->where(function ($q) use ($now) {
                        $q->whereNull('available_until')
                          ->orWhere('available_until', '>=', $now);
                    });
    }

    /**
     * Check if quiz is available for taking
     */
    public function isAvailable()
    {
        $now = now();
        return $this->status === 'published' &&
               ($this->available_from === null || $this->available_from <= $now) &&
               ($this->available_until === null || $this->available_until >= $now);
    }
}

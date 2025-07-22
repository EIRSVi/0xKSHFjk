<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes');
            $table->foreignId('student_id')->constrained('users');
            $table->integer('attempt_number')->default(1);
            $table->datetime('started_at');
            $table->datetime('completed_at')->nullable();
            $table->datetime('submitted_at')->nullable();
            $table->integer('time_taken')->nullable(); // Minutes
            $table->decimal('score', 8, 2)->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->decimal('total_possible', 8, 2)->default(0);
            $table->enum('status', ['in_progress', 'completed', 'submitted', 'timed_out', 'abandoned'])->default('in_progress');
            $table->json('answers_snapshot')->nullable(); // Snapshot of all answers
            $table->text('feedback')->nullable(); // Teacher feedback
            $table->boolean('is_passed')->default(false);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable(); // Additional attempt data
            $table->timestamps();
            
            // Indexes
            $table->index(['quiz_id', 'student_id']);
            $table->index(['student_id', 'status']);
            $table->index(['quiz_id', 'status']);
            $table->unique(['quiz_id', 'student_id', 'attempt_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};

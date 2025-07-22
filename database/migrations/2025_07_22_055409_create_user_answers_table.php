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
        Schema::create('user_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_attempt_id')->constrained('quiz_attempts')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->json('user_answer'); // Stores the user's answer(s)
            $table->boolean('is_correct')->default(false);
            $table->decimal('points_earned', 8, 2)->default(0);
            $table->decimal('points_possible', 8, 2)->default(0);
            $table->datetime('answered_at')->nullable();
            $table->integer('time_spent')->nullable(); // Seconds spent on this question
            $table->boolean('is_flagged')->default(false); // Student can flag for review
            $table->json('metadata')->nullable(); // Additional answer data
            $table->timestamps();
            
            // Indexes
            $table->index(['quiz_attempt_id', 'question_id']);
            $table->index(['question_id', 'is_correct']);
            $table->unique(['quiz_attempt_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_answers');
    }
};

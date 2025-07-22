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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->text('question_text');
            $table->enum('question_type', ['multiple_choice', 'true_false', 'short_answer', 'essay']);
            $table->json('options')->nullable(); // For multiple choice questions
            $table->json('correct_answers'); // Can be multiple for checkbox type
            $table->decimal('points', 8, 2)->default(1.00);
            $table->text('explanation')->nullable(); // Explanation for correct answer
            $table->string('image_url')->nullable(); // Optional question image
            $table->integer('order_index')->default(0); // Question order in quiz
            $table->boolean('is_required')->default(true);
            $table->json('settings')->nullable(); // Additional question settings
            $table->timestamps();
            
            // Indexes
            $table->index(['quiz_id', 'order_index']);
            $table->index(['quiz_id', 'question_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};

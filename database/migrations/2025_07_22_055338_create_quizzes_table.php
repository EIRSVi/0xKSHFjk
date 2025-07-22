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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('class_id')->constrained('classes');
            $table->foreignId('subject_id')->constrained('subjects');
            $table->foreignId('created_by')->constrained('users'); // Teacher who created
            $table->string('access_code', 6)->unique(); // 6-digit access code
            $table->integer('time_limit')->default(60); // Minutes
            $table->decimal('total_marks', 8, 2)->default(0);
            $table->integer('total_questions')->default(0);
            $table->boolean('allow_retakes')->default(false);
            $table->integer('max_attempts')->default(1);
            $table->boolean('randomize_questions')->default(false);
            $table->boolean('show_results_immediately')->default(true);
            $table->boolean('show_correct_answers')->default(true);
            $table->datetime('available_from')->nullable();
            $table->datetime('available_until')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->json('settings')->nullable(); // Additional quiz settings as JSON
            $table->text('instructions')->nullable();
            $table->decimal('pass_percentage', 5, 2)->default(60.00);
            $table->timestamps();
            
            // Indexes
            $table->index(['class_id', 'status']);
            $table->index(['subject_id', 'status']);
            $table->index('access_code');
            $table->index(['available_from', 'available_until']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};

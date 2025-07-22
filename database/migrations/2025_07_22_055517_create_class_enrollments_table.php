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
        Schema::create('class_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('class_id')->constrained('classes');
            $table->date('enrolled_date');
            $table->enum('status', ['active', 'dropped', 'completed'])->default('active');
            $table->decimal('final_grade', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('enrolled_by')->constrained('users'); // Admin or teacher who enrolled
            $table->timestamps();
            
            // Unique constraint to prevent duplicate enrollments
            $table->unique(['student_id', 'class_id']);
            
            // Indexes
            $table->index(['class_id', 'status']);
            $table->index(['student_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_enrollments');
    }
};

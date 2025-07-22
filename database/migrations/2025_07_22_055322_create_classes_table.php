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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->foreignId('subject_id')->constrained('subjects');
            $table->foreignId('teacher_id')->constrained('users'); // Teacher assigned to this class
            $table->string('grade_level')->nullable(); // e.g., "Grade 10", "Year 12"
            $table->string('academic_year', 9); // e.g., "2024-2025"
            $table->enum('semester', ['1', '2', 'full_year'])->default('full_year');
            $table->integer('max_students')->default(50);
            $table->enum('status', ['active', 'inactive', 'archived'])->default('active');
            $table->json('schedule')->nullable(); // Class schedule as JSON
            $table->string('room')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            
            // Indexes
            $table->index(['subject_id', 'teacher_id']);
            $table->index(['status', 'academic_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};

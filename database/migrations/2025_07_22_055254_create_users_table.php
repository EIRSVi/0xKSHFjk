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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('role', ['admin', 'teacher', 'student'])->default('student');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->string('profile_photo')->nullable();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->string('student_id')->nullable()->unique(); // For students
            $table->string('employee_id')->nullable()->unique(); // For teachers/admins
            $table->rememberToken();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['role', 'status']);
            $table->index('username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

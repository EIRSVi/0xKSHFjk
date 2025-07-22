<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin user
        \App\Models\User::create([
            'username' => 'admin',
            'email' => 'admin@oqs.com',
            'password' => bcrypt('admin123'),
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Create sample teacher
        \App\Models\User::create([
            'username' => 'teacher1',
            'email' => 'teacher@oqs.com',
            'password' => bcrypt('teacher123'),
            'first_name' => 'John',
            'last_name' => 'Smith',
            'role' => 'teacher',
            'status' => 'active',
            'employee_id' => 'EMP001',
        ]);

        // Create sample students
        \App\Models\User::create([
            'username' => 'student1',
            'email' => 'student1@oqs.com',
            'password' => bcrypt('student123'),
            'first_name' => 'Alice',
            'last_name' => 'Johnson',
            'role' => 'student',
            'status' => 'active',
            'student_id' => 'STU001',
        ]);

        \App\Models\User::create([
            'username' => 'student2',
            'email' => 'student2@oqs.com',
            'password' => bcrypt('student123'),
            'first_name' => 'Bob',
            'last_name' => 'Wilson',
            'role' => 'student',
            'status' => 'active',
            'student_id' => 'STU002',
        ]);

        // Create sample subjects
        $adminUser = \App\Models\User::where('role', 'admin')->first();
        
        \App\Models\Subject::create([
            'name' => 'Mathematics',
            'code' => 'MATH',
            'description' => 'Mathematics and Algebra',
            'color' => '#4285F4',
            'icon' => 'calculate',
            'created_by' => $adminUser->id,
        ]);

        \App\Models\Subject::create([
            'name' => 'Science',
            'code' => 'SCI',
            'description' => 'General Science',
            'color' => '#34A853',
            'icon' => 'science',
            'created_by' => $adminUser->id,
        ]);

        \App\Models\Subject::create([
            'name' => 'English',
            'code' => 'ENG',
            'description' => 'English Language and Literature',
            'color' => '#EA4335',
            'icon' => 'menu_book',
            'created_by' => $adminUser->id,
        ]);
    }
}

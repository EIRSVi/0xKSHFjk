<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SubjectController as AdminSubjectController;
use App\Http\Controllers\Admin\ClassController as AdminClassController;
use App\Http\Controllers\Admin\ReportsController as AdminReportsController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\QuizController as TeacherQuizController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registration (for demo purposes)
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // User Management
    Route::resource('users', AdminUserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);
    
    // Subject Management
    Route::resource('subjects', AdminSubjectController::class)->names([
        'index' => 'admin.subjects.index',
        'create' => 'admin.subjects.create',
        'store' => 'admin.subjects.store',
        'show' => 'admin.subjects.show',
        'edit' => 'admin.subjects.edit',
        'update' => 'admin.subjects.update',
        'destroy' => 'admin.subjects.destroy',
    ]);
    
    // Class Management
    Route::resource('classes', AdminClassController::class)->names([
        'index' => 'admin.classes.index',
        'create' => 'admin.classes.create',
        'store' => 'admin.classes.store',
        'show' => 'admin.classes.show',
        'edit' => 'admin.classes.edit',
        'update' => 'admin.classes.update',
        'destroy' => 'admin.classes.destroy',
    ]);
    
    // Class Enrollment Management
    Route::get('classes/{class}/enrollments', [AdminClassController::class, 'enrollments'])->name('admin.classes.enrollments');
    Route::post('classes/{class}/enroll', [AdminClassController::class, 'enrollStudent'])->name('admin.classes.enroll');
    Route::delete('classes/{class}/unenroll/{student}', [AdminClassController::class, 'unenrollStudent'])->name('admin.classes.unenroll');
    
    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/', [AdminReportsController::class, 'index'])->name('admin.reports.index');
        Route::get('/users', [AdminReportsController::class, 'users'])->name('admin.reports.users');
        Route::get('/subjects', [AdminReportsController::class, 'subjects'])->name('admin.reports.subjects');
        Route::get('/classes', [AdminReportsController::class, 'classes'])->name('admin.reports.classes');
        Route::get('/quizzes', [AdminReportsController::class, 'quizzes'])->name('admin.reports.quizzes');
        Route::get('/performance', [AdminReportsController::class, 'performance'])->name('admin.reports.performance');
        Route::post('/export', [AdminReportsController::class, 'export'])->name('admin.reports.export');
    });
});

// Teacher Routes
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('teacher.dashboard');
    
    // Quiz Management
    Route::resource('quizzes', TeacherQuizController::class)->names([
        'index' => 'teacher.quizzes.index',
        'create' => 'teacher.quizzes.create',
        'store' => 'teacher.quizzes.store',
        'show' => 'teacher.quizzes.show',
        'edit' => 'teacher.quizzes.edit',
        'update' => 'teacher.quizzes.update',
        'destroy' => 'teacher.quizzes.destroy',
    ]);
    
    // Question Management
    Route::get('quizzes/{quiz}/questions', [TeacherQuizController::class, 'questions'])->name('teacher.quizzes.questions');
    Route::get('quizzes/{quiz}/questions/create', [TeacherQuizController::class, 'createQuestion'])->name('teacher.quizzes.questions.create');
    Route::post('quizzes/{quiz}/questions', [TeacherQuizController::class, 'storeQuestion'])->name('teacher.quizzes.questions.store');
});

// Student Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
});

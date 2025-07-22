<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subject;
use App\Models\ClassModel;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics for dashboard
        $stats = [
            'total_users' => User::count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_subjects' => Subject::count(),
            'active_subjects' => Subject::where('status', 'active')->count(),
            'total_classes' => ClassModel::count(),
            'active_classes' => ClassModel::where('status', 'active')->count(),
            'total_quizzes' => Quiz::count(),
            'published_quizzes' => Quiz::where('status', 'published')->count(),
            'total_attempts' => QuizAttempt::count(),
            'completed_attempts' => QuizAttempt::where('status', 'completed')->count(),
        ];

        // Recent activities
        $recent_users = User::latest()->take(5)->get();
        $recent_quizzes = Quiz::with(['subject', 'creator'])->latest()->take(5)->get();
        $recent_attempts = QuizAttempt::with(['quiz', 'student'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_quizzes', 'recent_attempts'));
    }
}

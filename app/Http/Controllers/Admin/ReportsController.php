<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subject;
use App\Models\ClassModel;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function users()
    {
        $totalUsers = User::count();
        $usersByRole = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->get()
            ->keyBy('role');

        $usersByStatus = User::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $monthlyRegistrations = User::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $recentUsers = User::latest()->take(10)->get();

        return view('admin.reports.users', compact(
            'totalUsers', 'usersByRole', 'usersByStatus', 
            'monthlyRegistrations', 'recentUsers'
        ));
    }

    public function subjects()
    {
        $totalSubjects = Subject::count();
        $subjectsByStatus = Subject::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $subjectsWithClasses = Subject::withCount('classes')
            ->orderBy('classes_count', 'desc')
            ->get();

        $subjectsWithQuizzes = Subject::withCount('quizzes')
            ->orderBy('quizzes_count', 'desc')
            ->get();

        return view('admin.reports.subjects', compact(
            'totalSubjects', 'subjectsByStatus', 
            'subjectsWithClasses', 'subjectsWithQuizzes'
        ));
    }

    public function classes()
    {
        $totalClasses = ClassModel::count();
        $classesByStatus = ClassModel::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $classEnrollments = ClassModel::with(['subject', 'teacher'])
            ->withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->get();

        $teacherClassCounts = User::where('role', 'teacher')
            ->withCount('teachingClasses')
            ->orderBy('teaching_classes_count', 'desc')
            ->get();

        return view('admin.reports.classes', compact(
            'totalClasses', 'classesByStatus', 
            'classEnrollments', 'teacherClassCounts'
        ));
    }

    public function quizzes()
    {
        $totalQuizzes = Quiz::count();
        $quizzesByStatus = Quiz::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $quizAttempts = QuizAttempt::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $popularQuizzes = Quiz::withCount('attempts')
            ->orderBy('attempts_count', 'desc')
            ->take(10)
            ->get();

        $averageScores = QuizAttempt::select('quiz_id', DB::raw('AVG(score) as avg_score'))
            ->where('status', 'completed')
            ->groupBy('quiz_id')
            ->with('quiz')
            ->orderBy('avg_score', 'desc')
            ->take(10)
            ->get();

        $monthlyAttempts = QuizAttempt::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.reports.quizzes', compact(
            'totalQuizzes', 'quizzesByStatus', 'quizAttempts',
            'popularQuizzes', 'averageScores', 'monthlyAttempts'
        ));
    }

    public function performance()
    {
        // Student performance metrics
        $topStudents = User::where('role', 'student')
            ->withCount(['quizAttempts as completed_attempts' => function($query) {
                $query->where('status', 'completed');
            }])
            ->withAvg(['quizAttempts as avg_score' => function($query) {
                $query->where('status', 'completed');
            }], 'score')
            ->having('completed_attempts', '>', 0)
            ->orderBy('avg_score', 'desc')
            ->take(10)
            ->get();

        // Teacher performance metrics
        $teacherStats = User::where('role', 'teacher')
            ->withCount([
                'teachingClasses',
                'createdQuizzes',
                'createdQuizzes as total_attempts' => function($query) {
                    $query->withCount('attempts');
                }
            ])
            ->get();

        // Subject performance
        $subjectPerformance = Subject::withAvg(['quizzes.attempts as avg_score' => function($query) {
                $query->where('status', 'completed');
            }], 'score')
            ->withCount(['quizzes.attempts as total_attempts' => function($query) {
                $query->where('status', 'completed');
            }])
            ->having('total_attempts', '>', 0)
            ->orderBy('avg_score', 'desc')
            ->get();

        return view('admin.reports.performance', compact(
            'topStudents', 'teacherStats', 'subjectPerformance'
        ));
    }

    public function export(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:users,subjects,classes,quizzes,performance',
            'format' => 'required|in:csv,pdf',
        ]);

        // This would typically generate and return a file
        // For now, we'll just redirect back with a success message
        return redirect()->back()
            ->with('success', 'Report export initiated. You will receive an email when ready.');
    }
}

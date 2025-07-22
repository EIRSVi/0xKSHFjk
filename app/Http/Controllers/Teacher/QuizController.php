<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with(['subject', 'class'])
            ->where('created_by', auth()->id())
            ->latest()
            ->paginate(15);
        
        return view('teacher.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $classes = ClassModel::with('subject')
            ->where('teacher_id', auth()->id())
            ->where('status', 'active')
            ->get();
        
        return view('teacher.quizzes.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_id' => 'required|exists:classes,id',
            'instructions' => 'nullable|string',
            'time_limit' => 'nullable|integer|min:1',
            'max_attempts' => 'nullable|integer|min:1',
            'pass_percentage' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:draft,published,closed',
            'available_from' => 'nullable|date',
            'available_until' => 'nullable|date|after:available_from',
            'show_results' => 'required|boolean',
            'shuffle_questions' => 'required|boolean',
        ]);

        // Verify teacher owns the class
        $class = ClassModel::findOrFail($request->class_id);
        if ($class->teacher_id !== auth()->id()) {
            abort(403, 'You can only create quizzes for your own classes.');
        }

        // Generate unique access code
        do {
            $accessCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (Quiz::where('access_code', $accessCode)->exists());

        $quiz = Quiz::create([
            'title' => $request->title,
            'description' => $request->description,
            'class_id' => $request->class_id,
            'subject_id' => $class->subject_id,
            'created_by' => auth()->id(),
            'instructions' => $request->instructions,
            'time_limit' => $request->time_limit,
            'max_attempts' => $request->max_attempts,
            'pass_percentage' => $request->pass_percentage,
            'status' => $request->status,
            'available_from' => $request->available_from,
            'available_until' => $request->available_until,
            'show_results' => $request->boolean('show_results'),
            'shuffle_questions' => $request->boolean('shuffle_questions'),
            'access_code' => $accessCode,
        ]);

        return redirect()->route('teacher.quizzes.show', $quiz)
            ->with('success', 'Quiz created successfully. Start adding questions!');
    }

    public function show(Quiz $quiz)
    {
        // Verify teacher owns this quiz
        if ($quiz->created_by !== auth()->id()) {
            abort(403, 'You can only view your own quizzes.');
        }

        $quiz->load(['subject', 'class', 'questions', 'attempts.student']);
        
        return view('teacher.quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        // Verify teacher owns this quiz
        if ($quiz->created_by !== auth()->id()) {
            abort(403, 'You can only edit your own quizzes.');
        }

        $classes = ClassModel::with('subject')
            ->where('teacher_id', auth()->id())
            ->where('status', 'active')
            ->get();
        
        return view('teacher.quizzes.edit', compact('quiz', 'classes'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        // Verify teacher owns this quiz
        if ($quiz->created_by !== auth()->id()) {
            abort(403, 'You can only edit your own quizzes.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_id' => 'required|exists:classes,id',
            'instructions' => 'nullable|string',
            'time_limit' => 'nullable|integer|min:1',
            'max_attempts' => 'nullable|integer|min:1',
            'pass_percentage' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:draft,published,closed',
            'available_from' => 'nullable|date',
            'available_until' => 'nullable|date|after:available_from',
            'show_results' => 'required|boolean',
            'shuffle_questions' => 'required|boolean',
        ]);

        // Verify teacher owns the class
        $class = ClassModel::findOrFail($request->class_id);
        if ($class->teacher_id !== auth()->id()) {
            abort(403, 'You can only assign quizzes to your own classes.');
        }

        $quiz->update([
            'title' => $request->title,
            'description' => $request->description,
            'class_id' => $request->class_id,
            'subject_id' => $class->subject_id,
            'instructions' => $request->instructions,
            'time_limit' => $request->time_limit,
            'max_attempts' => $request->max_attempts,
            'pass_percentage' => $request->pass_percentage,
            'status' => $request->status,
            'available_from' => $request->available_from,
            'available_until' => $request->available_until,
            'show_results' => $request->boolean('show_results'),
            'shuffle_questions' => $request->boolean('shuffle_questions'),
        ]);

        return redirect()->route('teacher.quizzes.show', $quiz)
            ->with('success', 'Quiz updated successfully.');
    }

    public function destroy(Quiz $quiz)
    {
        // Verify teacher owns this quiz
        if ($quiz->created_by !== auth()->id()) {
            abort(403, 'You can only delete your own quizzes.');
        }

        // Check if quiz has attempts
        if ($quiz->attempts()->count() > 0) {
            return redirect()->route('teacher.quizzes.index')
                ->with('error', 'Cannot delete quiz that has student attempts.');
        }

        $quiz->delete();

        return redirect()->route('teacher.quizzes.index')
            ->with('success', 'Quiz deleted successfully.');
    }

    // Question management methods
    public function questions(Quiz $quiz)
    {
        // Verify teacher owns this quiz
        if ($quiz->created_by !== auth()->id()) {
            abort(403, 'You can only manage questions for your own quizzes.');
        }

        $quiz->load('questions');
        
        return view('teacher.quizzes.questions', compact('quiz'));
    }

    public function createQuestion(Quiz $quiz)
    {
        // Verify teacher owns this quiz
        if ($quiz->created_by !== auth()->id()) {
            abort(403, 'You can only add questions to your own quizzes.');
        }
        
        return view('teacher.quizzes.create-question', compact('quiz'));
    }

    public function storeQuestion(Request $request, Quiz $quiz)
    {
        // Verify teacher owns this quiz
        if ($quiz->created_by !== auth()->id()) {
            abort(403, 'You can only add questions to your own quizzes.');
        }

        $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'points' => 'required|numeric|min:0',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*' => 'required_if:question_type,multiple_choice|string',
            'correct_answer' => 'required',
            'explanation' => 'nullable|string',
        ]);

        $questionData = [
            'quiz_id' => $quiz->id,
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'points' => $request->points,
            'explanation' => $request->explanation,
            'order' => $quiz->questions()->max('order') + 1,
        ];

        if ($request->question_type === 'multiple_choice') {
            $questionData['options'] = json_encode($request->options);
            $questionData['correct_answer'] = $request->correct_answer; // Index of correct option
        } else {
            $questionData['correct_answer'] = $request->correct_answer;
        }

        Question::create($questionData);

        return redirect()->route('teacher.quizzes.questions', $quiz)
            ->with('success', 'Question added successfully.');
    }
}

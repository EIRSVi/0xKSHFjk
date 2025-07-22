<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassModel::with(['subject', 'teacher'])->latest()->paginate(15);
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        $subjects = Subject::where('status', 'active')->orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->where('status', 'active')->orderBy('name')->get();
        return view('admin.classes.create', compact('subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:classes',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'max_students' => 'nullable|integer|min:1|max:1000',
            'status' => 'required|in:active,inactive',
        ]);

        // Verify teacher role
        $teacher = User::findOrFail($request->teacher_id);
        if ($teacher->role !== 'teacher') {
            return back()->withErrors(['teacher_id' => 'Selected user must be a teacher.']);
        }

        ClassModel::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'max_students' => $request->max_students,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function show(ClassModel $class)
    {
        $class->load(['subject', 'teacher', 'enrollments.student', 'quizzes']);
        return view('admin.classes.show', compact('class'));
    }

    public function edit(ClassModel $class)
    {
        $subjects = Subject::where('status', 'active')->orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->where('status', 'active')->orderBy('name')->get();
        return view('admin.classes.edit', compact('class', 'subjects', 'teachers'));
    }

    public function update(Request $request, ClassModel $class)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:classes,code,' . $class->id,
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'max_students' => 'nullable|integer|min:1|max:1000',
            'status' => 'required|in:active,inactive',
        ]);

        // Verify teacher role
        $teacher = User::findOrFail($request->teacher_id);
        if ($teacher->role !== 'teacher') {
            return back()->withErrors(['teacher_id' => 'Selected user must be a teacher.']);
        }

        $class->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'max_students' => $request->max_students,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(ClassModel $class)
    {
        // Check if class has enrolled students or quizzes
        if ($class->enrollments()->count() > 0 || $class->quizzes()->count() > 0) {
            return redirect()->route('admin.classes.index')
                ->with('error', 'Cannot delete class that has enrolled students or quizzes.');
        }

        $class->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class deleted successfully.');
    }

    public function enrollments(ClassModel $class)
    {
        $class->load(['enrollments.student']);
        $availableStudents = User::where('role', 'student')
            ->where('status', 'active')
            ->whereNotIn('id', $class->enrollments()->pluck('user_id'))
            ->orderBy('name')
            ->get();

        return view('admin.classes.enrollments', compact('class', 'availableStudents'));
    }

    public function enrollStudent(Request $request, ClassModel $class)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $student = User::findOrFail($request->student_id);
        if ($student->role !== 'student') {
            return back()->withErrors(['student_id' => 'Selected user must be a student.']);
        }

        // Check if student is already enrolled
        if ($class->enrollments()->where('user_id', $student->id)->exists()) {
            return back()->withErrors(['student_id' => 'Student is already enrolled in this class.']);
        }

        // Check class capacity
        if ($class->max_students && $class->enrollments()->count() >= $class->max_students) {
            return back()->withErrors(['student_id' => 'Class has reached maximum capacity.']);
        }

        $class->enrollments()->create([
            'user_id' => $student->id,
            'enrolled_at' => now(),
        ]);

        return redirect()->route('admin.classes.enrollments', $class)
            ->with('success', 'Student enrolled successfully.');
    }

    public function unenrollStudent(ClassModel $class, $studentId)
    {
        $enrollment = $class->enrollments()->where('user_id', $studentId)->first();
        
        if (!$enrollment) {
            return back()->withErrors(['error' => 'Student is not enrolled in this class.']);
        }

        $enrollment->delete();

        return redirect()->route('admin.classes.enrollments', $class)
            ->with('success', 'Student unenrolled successfully.');
    }
}

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
        $teachers = User::where('role', 'teacher')->where('status', 'active')->orderBy('first_name')->get();
        return view('admin.classes.create', compact('subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'class_code' => 'nullable|string|max:20|unique:classes,code',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'max_students' => 'nullable|integer|min:1|max:1000',
            'status' => 'required|in:active,inactive,archived',
            'schedule' => 'nullable|string',
        ]);

        // Verify teacher role
        $teacher = User::findOrFail($request->teacher_id);
        if ($teacher->role !== 'teacher') {
            return back()->withErrors(['teacher_id' => 'Selected user must be a teacher.']);
        }

        // Generate class code if not provided
        $classCode = $request->class_code ?: strtoupper(substr($request->name, 0, 3) . rand(100, 999));

        ClassModel::create([
            'name' => $request->name,
            'code' => $classCode,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'grade_level' => null,
            'academic_year' => date('Y') . '-' . (date('Y') + 1),
            'semester' => 'full_year',
            'max_students' => $request->max_students ?? 50,
            'status' => $request->status,
            'schedule' => $request->schedule,
            'room' => null,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function show(ClassModel $class)
    {
        $class->load(['subject', 'teacher', 'enrollments.user', 'quizzes']);
        $availableStudents = User::where('role', 'student')
            ->where('status', 'active')
            ->whereNotIn('id', $class->enrollments()->pluck('user_id'))
            ->orderBy('first_name')
            ->get();
        return view('admin.classes.show', compact('class', 'availableStudents'));
    }

    public function edit(ClassModel $class)
    {
        $subjects = Subject::where('status', 'active')->orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->where('status', 'active')->orderBy('first_name')->get();
        return view('admin.classes.edit', compact('class', 'subjects', 'teachers'));
    }

    public function update(Request $request, ClassModel $class)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'class_code' => 'nullable|string|max:20|unique:classes,code,' . $class->id,
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'max_students' => 'nullable|integer|min:1|max:1000',
            'status' => 'required|in:active,inactive,archived',
            'schedule' => 'nullable|string',
        ]);

        // Verify teacher role
        $teacher = User::findOrFail($request->teacher_id);
        if ($teacher->role !== 'teacher') {
            return back()->withErrors(['teacher_id' => 'Selected user must be a teacher.']);
        }

        // Generate class code if not provided
        $classCode = $request->class_code ?: $class->code;

        $class->update([
            'name' => $request->name,
            'code' => $classCode,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'max_students' => $request->max_students ?? 50,
            'status' => $request->status,
            'schedule' => $request->schedule,
        ]);

        return redirect()->route('admin.classes.show', $class)
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
        $class->load(['enrollments.user']);
        $availableStudents = User::where('role', 'student')
            ->where('status', 'active')
            ->whereNotIn('id', $class->enrollments()->pluck('user_id'))
            ->orderBy('first_name')
            ->get();

        return view('admin.classes.enrollments', compact('class', 'availableStudents'));
    }

    public function enroll(Request $request, ClassModel $class)
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
        ]);

        return back()->with('success', 'Student enrolled successfully.');
    }

    public function unenroll(ClassModel $class, User $user)
    {
        $enrollment = $class->enrollments()->where('user_id', $user->id)->first();
        
        if (!$enrollment) {
            return back()->withErrors(['error' => 'Student is not enrolled in this class.']);
        }

        $enrollment->delete();

        return back()->with('success', 'Student unenrolled successfully.');
    }
}

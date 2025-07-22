<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('creator')->latest()->paginate(15);
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.subjects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:subjects',
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'icon' => 'required|string|max:50',
            'status' => 'required|in:active,inactive',
        ]);

        Subject::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'color' => $request->color,
            'icon' => $request->icon,
            'status' => $request->status,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    public function show(Subject $subject)
    {
        $subject->load(['creator', 'classes', 'quizzes']);
        return view('admin.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:subjects,code,' . $subject->id,
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'icon' => 'required|string|max:50',
            'status' => 'required|in:active,inactive',
        ]);

        $subject->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'color' => $request->color,
            'icon' => $request->icon,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        // Check if subject has associated classes or quizzes
        if ($subject->classes()->count() > 0 || $subject->quizzes()->count() > 0) {
            return redirect()->route('admin.subjects.index')
                ->with('error', 'Cannot delete subject that has associated classes or quizzes.');
        }

        $subject->delete();

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
}

@extends('layouts.dashboard')

@section('title', 'Edit Class - Admin')
@section('page-title', 'Edit Class')

@section('sidebar-content')
<a href="{{ route('admin.dashboard') }}" class="nav-link">
    <span class="material-icon text-lg mr-3">dashboard</span>
    Dashboard
</a>
<a href="{{ route('admin.users.index') }}" class="nav-link">
    <span class="material-icon text-lg mr-3">people</span>
    Users
</a>
<a href="{{ route('admin.subjects.index') }}" class="nav-link">
    <span class="material-icon text-lg mr-3">book</span>
    Subjects
</a>
<a href="{{ route('admin.classes.index') }}" class="nav-link active">
    <span class="material-icon text-lg mr-3">class</span>
    Classes
</a>
<a href="#" class="nav-link">
    <span class="material-icon text-lg mr-3">quiz</span>
    Quizzes
</a>
<a href="{{ route('admin.reports.index') }}" class="nav-link">
    <span class="material-icon text-lg mr-3">analytics</span>
    Reports
</a>
<a href="#" class="nav-link">
    <span class="material-icon text-lg mr-3">settings</span>
    Settings
</a>
@endsection

@section('main-content')
<!-- Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Class</h1>
        <p class="text-sm text-gray-600 mt-1">Update class information</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.classes.index') }}" class="btn-secondary">
            <span class="material-icon text-sm mr-2">arrow_back</span>
            Back to Classes
        </a>
        <a href="{{ route('admin.classes.show', $class) }}" class="btn-secondary">
            <span class="material-icon text-sm mr-2">visibility</span>
            View Details
        </a>
    </div>
</div>

<!-- Success Message -->
@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

<!-- Edit Class Form -->
<div class="card">
    <div class="card-header">
        <h3 class="text-lg font-semibold text-gray-900">Class Information</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.classes.update', $class) }}">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Class Name -->
                <div>
                    <label for="name" class="form-label required">Class Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $class->name) }}" 
                           class="form-input @error('name') border-red-300 @enderror" 
                           placeholder="Enter class name..." required>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" rows="3" 
                              class="form-input @error('description') border-red-300 @enderror"
                              placeholder="Enter class description...">{{ old('description', $class->description) }}</textarea>
                    @error('description')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subject Selection -->
                <div>
                    <label for="subject_id" class="form-label required">Subject</label>
                    <select id="subject_id" name="subject_id" class="form-input @error('subject_id') border-red-300 @enderror" required>
                        <option value="">Select a subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', $class->subject_id) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teacher Assignment -->
                <div>
                    <label for="teacher_id" class="form-label required">Assign Teacher</label>
                    <select id="teacher_id" name="teacher_id" class="form-input @error('teacher_id') border-red-300 @enderror" required>
                        <option value="">Select a teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $class->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->first_name }} {{ $teacher->last_name }} ({{ $teacher->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Class Settings -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Max Students -->
                    <div>
                        <label for="max_students" class="form-label">Maximum Students</label>
                        <input type="number" id="max_students" name="max_students" value="{{ old('max_students', $class->max_students) }}" 
                               class="form-input @error('max_students') border-red-300 @enderror" 
                               min="1" max="100" placeholder="e.g., 30">
                        @error('max_students')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-600 mt-1">Leave blank for no limit</p>
                    </div>

                    <!-- Class Code -->
                    <div>
                        <label for="class_code" class="form-label">Class Code</label>
                        <input type="text" id="class_code" name="class_code" value="{{ old('class_code', $class->code) }}" 
                               class="form-input @error('class_code') border-red-300 @enderror" 
                               placeholder="e.g., CS101A" maxlength="10">
                        @error('class_code')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-600 mt-1">Unique code for student enrollment</p>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="form-label required">Status</label>
                    <select id="status" name="status" class="form-input @error('status') border-red-300 @enderror" required>
                        <option value="active" {{ old('status', $class->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $class->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="archived" {{ old('status', $class->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    @error('status')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Schedule Information -->
                <div>
                    <label for="schedule" class="form-label">Schedule</label>
                    <textarea id="schedule" name="schedule" rows="2" 
                              class="form-input @error('schedule') border-red-300 @enderror"
                              placeholder="e.g., Mon/Wed/Fri 10:00-11:30 AM">{{ old('schedule', $class->schedule) }}</textarea>
                    @error('schedule')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-600 mt-1">Optional class schedule information</p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('admin.classes.index') }}" class="btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    <span class="material-icon text-sm mr-2">save</span>
                    Update Class
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Current Enrollments -->
@if($class->enrollments && $class->enrollments->count() > 0)
<div class="card mt-6">
    <div class="card-header">
        <h3 class="text-lg font-semibold text-gray-900">Current Enrollments ({{ $class->enrollments->count() }})</h3>
    </div>
    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrolled</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($class->enrollments as $enrollment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $enrollment->user->first_name }} {{ $enrollment->user->last_name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $enrollment->user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $enrollment->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form method="POST" action="{{ route('admin.classes.unenroll', [$class, $enrollment->user]) }}" 
                                      onsubmit="return confirm('Are you sure you want to unenroll this student?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        Unenroll
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection

@extends('layouts.dashboard')

@section('title', 'Create Class - Admin')
@section('page-title', 'Create New Class')

@section('sidebar                    <!-- Class Code -->
                    <div>
                        <label for="class_code" class="form-label">Class Code</label>
                        <input type="text" id="class_code" name="class_code" value="{{ old('class_code', strtoupper(substr(fake()->word(), 0, 3) . rand(100, 999))) }}" 
                               class="form-input @error('class_code') border-red-300 @enderror" 
                               placeholder="e.g., CS101A" maxlength="10">
                        @error('class_code')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-600 mt-1">Unique code for student enrollment</p>
                    </div>
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
        <h1 class="text-2xl font-bold text-gray-900">Create New Class</h1>
        <p class="text-sm text-gray-600 mt-1">Add a new class to the system</p>
    </div>
    <a href="{{ route('admin.classes.index') }}" class="btn-secondary">
        <span class="material-icon text-sm mr-2">arrow_back</span>
        Back to Classes
    </a>
</div>

<!-- Create Class Form -->
<div class="card">
    <div class="card-header">
        <h3 class="text-lg font-semibold text-gray-900">Class Information</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.classes.store') }}">
            @csrf
            
            <div class="space-y-6">
                <!-- Class Name -->
                <div>
                    <label for="name" class="form-label required">Class Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" 
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
                              placeholder="Enter class description...">{{ old('description') }}</textarea>
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
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                    @if($subjects->isEmpty())
                        <p class="text-sm text-yellow-600 mt-1">
                            <span class="material-icon text-sm mr-1">warning</span>
                            No subjects available. You may need to create subjects first.
                        </p>
                    @endif
                </div>

                <!-- Teacher Assignment -->
                <div>
                    <label for="teacher_id" class="form-label required">Assign Teacher</label>
                    <select id="teacher_id" name="teacher_id" class="form-input @error('teacher_id') border-red-300 @enderror" required>
                        <option value="">Select a teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->first_name }} {{ $teacher->last_name }} ({{ $teacher->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                    @if($teachers->isEmpty())
                        <p class="text-sm text-yellow-600 mt-1">
                            <span class="material-icon text-sm mr-1">warning</span>
                            No teachers available in the system.
                        </p>
                    @endif
                </div>

                <!-- Class Settings -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Max Students -->
                    <div>
                        <label for="max_students" class="form-label">Maximum Students</label>
                        <input type="number" id="max_students" name="max_students" value="{{ old('max_students') }}" 
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
                        <input type="text" id="class_code" name="class_code" value="{{ old('class_code', strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6))) }}" 
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
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
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
                              placeholder="e.g., Mon/Wed/Fri 10:00-11:30 AM">{{ old('schedule') }}</textarea>
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
                    Create Class
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Generate random class code
    document.getElementById('name').addEventListener('input', function() {
        const name = this.value;
        const codeField = document.getElementById('class_code');
        
        if (name && !codeField.dataset.userModified) {
            // Generate code from class name
            const code = name.toUpperCase()
                .replace(/[^A-Z0-9]/g, '')
                .substring(0, 6) + Math.floor(Math.random() * 100);
            codeField.value = code;
        }
    });
    
    // Mark code field as user-modified when manually changed
    document.getElementById('class_code').addEventListener('input', function() {
        this.dataset.userModified = 'true';
    });
</script>
@endpush

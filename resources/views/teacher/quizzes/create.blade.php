@extends('layouts.dashboard')

@section('title', 'Create Quiz - Teacher')
@section('page-title', 'Create New Quiz')

@section('sidebar-content')
<a href="{{ route('teacher.dashboard') }}" class="nav-link">
    <span class="material-icon text-lg mr-3">dashboard</span>
    Dashboard
</a>
<a href="{{ route('teacher.quizzes.index') }}" class="nav-link active">
    <span class="material-icon text-lg mr-3">quiz</span>
    My Quizzes
</a>
<a href="#" class="nav-link">
    <span class="material-icon text-lg mr-3">class</span>
    My Classes
</a>
<a href="#" class="nav-link">
    <span class="material-icon text-lg mr-3">people</span>
    Students
</a>
<a href="#" class="nav-link">
    <span class="material-icon text-lg mr-3">assessment</span>
    Results
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
        <h1 class="text-2xl font-bold text-gray-900">Create New Quiz</h1>
        <p class="text-sm text-gray-600 mt-1">Set up a new quiz for your students</p>
    </div>
    <a href="{{ route('teacher.quizzes.index') }}" class="btn-secondary">
        <span class="material-icon text-sm mr-2">arrow_back</span>
        Back to Quizzes
    </a>
</div>

<!-- Create Quiz Form -->
<form method="POST" action="{{ route('teacher.quizzes.store') }}">
    @csrf
    
    <!-- Basic Information -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
        </div>
        <div class="card-body space-y-6">
            <!-- Quiz Title -->
            <div>
                <label for="title" class="form-label required">Quiz Title</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" 
                       class="form-input @error('title') border-red-300 @enderror" 
                       placeholder="Enter quiz title..." required>
                @error('title')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" rows="3" 
                          class="form-input @error('description') border-red-300 @enderror"
                          placeholder="Provide a brief description of the quiz...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Class Selection -->
            <div>
                <label for="class_id" class="form-label required">Assign to Class</label>
                <select id="class_id" name="class_id" class="form-input @error('class_id') border-red-300 @enderror" required>
                    <option value="">Select a class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }} ({{ $class->enrollments_count ?? 0 }} students)
                        </option>
                    @endforeach
                </select>
                @error('class_id')
                    <p class="form-error">{{ $message }}</p>
                @enderror
                @if($classes->isEmpty())
                    <p class="text-sm text-yellow-600 mt-1">
                        <span class="material-icon text-sm mr-1">warning</span>
                        You don't have any classes yet. You may need to create a class first.
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Quiz Settings -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Quiz Settings</h3>
        </div>
        <div class="card-body space-y-6">
            <!-- Time and Attempts Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Time Limit -->
                <div>
                    <label for="time_limit" class="form-label">Time Limit (minutes)</label>
                    <input type="number" id="time_limit" name="time_limit" value="{{ old('time_limit') }}" 
                           class="form-input @error('time_limit') border-red-300 @enderror" 
                           min="1" max="480" placeholder="e.g., 60">
                    @error('time_limit')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-600 mt-1">Leave blank for no time limit</p>
                </div>

                <!-- Max Attempts -->
                <div>
                    <label for="max_attempts" class="form-label">Maximum Attempts</label>
                    <input type="number" id="max_attempts" name="max_attempts" value="{{ old('max_attempts') }}" 
                           class="form-input @error('max_attempts') border-red-300 @enderror" 
                           min="1" max="10" placeholder="e.g., 3">
                    @error('max_attempts')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-600 mt-1">Leave blank for unlimited attempts</p>
                </div>
            </div>

            <!-- Randomize Questions -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="randomize_questions" value="1" 
                           {{ old('randomize_questions') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Randomize question order for each student</span>
                </label>
            </div>

            <!-- Show Results -->
            <div>
                <label for="show_results" class="form-label">Show Results</label>
                <select id="show_results" name="show_results" class="form-input @error('show_results') border-red-300 @enderror">
                    <option value="immediately" {{ old('show_results') == 'immediately' ? 'selected' : '' }}>Immediately after submission</option>
                    <option value="after_due" {{ old('show_results') == 'after_due' ? 'selected' : '' }}>After due date</option>
                    <option value="manual" {{ old('show_results', 'manual') == 'manual' ? 'selected' : '' }}>Manual release by teacher</option>
                </select>
                @error('show_results')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Schedule -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Schedule</h3>
        </div>
        <div class="card-body space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Available From -->
                <div>
                    <label for="available_from" class="form-label">Available From</label>
                    <input type="datetime-local" id="available_from" name="available_from" 
                           value="{{ old('available_from') }}" 
                           class="form-input @error('available_from') border-red-300 @enderror">
                    @error('available_from')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-600 mt-1">When students can start taking the quiz</p>
                </div>

                <!-- Available Until -->
                <div>
                    <label for="available_until" class="form-label">Available Until</label>
                    <input type="datetime-local" id="available_until" name="available_until" 
                           value="{{ old('available_until') }}" 
                           class="form-input @error('available_until') border-red-300 @enderror">
                    @error('available_until')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-600 mt-1">When the quiz becomes unavailable</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Access Control -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Access Control</h3>
        </div>
        <div class="card-body space-y-6">
            <!-- Generate Access Code -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox" id="generate_access_code" name="generate_access_code" value="1" 
                           {{ old('generate_access_code') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Generate access code</span>
                </label>
                <p class="text-sm text-gray-600 mt-1">Students will need an access code to take the quiz</p>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="form-label required">Status</label>
                <select id="status" name="status" class="form-input @error('status') border-red-300 @enderror" required>
                    <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                </select>
                @error('status')
                    <p class="form-error">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-600 mt-1">Draft quizzes are not visible to students</p>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="flex justify-end space-x-4">
        <a href="{{ route('teacher.quizzes.index') }}" class="btn-secondary">
            Cancel
        </a>
        <button type="submit" class="btn-primary">
            <span class="material-icon text-sm mr-2">save</span>
            Create Quiz
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    // Set default available_from to current time
    document.addEventListener('DOMContentLoaded', function() {
        const availableFrom = document.getElementById('available_from');
        const availableUntil = document.getElementById('available_until');
        
        if (!availableFrom.value) {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            availableFrom.value = now.toISOString().slice(0, 16);
        }
        
        // Auto-set available_until when available_from changes
        availableFrom.addEventListener('change', function() {
            if (!availableUntil.value && this.value) {
                const fromDate = new Date(this.value);
                fromDate.setDate(fromDate.getDate() + 7); // Default to 1 week later
                availableUntil.value = fromDate.toISOString().slice(0, 16);
            }
        });
    });
</script>
@endpush

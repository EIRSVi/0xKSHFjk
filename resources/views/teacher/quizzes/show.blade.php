@extends('layouts.dashboard')

@section('title', 'Quiz Details - Teacher')
@section('page-title', $quiz->title)

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
<!-- Header with Actions -->
<div class="flex justify-between items-start mb-6">
    <div>
        <div class="flex items-center mb-2">
            <h1 class="text-2xl font-bold text-gray-900 mr-3">{{ $quiz->title }}</h1>
            @if($quiz->status === 'published')
                <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">Published</span>
            @elseif($quiz->status === 'draft')
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm rounded-full">Draft</span>
            @else
                <span class="px-3 py-1 bg-red-100 text-red-800 text-sm rounded-full">Closed</span>
            @endif
        </div>
        <p class="text-sm text-gray-600">{{ $quiz->class->name ?? 'No Class Assigned' }}</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('teacher.quizzes.index') }}" class="btn-secondary">
            <span class="material-icon text-sm mr-2">arrow_back</span>
            Back to Quizzes
        </a>
        <a href="{{ route('teacher.quizzes.edit', $quiz) }}" class="btn-primary">
            <span class="material-icon text-sm mr-2">edit</span>
            Edit Quiz
        </a>
    </div>
</div>

<!-- Success Message -->
@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

<!-- Quiz Overview -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Main Info -->
    <div class="lg:col-span-2">
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Quiz Information</h3>
            </div>
            <div class="card-body space-y-4">
                @if($quiz->description)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Description</h4>
                        <p class="text-gray-600">{{ $quiz->description }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-1">Questions</h4>
                        <p class="text-lg font-semibold text-blue-600">{{ $quiz->questions_count ?? 0 }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-1">Attempts</h4>
                        <p class="text-lg font-semibold text-green-600">{{ $quiz->attempts_count ?? 0 }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t">
                    @if($quiz->time_limit)
                        <div class="flex items-center text-gray-600">
                            <span class="material-icon text-lg mr-2">timer</span>
                            <span>{{ $quiz->time_limit }} minutes time limit</span>
                        </div>
                    @endif
                    @if($quiz->max_attempts)
                        <div class="flex items-center text-gray-600">
                            <span class="material-icon text-lg mr-2">replay</span>
                            <span>Maximum {{ $quiz->max_attempts }} attempts</span>
                        </div>
                    @endif
                    @if($quiz->randomize_questions)
                        <div class="flex items-center text-gray-600">
                            <span class="material-icon text-lg mr-2">shuffle</span>
                            <span>Questions randomized</span>
                        </div>
                    @endif
                    <div class="flex items-center text-gray-600">
                        <span class="material-icon text-lg mr-2">visibility</span>
                        <span>Results: {{ ucfirst(str_replace('_', ' ', $quiz->show_results)) }}</span>
                    </div>
                </div>

                @if($quiz->available_from || $quiz->available_until)
                    <div class="pt-4 border-t">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Availability</h4>
                        <div class="space-y-2">
                            @if($quiz->available_from)
                                <div class="flex items-center text-gray-600 text-sm">
                                    <span class="material-icon text-sm mr-2">schedule</span>
                                    <span>Available from: {{ $quiz->available_from->format('M j, Y g:i A') }}</span>
                                </div>
                            @endif
                            @if($quiz->available_until)
                                <div class="flex items-center text-gray-600 text-sm">
                                    <span class="material-icon text-sm mr-2">schedule</span>
                                    <span>Available until: {{ $quiz->available_until->format('M j, Y g:i A') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Side Panel -->
    <div>
        <!-- Quick Actions -->
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
            </div>
            <div class="card-body space-y-3">
                <a href="{{ route('teacher.quizzes.questions.index', $quiz) }}" class="btn-primary w-full justify-center">
                    <span class="material-icon text-sm mr-2">help</span>
                    Manage Questions
                </a>
                <a href="#" class="btn-secondary w-full justify-center">
                    <span class="material-icon text-sm mr-2">assessment</span>
                    View Results
                </a>
                <a href="#" class="btn-secondary w-full justify-center">
                    <span class="material-icon text-sm mr-2">download</span>
                    Export Data
                </a>
            </div>
        </div>

        <!-- Access Code -->
        @if($quiz->access_code)
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Access Code</h3>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="text-2xl font-mono bg-gray-100 px-4 py-3 rounded-lg mb-2">
                        {{ $quiz->access_code }}
                    </div>
                    <p class="text-sm text-gray-600">Share this code with your students</p>
                    <button onclick="copyToClipboard('{{ $quiz->access_code }}')" class="btn-secondary mt-2">
                        <span class="material-icon text-sm mr-1">content_copy</span>
                        Copy Code
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- Quiz Stats -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Statistics</h3>
            </div>
            <div class="card-body space-y-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $quiz->questions_count ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Total Questions</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $quiz->attempts_count ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Total Attempts</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ $quiz->class->enrollments_count ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Students in Class</div>
                </div>
                <div class="pt-3 border-t text-center">
                    <div class="text-sm text-gray-600">
                        Created {{ $quiz->created_at->diffForHumans() }}
                    </div>
                    @if($quiz->updated_at->gt($quiz->created_at))
                        <div class="text-sm text-gray-600">
                            Updated {{ $quiz->updated_at->diffForHumans() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Questions Preview -->
<div class="card">
    <div class="card-header flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900">Questions Preview</h3>
        <a href="{{ route('teacher.quizzes.questions.index', $quiz) }}" class="text-blue-600 hover:text-blue-800 text-sm">
            View All Questions →
        </a>
    </div>
    <div class="card-body">
        @if($quiz->questions && $quiz->questions->count() > 0)
            <div class="space-y-4">
                @foreach($quiz->questions->take(3) as $index => $question)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-medium text-gray-900">Question {{ $index + 1 }}</h4>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">
                                {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                            </span>
                        </div>
                        <p class="text-gray-700 mb-2">{{ $question->question_text }}</p>
                        @if($question->type === 'multiple_choice' && $question->options)
                            <div class="text-sm text-gray-600">
                                <strong>Options:</strong> {{ implode(', ', array_map(fn($opt) => $opt['text'], $question->options)) }}
                            </div>
                        @endif
                    </div>
                @endforeach
                
                @if($quiz->questions->count() > 3)
                    <div class="text-center pt-4">
                        <p class="text-gray-600">And {{ $quiz->questions->count() - 3 }} more questions...</p>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center py-8">
                <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <span class="material-icon text-2xl text-gray-400">help</span>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">No questions yet</h4>
                <p class="text-gray-600 mb-4">Start building your quiz by adding questions.</p>
                <a href="{{ route('teacher.quizzes.questions.index', $quiz) }}" class="btn-primary">
                    <span class="material-icon text-sm mr-2">add</span>
                    Add Questions
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Show success message
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="material-icon text-sm mr-1">check</span>Copied!';
            btn.disabled = true;
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 2000);
        }).catch(function(err) {
            console.error('Failed to copy: ', err);
        });
    }
</script>
@endpush

@extends('layouts.dashboard')

@section('title', 'My Quizzes - Teacher')
@section('page-title', 'My Quizzes')

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
<!-- Header with Create Button -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">My Quizzes</h1>
        <p class="text-sm text-gray-600 mt-1">Create and manage your quizzes</p>
    </div>
    <a href="{{ route('teacher.quizzes.create') }}" class="btn-primary">
        <span class="material-icon text-sm mr-2">add</span>
        Create Quiz
    </a>
</div>

<!-- Filters -->
<div class="card mb-6">
    <div class="card-body">
        <form method="GET" action="{{ route('teacher.quizzes.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-0">
                <label for="search" class="form-label">Search Quizzes</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                       placeholder="Search by title or description..." class="form-input">
            </div>
            <div class="w-48">
                <label for="class_id" class="form-label">Filter by Class</label>
                <select id="class_id" name="class_id" class="form-input">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-40">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-input">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary">
                    <span class="material-icon text-sm mr-1">search</span>
                    Filter
                </button>
                <a href="{{ route('teacher.quizzes.index') }}" class="btn-secondary">
                    Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Success Message -->
@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

<!-- Quizzes Grid -->
@if($quizzes->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($quizzes as $quiz)
            <div class="card hover:shadow-lg transition-shadow">
                <div class="card-body">
                    <!-- Quiz Header -->
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $quiz->title }}</h3>
                            <p class="text-sm text-gray-600">{{ $quiz->class->name ?? 'No Class' }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <!-- Status Badge -->
                            @if($quiz->status === 'published')
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Published</span>
                            @elseif($quiz->status === 'draft')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Draft</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Closed</span>
                            @endif
                            
                            <!-- Actions Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="p-1 rounded hover:bg-gray-100">
                                    <span class="material-icon text-gray-500">more_vert</span>
                                </button>
                                <div x-show="open" @click.away="open = false" 
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border z-10">
                                    <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <span class="material-icon text-sm mr-2">visibility</span>
                                        View Details
                                    </a>
                                    <a href="{{ route('teacher.quizzes.edit', $quiz) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <span class="material-icon text-sm mr-2">edit</span>
                                        Edit Quiz
                                    </a>
                                    <a href="{{ route('teacher.quizzes.questions.index', $quiz) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <span class="material-icon text-sm mr-2">help</span>
                                        Manage Questions
                                    </a>
                                    <hr class="my-1">
                                    <form method="POST" action="{{ route('teacher.quizzes.destroy', $quiz) }}" 
                                          onsubmit="return confirm('Are you sure you want to delete this quiz?')" class="block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            <span class="material-icon text-sm mr-2">delete</span>
                                            Delete Quiz
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quiz Description -->
                    @if($quiz->description)
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $quiz->description }}</p>
                    @endif

                    <!-- Quiz Stats -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="text-center">
                            <div class="text-lg font-bold text-blue-600">{{ $quiz->questions_count ?? 0 }}</div>
                            <div class="text-xs text-gray-500">Questions</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-bold text-green-600">{{ $quiz->attempts_count ?? 0 }}</div>
                            <div class="text-xs text-gray-500">Attempts</div>
                        </div>
                    </div>

                    <!-- Quiz Details -->
                    <div class="space-y-2 text-sm">
                        @if($quiz->time_limit)
                            <div class="flex items-center text-gray-600">
                                <span class="material-icon text-sm mr-2">timer</span>
                                {{ $quiz->time_limit }} minutes
                            </div>
                        @endif
                        @if($quiz->max_attempts)
                            <div class="flex items-center text-gray-600">
                                <span class="material-icon text-sm mr-2">replay</span>
                                Max {{ $quiz->max_attempts }} attempts
                            </div>
                        @endif
                        <div class="flex items-center text-gray-600">
                            <span class="material-icon text-sm mr-2">schedule</span>
                            Created {{ $quiz->created_at->diffForHumans() }}
                        </div>
                    </div>

                    <!-- Access Code -->
                    @if($quiz->access_code)
                        <div class="mt-3 pt-3 border-t">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">Access Code</span>
                                <span class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">{{ $quiz->access_code }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($quizzes->hasPages())
        <div class="mt-8">
            {{ $quizzes->links() }}
        </div>
    @endif
@else
    <!-- Empty State -->
    <div class="text-center py-12">
        <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
            <span class="material-icon text-4xl text-gray-400">quiz</span>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">No quizzes found</h3>
        <p class="text-gray-600 mb-6">
            @if(request()->hasAny(['search', 'class_id', 'status']))
                No quizzes match your current filters. Try adjusting your search criteria.
            @else
                Get started by creating your first quiz for your students.
            @endif
        </p>
        <a href="{{ route('teacher.quizzes.create') }}" class="btn-primary">
            <span class="material-icon text-sm mr-2">add</span>
            Create Your First Quiz
        </a>
    </div>
@endif
@endsection

@push('scripts')
<script>
    // Auto-submit form on filter change
    document.querySelectorAll('#class_id, #status').forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
</script>
@endpush

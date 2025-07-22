@extends('layouts.dashboard')

@section('title', 'Class Details - Admin')
@section('page-title', $class->name)

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
<!-- Header with Actions -->
<div class="flex justify-between items-start mb-6">
    <div>
        <div class="flex items-center mb-2">
            <h1 class="text-2xl font-bold text-gray-900 mr-3">{{ $class->name }}</h1>
            @if($class->status === 'active')
                <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">Active</span>
            @elseif($class->status === 'inactive')
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm rounded-full">Inactive</span>
            @else
                <span class="px-3 py-1 bg-red-100 text-red-800 text-sm rounded-full">Archived</span>
            @endif
        </div>
        <p class="text-sm text-gray-600">{{ $class->subject->name ?? 'No Subject Assigned' }}</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.classes.index') }}" class="btn-secondary">
            <span class="material-icon text-sm mr-2">arrow_back</span>
            Back to Classes
        </a>
        <a href="{{ route('admin.classes.edit', $class) }}" class="btn-primary">
            <span class="material-icon text-sm mr-2">edit</span>
            Edit Class
        </a>
    </div>
</div>

<!-- Success Message -->
@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

<!-- Class Overview -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Main Info -->
    <div class="lg:col-span-2">
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Class Information</h3>
            </div>
            <div class="card-body space-y-4">
                @if($class->description)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Description</h4>
                        <p class="text-gray-600">{{ $class->description }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-1">Subject</h4>
                        <p class="text-gray-900">{{ $class->subject->name ?? 'Not Assigned' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-1">Teacher</h4>
                        <p class="text-gray-900">
                            @if($class->teacher)
                                {{ $class->teacher->first_name }} {{ $class->teacher->last_name }}
                            @else
                                Not Assigned
                            @endif
                        </p>
                    </div>
                </div>

                @if($class->code)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Class Code</h4>
                        <div class="flex items-center space-x-2">
                            <span class="text-lg font-mono bg-gray-100 px-3 py-1 rounded">{{ $class->code }}</span>
                            <button onclick="copyToClipboard('{{ $class->code }}')" class="btn-secondary text-sm">
                                <span class="material-icon text-sm mr-1">content_copy</span>
                                Copy
                            </button>
                        </div>
                    </div>
                @endif

                @if($class->schedule)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Schedule</h4>
                        <p class="text-gray-600">{{ $class->schedule }}</p>
                    </div>
                @endif

                @if($class->max_students)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Capacity</h4>
                        <div class="flex items-center space-x-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" 
                                     style="width: {{ min(100, ($class->enrollments->count() / $class->max_students) * 100) }}%"></div>
                            </div>
                            <span class="text-sm text-gray-600">
                                {{ $class->enrollments->count() }} / {{ $class->max_students }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Side Panel -->
    <div>
        <!-- Quick Stats -->
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Statistics</h3>
            </div>
            <div class="card-body space-y-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $class->enrollments->count() }}</div>
                    <div class="text-sm text-gray-500">Enrolled Students</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $class->quizzes->count() ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Quizzes</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">
                        {{ $class->quizzes->sum(function($quiz) { return $quiz->attempts->count(); }) ?? 0 }}
                    </div>
                    <div class="text-sm text-gray-500">Total Attempts</div>
                </div>
                <div class="pt-3 border-t text-center">
                    <div class="text-sm text-gray-600">
                        Created {{ $class->created_at->diffForHumans() }}
                    </div>
                    @if($class->updated_at->gt($class->created_at))
                        <div class="text-sm text-gray-600">
                            Updated {{ $class->updated_at->diffForHumans() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
            </div>
            <div class="card-body space-y-3">
                <button onclick="showEnrollModal()" class="btn-primary w-full justify-center">
                    <span class="material-icon text-sm mr-2">person_add</span>
                    Enroll Student
                </button>
                <a href="#" class="btn-secondary w-full justify-center">
                    <span class="material-icon text-sm mr-2">email</span>
                    Send Announcement
                </a>
                <a href="#" class="btn-secondary w-full justify-center">
                    <span class="material-icon text-sm mr-2">download</span>
                    Export Roster
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Enrolled Students -->
<div class="card mb-6">
    <div class="card-header flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900">
            Enrolled Students ({{ $class->enrollments->count() }})
        </h3>
        <button onclick="showEnrollModal()" class="btn-primary">
            <span class="material-icon text-sm mr-2">person_add</span>
            Enroll Student
        </button>
    </div>
    <div class="card-body">
        @if($class->enrollments->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrolled</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quiz Attempts</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($class->enrollments as $enrollment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                                <span class="text-white font-medium">
                                                    {{ substr($enrollment->user->first_name, 0, 1) }}{{ substr($enrollment->user->last_name, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $enrollment->user->first_name }} {{ $enrollment->user->last_name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $enrollment->user->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $enrollment->created_at->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $enrollment->user->quiz_attempts->where('quiz.class_id', $class->id)->count() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.users.show', $enrollment->user) }}" class="text-blue-600 hover:text-blue-900">
                                        View Profile
                                    </a>
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
        @else
            <div class="text-center py-8">
                <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <span class="material-icon text-2xl text-gray-400">people</span>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">No students enrolled</h4>
                <p class="text-gray-600 mb-4">This class doesn't have any enrolled students yet.</p>
                <button onclick="showEnrollModal()" class="btn-primary">
                    <span class="material-icon text-sm mr-2">person_add</span>
                    Enroll First Student
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Quizzes in Class -->
@if($class->quizzes && $class->quizzes->count() > 0)
<div class="card">
    <div class="card-header">
        <h3 class="text-lg font-semibold text-gray-900">Quizzes ({{ $class->quizzes->count() }})</h3>
    </div>
    <div class="card-body">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($class->quizzes as $quiz)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="font-medium text-gray-900">{{ $quiz->title }}</h4>
                        @if($quiz->status === 'published')
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Published</span>
                        @elseif($quiz->status === 'draft')
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Draft</span>
                        @else
                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Closed</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-600 mb-3">{{ $quiz->questions_count ?? 0 }} questions</p>
                    <div class="text-xs text-gray-500">
                        Created {{ $quiz->created_at->diffForHumans() }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Enroll Student Modal -->
<div id="enrollModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="hideEnrollModal()"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form method="POST" action="{{ route('admin.classes.enroll', $class) }}">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Enroll Student</h3>
                    <div>
                        <label for="student_id" class="form-label required">Select Student</label>
                        <select id="student_id" name="student_id" class="form-input" required>
                            <option value="">Choose a student...</option>
                            @foreach($availableStudents as $student)
                                <option value="{{ $student->id }}">
                                    {{ $student->first_name }} {{ $student->last_name }} ({{ $student->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="btn-primary sm:ml-3">
                        Enroll Student
                    </button>
                    <button type="button" onclick="hideEnrollModal()" class="btn-secondary">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
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

    function showEnrollModal() {
        document.getElementById('enrollModal').classList.remove('hidden');
    }

    function hideEnrollModal() {
        document.getElementById('enrollModal').classList.add('hidden');
    }
</script>
@endpush

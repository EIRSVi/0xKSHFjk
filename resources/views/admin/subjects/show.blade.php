@extends('layouts.dashboard')

@section('title', 'Subject Details - Admin')
@section('page-title', 'Subject Details')

@section('sidebar-content')
<a href="{{ route('admin.dashboard') }}" class="nav-link">
    <span class="material-icon text-lg mr-3">dashboard</span>
    Dashboard
</a>
<a href="{{ route('admin.users.index') }}" class="nav-link">
    <span class="material-icon text-lg mr-3">people</span>
    Users
</a>
<a href="{{ route('admin.subjects.index') }}" class="nav-link active">
    <span class="material-icon text-lg mr-3">book</span>
    Subjects
</a>
<a href="{{ route('admin.classes.index') }}" class="nav-link">
    <span class="material-icon text-lg mr-3">class</span>
    Classes
</a>
<a href="#" class="nav-link">
    <span class="material-icon text-lg mr-3">quiz</span>
    Quizzes
</a>
<a href="#" class="nav-link">
    <span class="material-icon text-lg mr-3">analytics</span>
    Reports
</a>
<a href="#" class="nav-link">
    <span class="material-icon text-lg mr-3">settings</span>
    Settings
</a>
@endsection

@section('header-actions')
<div class="flex space-x-2">
    <a href="{{ route('admin.subjects.edit', $subject) }}" class="btn-primary">
        <span class="material-icon text-sm mr-2">edit</span>
        Edit Subject
    </a>
    <a href="{{ route('admin.subjects.index') }}" class="btn-secondary">
        <span class="material-icon text-sm mr-2">arrow_back</span>
        Back to Subjects
    </a>
</div>
@endsection

@section('main-content')
<div class="max-w-6xl">
    <!-- Subject Overview -->
    <div class="card mb-6">
        <div class="card-body">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 rounded-xl flex items-center justify-center" 
                     style="background-color: {{ $subject->color }}20; color: {{ $subject->color }};">
                    <span class="material-icon text-3xl">{{ $subject->icon }}</span>
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $subject->name }}</h2>
                    <p class="text-lg text-gray-600">{{ $subject->code }}</p>
                    @if($subject->description)
                        <p class="text-gray-500 mt-2">{{ $subject->description }}</p>
                    @endif
                    <div class="flex items-center space-x-4 mt-3">
                        <span class="badge @if($subject->status === 'active') badge-success @else badge-danger @endif">
                            {{ ucfirst($subject->status) }}
                        </span>
                        <span class="text-sm text-gray-500">
                            Created by {{ $subject->creator->full_name }} on {{ $subject->created_at->format('M d, Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Subject Information -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Subject Information</h3>
            </div>
            <div class="card-body">
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Subject Name</dt>
                        <dd class="text-sm text-gray-900">{{ $subject->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Subject Code</dt>
                        <dd class="text-sm text-gray-900">{{ $subject->code }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="text-sm text-gray-900">{{ $subject->description ?: 'No description provided' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Theme Color</dt>
                        <dd class="text-sm text-gray-900">
                            <div class="flex items-center space-x-2">
                                <div class="w-6 h-6 rounded border border-gray-300" style="background-color: {{ $subject->color }};"></div>
                                <span>{{ $subject->color }}</span>
                            </div>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Icon</dt>
                        <dd class="text-sm text-gray-900">
                            <div class="flex items-center space-x-2">
                                <span class="material-icon" style="color: {{ $subject->color }};">{{ $subject->icon }}</span>
                                <span>{{ $subject->icon }}</span>
                            </div>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="text-sm text-gray-900">{{ ucfirst($subject->status) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created By</dt>
                        <dd class="text-sm text-gray-900">{{ $subject->creator->full_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created Date</dt>
                        <dd class="text-sm text-gray-900">{{ $subject->created_at->format('F d, Y g:i A') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Statistics -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Statistics</h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $subject->classes->count() }}</div>
                        <div class="text-sm text-blue-600">Classes</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $subject->quizzes->count() }}</div>
                        <div class="text-sm text-green-600">Quizzes</div>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600">
                            {{ $subject->classes->sum(function($class) { return $class->enrollments->count(); }) }}
                        </div>
                        <div class="text-sm text-yellow-600">Students</div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600">
                            {{ $subject->classes->unique('teacher_id')->count() }}
                        </div>
                        <div class="text-sm text-purple-600">Teachers</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Classes using this subject -->
    @if($subject->classes->count() > 0)
    <div class="card mt-6">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Classes</h3>
            <p class="text-sm text-gray-600">Classes that use this subject</p>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Teacher</th>
                            <th>Students</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subject->classes as $class)
                        <tr>
                            <td>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $class->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $class->code }}</div>
                                </div>
                            </td>
                            <td class="text-sm text-gray-900">{{ $class->teacher->full_name }}</td>
                            <td class="text-sm text-gray-500">{{ $class->enrollments->count() }} students</td>
                            <td>
                                <span class="badge @if($class->status === 'active') badge-success @else badge-danger @endif">
                                    {{ ucfirst($class->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.classes.show', $class) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition-colors"
                                   title="View Class">
                                    <span class="material-icon text-sm">visibility</span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Danger Zone -->
    <div class="card mt-6 border-red-200">
        <div class="card-header border-red-200">
            <h3 class="text-lg font-semibold text-red-900">Danger Zone</h3>
            <p class="text-sm text-red-600">Irreversible and destructive actions.</p>
        </div>
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-medium text-gray-900">Delete Subject</h4>
                    <p class="text-sm text-gray-500">
                        @if($subject->classes->count() > 0 || $subject->quizzes->count() > 0)
                            Cannot delete this subject because it has associated classes or quizzes.
                        @else
                            Once you delete this subject, all associated data will be permanently removed.
                        @endif
                    </p>
                </div>
                @if($subject->classes->count() === 0 && $subject->quizzes->count() === 0)
                <form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}" 
                      onsubmit="return confirm('Are you sure you want to delete this subject? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        Delete Subject
                    </button>
                </form>
                @else
                <button disabled class="bg-gray-300 text-gray-500 font-medium py-2 px-4 rounded-md cursor-not-allowed">
                    Cannot Delete
                </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

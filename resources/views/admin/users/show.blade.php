@extends('layouts.dashboard')

@section('title', 'User Details - Admin')
@section('page-title', 'User Details')

@section('sidebar-content')
<a href="{{ route('admin.dashboard') }}" class="nav-link">
    <span class="material-icon text-lg mr-3">dashboard</span>
    Dashboard
</a>
<a href="{{ route('admin.users.index') }}" class="nav-link active">
    <span class="material-icon text-lg mr-3">people</span>
    Users
</a>
<a href="{{ route('admin.subjects.index') }}" class="nav-link">
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
    <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary">
        <span class="material-icon text-sm mr-2">edit</span>
        Edit User
    </a>
    <a href="{{ route('admin.users.index') }}" class="btn-secondary">
        <span class="material-icon text-sm mr-2">arrow_back</span>
        Back to Users
    </a>
</div>
@endsection

@section('main-content')
<div class="max-w-4xl">
    <!-- User Overview -->
    <div class="card mb-6">
        <div class="card-body">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center">
                    <span class="material-icon text-gray-600 text-2xl">person</span>
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    <div class="flex items-center space-x-4 mt-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($user->role === 'admin') bg-red-100 text-red-800
                            @elseif($user->role === 'teacher') bg-blue-100 text-blue-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                        <span class="badge @if($user->status === 'active') badge-success @else badge-danger @endif">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- User Information -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">User Information</h3>
            </div>
            <div class="card-body">
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                        <dd class="text-sm text-gray-900">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                        <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Role</dt>
                        <dd class="text-sm text-gray-900">{{ ucfirst($user->role) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="text-sm text-gray-900">{{ ucfirst($user->status) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email Verified</dt>
                        <dd class="text-sm text-gray-900">
                            @if($user->email_verified_at)
                                <span class="text-green-600">✓ Verified on {{ $user->email_verified_at->format('M d, Y') }}</span>
                            @else
                                <span class="text-red-600">✗ Not verified</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                        <dd class="text-sm text-gray-900">{{ $user->created_at->format('F d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="text-sm text-gray-900">{{ $user->updated_at->format('F d, Y g:i A') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Role-specific Information -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">
                    @if($user->role === 'teacher')
                        Teaching Information
                    @elseif($user->role === 'student')
                        Student Information
                    @else
                        System Information
                    @endif
                </h3>
            </div>
            <div class="card-body">
                @if($user->role === 'teacher')
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Classes Teaching</dt>
                            <dd class="text-sm text-gray-900">
                                @if($user->teachingClasses->count() > 0)
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach($user->teachingClasses as $class)
                                            <li>{{ $class->name }} ({{ $class->subject->name }})</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-gray-500">No classes assigned</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Students</dt>
                            <dd class="text-sm text-gray-900">
                                {{ $user->teachingClasses->sum(function($class) { return $class->enrollments->count(); }) }}
                            </dd>
                        </div>
                    </dl>
                @elseif($user->role === 'student')
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Enrolled Classes</dt>
                            <dd class="text-sm text-gray-900">
                                @if($user->enrolledClasses->count() > 0)
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach($user->enrolledClasses as $class)
                                            <li>{{ $class->name }} ({{ $class->subject->name }})</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-gray-500">Not enrolled in any classes</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Quiz Attempts</dt>
                            <dd class="text-sm text-gray-900">{{ $user->quizAttempts->count() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Completed Quizzes</dt>
                            <dd class="text-sm text-gray-900">
                                {{ $user->quizAttempts->where('status', 'completed')->count() }}
                            </dd>
                        </div>
                    </dl>
                @else
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">User ID</dt>
                            <dd class="text-sm text-gray-900">#{{ $user->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">System Role</dt>
                            <dd class="text-sm text-gray-900">System Administrator</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Permissions</dt>
                            <dd class="text-sm text-gray-900">Full system access</dd>
                        </div>
                    </dl>
                @endif
            </div>
        </div>
    </div>

    @if($user->id !== auth()->id())
    <!-- Danger Zone -->
    <div class="card mt-6 border-red-200">
        <div class="card-header border-red-200">
            <h3 class="text-lg font-semibold text-red-900">Danger Zone</h3>
            <p class="text-sm text-red-600">Irreversible and destructive actions.</p>
        </div>
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-medium text-gray-900">Delete User</h4>
                    <p class="text-sm text-gray-500">Once you delete this user, all of their data will be permanently removed.</p>
                </div>
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                      onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        Delete User
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@extends('layouts.dashboard')

@section('title', 'Admin Dashboard - OQS')
@section('page-title', 'Admin Dashboard')

@section('sidebar-content')
<a href="{{ route('admin.dashboard') }}" class="nav-link active">
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
<a href="{{ route('admin.classes.index') }}" class="nav-link">
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

@section('header-actions')
<button class="btn-primary">
    <span class="material-icon text-sm mr-2">add</span>
    Quick Actions
</button>
@endsection

@section('main-content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <span class="material-icon text-blue-600 text-xl">people</span>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                    <div class="flex text-xs text-gray-500 mt-1">
                        <span class="text-green-600">{{ $stats['total_students'] }} Students</span>
                        <span class="mx-2">•</span>
                        <span class="text-blue-600">{{ $stats['total_teachers'] }} Teachers</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Subjects -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <span class="material-icon text-green-600 text-xl">book</span>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Subjects</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_subjects'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['active_subjects'] }} Active</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Classes -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <span class="material-icon text-yellow-600 text-xl">class</span>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Classes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_classes'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['active_classes'] }} Active</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Quizzes -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <span class="material-icon text-red-600 text-xl">quiz</span>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Quizzes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_quizzes'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['published_quizzes'] }} Published</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Users -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-medium text-gray-900">Recent Users</h3>
        </div>
        <div class="card-body">
            <div class="space-y-4">
                @forelse($recent_users as $user)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                <span class="material-icon text-gray-600 text-sm">person</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $user->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <span class="badge badge-info">{{ ucfirst($user->role) }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No recent users</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Quizzes -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-medium text-gray-900">Recent Quizzes</h3>
        </div>
        <div class="card-body">
            <div class="space-y-4">
                @forelse($recent_quizzes as $quiz)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $quiz->title }}</p>
                            <p class="text-xs text-gray-500">{{ $quiz->subject->name ?? 'No Subject' }} • {{ $quiz->creator->full_name ?? 'Unknown' }}</p>
                        </div>
                        <span class="badge {{ $quiz->status === 'published' ? 'badge-success' : 'badge-warning' }}">
                            {{ ucfirst($quiz->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No recent quizzes</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-8">
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <button class="btn-secondary text-center p-4">
                    <span class="material-icon text-2xl mb-2 block">person_add</span>
                    Add User
                </button>
                <button class="btn-secondary text-center p-4">
                    <span class="material-icon text-2xl mb-2 block">book</span>
                    Create Subject
                </button>
                <button class="btn-secondary text-center p-4">
                    <span class="material-icon text-2xl mb-2 block">class</span>
                    New Class
                </button>
                <button class="btn-secondary text-center p-4">
                    <span class="material-icon text-2xl mb-2 block">analytics</span>
                    View Reports
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

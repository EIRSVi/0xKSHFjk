@extends('layouts.dashboard')

@section('title', 'Classes Management - Admin')
@section('page-title', 'Classes Management')

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
<a href="{{ route('admin.classes.create') }}" class="btn-primary">
    <span class="material-icon text-sm mr-2">add</span>
    Add New Class
</a>
@endsection

@section('main-content')
<div class="card">
    <div class="card-header">
        <h3 class="text-lg font-semibold text-gray-900">All Classes</h3>
        <p class="text-sm text-gray-600">Manage classes and their assignments</p>
    </div>
    <div class="card-body p-0">
        @if($classes->count() > 0)
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th>Students</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classes as $class)
                        <tr>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" 
                                         style="background-color: {{ $class->subject->color }}20; color: {{ $class->subject->color }};">
                                        <span class="material-icon text-lg">{{ $class->subject->icon }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $class->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $class->code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm font-medium text-gray-900">{{ $class->subject->name }}</div>
                                <div class="text-sm text-gray-500">{{ $class->subject->code }}</div>
                            </td>
                            <td class="text-sm text-gray-900">
                                {{ $class->teacher->full_name }}
                            </td>
                            <td class="text-sm text-gray-500">
                                {{ $class->enrollments->count() }}
                                @if($class->max_students)
                                    / {{ $class->max_students }}
                                @endif
                                student{{ $class->enrollments->count() !== 1 ? 's' : '' }}
                            </td>
                            <td>
                                <span class="badge @if($class->status === 'active') badge-success @else badge-danger @endif">
                                    {{ ucfirst($class->status) }}
                                </span>
                            </td>
                            <td class="text-sm text-gray-500">
                                {{ $class->created_at->format('M d, Y') }}
                            </td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.classes.show', $class) }}" 
                                       class="text-blue-600 hover:text-blue-800 transition-colors"
                                       title="View">
                                        <span class="material-icon text-sm">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.classes.enrollments', $class) }}" 
                                       class="text-green-600 hover:text-green-800 transition-colors"
                                       title="Manage Students">
                                        <span class="material-icon text-sm">people</span>
                                    </a>
                                    <a href="{{ route('admin.classes.edit', $class) }}" 
                                       class="text-yellow-600 hover:text-yellow-800 transition-colors"
                                       title="Edit">
                                        <span class="material-icon text-sm">edit</span>
                                    </a>
                                    <form method="POST" action="{{ route('admin.classes.destroy', $class) }}" 
                                          class="inline-block"
                                          onsubmit="return confirm('Are you sure you want to delete this class?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 transition-colors"
                                                title="Delete">
                                            <span class="material-icon text-sm">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($classes->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $classes->links() }}
            </div>
            @endif
        @else
            <div class="text-center py-12">
                <span class="material-icon text-gray-300 text-6xl mb-4">class</span>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No classes found</h3>
                <p class="text-gray-500 mb-4">Get started by creating your first class.</p>
                <a href="{{ route('admin.classes.create') }}" class="btn-primary">
                    <span class="material-icon text-sm mr-2">add</span>
                    Add New Class
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

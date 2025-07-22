@extends('layouts.dashboard')

@section('title', 'Subjects Management - Admin')
@section('page-title', 'Subjects Management')

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
<a href="{{ route('admin.subjects.create') }}" class="btn-primary">
    <span class="material-icon text-sm mr-2">add</span>
    Add New Subject
</a>
@endsection

@section('main-content')
<div class="card">
    <div class="card-header">
        <h3 class="text-lg font-semibold text-gray-900">All Subjects</h3>
        <p class="text-sm text-gray-600">Manage academic subjects and their settings</p>
    </div>
    <div class="card-body p-0">
        @if($subjects->count() > 0)
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Code</th>
                            <th>Created By</th>
                            <th>Classes</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $subject)
                        <tr>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" 
                                         style="background-color: {{ $subject->color }}20; color: {{ $subject->color }};">
                                        <span class="material-icon text-lg">{{ $subject->icon }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $subject->name }}</div>
                                        @if($subject->description)
                                            <div class="text-sm text-gray-500">{{ Str::limit($subject->description, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $subject->code }}
                                </span>
                            </td>
                            <td class="text-sm text-gray-900">
                                {{ $subject->creator->name }}
                            </td>
                            <td class="text-sm text-gray-500">
                                {{ $subject->classes->count() }} class{{ $subject->classes->count() !== 1 ? 'es' : '' }}
                            </td>
                            <td>
                                <span class="badge @if($subject->status === 'active') badge-success @else badge-danger @endif">
                                    {{ ucfirst($subject->status) }}
                                </span>
                            </td>
                            <td class="text-sm text-gray-500">
                                {{ $subject->created_at->format('M d, Y') }}
                            </td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.subjects.show', $subject) }}" 
                                       class="text-blue-600 hover:text-blue-800 transition-colors"
                                       title="View">
                                        <span class="material-icon text-sm">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.subjects.edit', $subject) }}" 
                                       class="text-green-600 hover:text-green-800 transition-colors"
                                       title="Edit">
                                        <span class="material-icon text-sm">edit</span>
                                    </a>
                                    <form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}" 
                                          class="inline-block"
                                          onsubmit="return confirm('Are you sure you want to delete this subject?')">
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
            @if($subjects->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $subjects->links() }}
            </div>
            @endif
        @else
            <div class="text-center py-12">
                <span class="material-icon text-gray-300 text-6xl mb-4">book</span>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No subjects found</h3>
                <p class="text-gray-500 mb-4">Get started by creating your first subject.</p>
                <a href="{{ route('admin.subjects.create') }}" class="btn-primary">
                    <span class="material-icon text-sm mr-2">add</span>
                    Add New Subject
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

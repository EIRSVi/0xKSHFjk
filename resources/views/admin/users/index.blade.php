@extends('layouts.dashboard')

@section('title', 'Users Management - Admin')
@section('page-title', 'Users Management')

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
<a href="{{ route('admin.users.create') }}" class="btn-primary">
    <span class="material-icon text-sm mr-2">add</span>
    Add New User
</a>
@endsection

@section('main-content')
<div class="card">
    <div class="card-header">
        <h3 class="text-lg font-semibold text-gray-900">All Users</h3>
        <p class="text-sm text-gray-600">Manage system users and their roles</p>
    </div>
    <div class="card-body p-0">
        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                        <span class="material-icon text-gray-600 text-sm">person</span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($user->role === 'admin') bg-red-100 text-red-800
                                    @elseif($user->role === 'teacher') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge @if($user->status === 'active') badge-success @else badge-danger @endif">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="text-sm text-gray-500">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="text-blue-600 hover:text-blue-800 transition-colors"
                                       title="View">
                                        <span class="material-icon text-sm">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="text-green-600 hover:text-green-800 transition-colors"
                                       title="Edit">
                                        <span class="material-icon text-sm">edit</span>
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                                          class="inline-block"
                                          onsubmit="return confirm('Are you sure you want to delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 transition-colors"
                                                title="Delete">
                                            <span class="material-icon text-sm">delete</span>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
            @endif
        @else
            <div class="text-center py-12">
                <span class="material-icon text-gray-300 text-6xl mb-4">people</span>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No users found</h3>
                <p class="text-gray-500 mb-4">Get started by creating your first user.</p>
                <a href="{{ route('admin.users.create') }}" class="btn-primary">
                    <span class="material-icon text-sm mr-2">add</span>
                    Add New User
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

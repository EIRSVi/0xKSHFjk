@extends('layouts.dashboard')

@section('title', 'Edit User - Admin')
@section('page-title', 'Edit User')

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
<a href="{{ route('admin.users.index') }}" class="btn-secondary">
    <span class="material-icon text-sm mr-2">arrow_back</span>
    Back to Users
</a>
@endsection

@section('main-content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Edit User</h3>
            <p class="text-sm text-gray-600">Update user information and settings</p>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $user->name) }}"
                           class="form-input @error('name') border-red-300 @enderror"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}"
                           class="form-input @error('email') border-red-300 @enderror"
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" 
                           id="password" 
                           name="password"
                           class="form-input @error('password') border-red-300 @enderror"
                           placeholder="Leave blank to keep current password">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Leave blank to keep the current password</p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation"
                           class="form-input"
                           placeholder="Confirm new password">
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="form-label">Role</label>
                    <select id="role" 
                            name="role" 
                            class="form-input @error('role') border-red-300 @enderror"
                            required>
                        <option value="">Select Role</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                        <option value="teacher" {{ old('role', $user->role) === 'teacher' ? 'selected' : '' }}>Teacher</option>
                        <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Student</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="form-label">Status</label>
                    <select id="status" 
                            name="status" 
                            class="form-input @error('status') border-red-300 @enderror"
                            required>
                        <option value="">Select Status</option>
                        <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- User Info -->
                <div class="bg-gray-50 p-4 rounded-md">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">User Information</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Created:</span>
                            <span class="text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Last Updated:</span>
                            <span class="text-gray-900">{{ $user->updated_at->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Email Verified:</span>
                            <span class="text-gray-900">{{ $user->email_verified_at ? 'Yes' : 'No' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">User ID:</span>
                            <span class="text-gray-900">#{{ $user->id }}</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        <span class="material-icon text-sm mr-2">save</span>
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

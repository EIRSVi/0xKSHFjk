@extends('layouts.app')

@section('title', 'Register - OQS')

@section('content')
<div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center bg-red-500 rounded-lg">
                <span class="material-icon text-white text-2xl">quiz</span>
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
                Create Account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Register for Online Quiz System
            </p>
        </div>
        <form class="mt-8 space-y-6" action="{{ route('register') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="form-label">First Name</label>
                        <input id="first_name" name="first_name" type="text" required 
                               class="form-input @error('first_name') border-red-500 @enderror" 
                               placeholder="First name"
                               value="{{ old('first_name') }}">
                        @error('first_name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="last_name" class="form-label">Last Name</label>
                        <input id="last_name" name="last_name" type="text" required 
                               class="form-input @error('last_name') border-red-500 @enderror" 
                               placeholder="Last name"
                               value="{{ old('last_name') }}">
                        @error('last_name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="username" class="form-label">Username</label>
                    <input id="username" name="username" type="text" required 
                           class="form-input @error('username') border-red-500 @enderror" 
                           placeholder="Choose a username"
                           value="{{ old('username') }}">
                    @error('username')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="form-label">Email</label>
                    <input id="email" name="email" type="email" required 
                           class="form-input @error('email') border-red-500 @enderror" 
                           placeholder="Enter your email"
                           value="{{ old('email') }}">
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="form-label">Role</label>
                    <select id="role" name="role" required 
                            class="form-input @error('role') border-red-500 @enderror">
                        <option value="">Select Role</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                        <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                        <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                    </select>
                    @error('role')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="form-label">Password</label>
                    <input id="password" name="password" type="password" required 
                           class="form-input @error('password') border-red-500 @enderror" 
                           placeholder="Create a password">
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required 
                           class="form-input" 
                           placeholder="Confirm your password">
                </div>
            </div>

            <div>
                <button type="submit" class="btn-primary w-full">
                    <span class="material-icon text-sm mr-2">person_add</span>
                    Create Account
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-sm text-red-600 hover:text-red-500">
                    Already have an account? Sign in
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

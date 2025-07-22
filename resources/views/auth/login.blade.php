@extends('layouts.app')

@section('title', 'Login - OQS')

@section('content')
<div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center bg-red-500 rounded-lg">
                <span class="material-icon text-white text-2xl">quiz</span>
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
                Online Quiz System
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Sign in to your account
            </p>
        </div>
        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="login" class="form-label">Username or Email</label>
                    <input id="login" name="login" type="text" required 
                           class="form-input @error('login') border-red-500 @enderror" 
                           placeholder="Enter your username or email"
                           value="{{ old('login') }}">
                    @error('login')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="form-label">Password</label>
                    <input id="password" name="password" type="password" required 
                           class="form-input @error('password') border-red-500 @enderror" 
                           placeholder="Enter your password">
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="btn-primary w-full">
                    <span class="material-icon text-sm mr-2">login</span>
                    Sign in
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('register') }}" class="text-sm text-red-600 hover:text-red-500">
                    Need an account? Register here
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

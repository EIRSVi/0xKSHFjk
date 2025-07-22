@extends('layouts.app')

@section('content')
<div class="h-full flex">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="flex flex-col h-full">
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 px-4 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center">
                        <span class="material-icon text-white text-lg">quiz</span>
                    </div>
                    <span class="ml-2 text-xl font-bold text-gray-800">OQS</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2">
                @yield('sidebar-content')
            </nav>

            <!-- User info -->
            <div class="px-4 py-4 border-t border-gray-200">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                        <span class="material-icon text-gray-600 text-sm">person</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ auth()->user()->full_name }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full text-left text-sm text-gray-500 hover:text-red-500 transition-colors">
                        <span class="material-icon text-sm mr-2">logout</span>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200 h-16">
            <div class="h-full px-4 flex items-center justify-between">
                <div class="flex items-center">
                    <button class="md:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100" onclick="toggleSidebar()">
                        <span class="material-icon">menu</span>
                    </button>
                    <h1 class="ml-2 text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="flex items-center space-x-4">
                    @yield('header-actions')
                </div>
            </div>
        </header>

        <!-- Main content area -->
        <main class="flex-1 overflow-auto">
            <div class="p-6">
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('main-content')
            </div>
        </main>
    </div>
</div>

<!-- Mobile sidebar overlay -->
<div class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden" id="sidebar-overlay" onclick="toggleSidebar()"></div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    
    sidebar.classList.toggle('mobile-open');
    overlay.classList.toggle('hidden');
}
</script>
@endsection

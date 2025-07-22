@extends('layouts.dashboard')

@section('title', 'Reports - Admin')
@section('page-title', 'System Reports')

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
<a href="{{ route('admin.classes.index') }}" class="nav-link">
    <span class="material-icon text-lg mr-3">class</span>
    Classes
</a>
<a href="#" class="nav-link">
    <span class="material-icon text-lg mr-3">quiz</span>
    Quizzes
</a>
<a href="{{ route('admin.reports.index') }}" class="nav-link active">
    <span class="material-icon text-lg mr-3">analytics</span>
    Reports
</a>
<a href="#" class="nav-link">
    <span class="material-icon text-lg mr-3">settings</span>
    Settings
</a>
@endsection

@section('main-content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- User Reports -->
    <div class="card hover:shadow-lg transition-shadow cursor-pointer" onclick="window.location.href='{{ route('admin.reports.users') }}'">
        <div class="card-body">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                    <span class="material-icon text-white text-xl">people</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">User Reports</h3>
                    <p class="text-sm text-gray-600">User statistics and analytics</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Subject Reports -->
    <div class="card hover:shadow-lg transition-shadow cursor-pointer" onclick="window.location.href='{{ route('admin.reports.subjects') }}'">
        <div class="card-body">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                    <span class="material-icon text-white text-xl">book</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Subject Reports</h3>
                    <p class="text-sm text-gray-600">Subject usage and statistics</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Class Reports -->
    <div class="card hover:shadow-lg transition-shadow cursor-pointer" onclick="window.location.href='{{ route('admin.reports.classes') }}'">
        <div class="card-body">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                    <span class="material-icon text-white text-xl">class</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Class Reports</h3>
                    <p class="text-sm text-gray-600">Class enrollment and activities</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quiz Reports -->
    <div class="card hover:shadow-lg transition-shadow cursor-pointer" onclick="window.location.href='{{ route('admin.reports.quizzes') }}'">
        <div class="card-body">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
                    <span class="material-icon text-white text-xl">quiz</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Quiz Reports</h3>
                    <p class="text-sm text-gray-600">Quiz performance and statistics</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Reports -->
    <div class="card hover:shadow-lg transition-shadow cursor-pointer" onclick="window.location.href='{{ route('admin.reports.performance') }}'">
        <div class="card-body">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                    <span class="material-icon text-white text-xl">trending_up</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Performance Reports</h3>
                    <p class="text-sm text-gray-600">Student and teacher performance</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Tools -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gray-500 rounded-lg flex items-center justify-center">
                    <span class="material-icon text-white text-xl">download</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Export Data</h3>
                    <p class="text-sm text-gray-600">Download reports in various formats</p>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.reports.export') }}" class="space-y-3">
                @csrf
                <select name="report_type" class="form-input text-sm" required>
                    <option value="">Select Report Type</option>
                    <option value="users">Users Report</option>
                    <option value="subjects">Subjects Report</option>
                    <option value="classes">Classes Report</option>
                    <option value="quizzes">Quizzes Report</option>
                    <option value="performance">Performance Report</option>
                </select>
                <select name="format" class="form-input text-sm" required>
                    <option value="">Select Format</option>
                    <option value="csv">CSV</option>
                    <option value="pdf">PDF</option>
                </select>
                <button type="submit" class="btn-primary w-full text-sm">
                    <span class="material-icon text-sm mr-2">download</span>
                    Export Report
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="mt-8">
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Quick System Overview</h3>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ \App\Models\User::count() }}</div>
                    <div class="text-sm text-gray-500">Total Users</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ \App\Models\Subject::count() }}</div>
                    <div class="text-sm text-gray-500">Subjects</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ \App\Models\ClassModel::count() }}</div>
                    <div class="text-sm text-gray-500">Classes</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600">{{ \App\Models\Quiz::count() }}</div>
                    <div class="text-sm text-gray-500">Quizzes</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

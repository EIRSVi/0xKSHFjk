@extends('layouts.dashboard')

@section('title', 'Create Subject - Admin')
@section('page-title', 'Create New Subject')

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
<a href="{{ route('admin.subjects.index') }}" class="btn-secondary">
    <span class="material-icon text-sm mr-2">arrow_back</span>
    Back to Subjects
</a>
@endsection

@section('main-content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Create New Subject</h3>
            <p class="text-sm text-gray-600">Add a new academic subject to the system</p>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.subjects.store') }}" class="space-y-6">
                @csrf

                <!-- Subject Name -->
                <div>
                    <label for="name" class="form-label">Subject Name</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="form-input @error('name') border-red-300 @enderror"
                           placeholder="e.g., Mathematics, Science, History"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subject Code -->
                <div>
                    <label for="code" class="form-label">Subject Code</label>
                    <input type="text" 
                           id="code" 
                           name="code" 
                           value="{{ old('code') }}"
                           class="form-input @error('code') border-red-300 @enderror"
                           placeholder="e.g., MATH101, SCI201, HIST301"
                           required>
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Unique identifier for this subject</p>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" 
                              name="description" 
                              rows="3"
                              class="form-input @error('description') border-red-300 @enderror"
                              placeholder="Brief description of the subject...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Color and Icon -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Color Picker -->
                    <div>
                        <label for="color" class="form-label">Subject Color</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" 
                                   id="color" 
                                   name="color" 
                                   value="{{ old('color', '#EA4335') }}"
                                   class="w-12 h-10 border border-gray-300 rounded-md cursor-pointer @error('color') border-red-300 @enderror">
                            <input type="text" 
                                   id="color_text" 
                                   name="color" 
                                   value="{{ old('color', '#EA4335') }}"
                                   class="form-input @error('color') border-red-300 @enderror"
                                   placeholder="#EA4335">
                        </div>
                        @error('color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Icon Selector -->
                    <div>
                        <label for="icon" class="form-label">Subject Icon</label>
                        <select id="icon" 
                                name="icon" 
                                class="form-input @error('icon') border-red-300 @enderror"
                                required>
                            <option value="">Select Icon</option>
                            <option value="calculate" {{ old('icon') === 'calculate' ? 'selected' : '' }}>📊 Calculate (Math)</option>
                            <option value="science" {{ old('icon') === 'science' ? 'selected' : '' }}>🧪 Science</option>
                            <option value="history_edu" {{ old('icon') === 'history_edu' ? 'selected' : '' }}>📚 History</option>
                            <option value="language" {{ old('icon') === 'language' ? 'selected' : '' }}>🌐 Language</option>
                            <option value="palette" {{ old('icon') === 'palette' ? 'selected' : '' }}>🎨 Arts</option>
                            <option value="fitness_center" {{ old('icon') === 'fitness_center' ? 'selected' : '' }}>💪 Physical Education</option>
                            <option value="piano" {{ old('icon') === 'piano' ? 'selected' : '' }}>🎹 Music</option>
                            <option value="computer" {{ old('icon') === 'computer' ? 'selected' : '' }}>💻 Computer Science</option>
                            <option value="business" {{ old('icon') === 'business' ? 'selected' : '' }}>💼 Business</option>
                            <option value="psychology" {{ old('icon') === 'psychology' ? 'selected' : '' }}>🧠 Psychology</option>
                            <option value="book" {{ old('icon') === 'book' ? 'selected' : '' }}>📖 Literature</option>
                            <option value="public" {{ old('icon') === 'public' ? 'selected' : '' }}>🌍 Geography</option>
                        </select>
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Preview -->
                <div>
                    <label class="form-label">Preview</label>
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <div id="icon-preview" class="w-12 h-12 rounded-lg flex items-center justify-center mr-4" style="background-color: #EA433520; color: #EA4335;">
                            <span class="material-icon text-xl">book</span>
                        </div>
                        <div>
                            <div id="name-preview" class="text-lg font-medium text-gray-900">Subject Name</div>
                            <div id="code-preview" class="text-sm text-gray-500">SUBJECT_CODE</div>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="form-label">Status</label>
                    <select id="status" 
                            name="status" 
                            class="form-input @error('status') border-red-300 @enderror"
                            required>
                        <option value="">Select Status</option>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.subjects.index') }}" class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        <span class="material-icon text-sm mr-2">save</span>
                        Create Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Live preview functionality
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('code');
    const colorInput = document.getElementById('color');
    const colorTextInput = document.getElementById('color_text');
    const iconSelect = document.getElementById('icon');
    
    const namePreview = document.getElementById('name-preview');
    const codePreview = document.getElementById('code-preview');
    const iconPreview = document.getElementById('icon-preview');
    
    // Update preview when inputs change
    nameInput.addEventListener('input', function() {
        namePreview.textContent = this.value || 'Subject Name';
    });
    
    codeInput.addEventListener('input', function() {
        codePreview.textContent = this.value || 'SUBJECT_CODE';
    });
    
    function updateColor(color) {
        iconPreview.style.backgroundColor = color + '20';
        iconPreview.style.color = color;
        colorInput.value = color;
        colorTextInput.value = color;
    }
    
    colorInput.addEventListener('change', function() {
        updateColor(this.value);
    });
    
    colorTextInput.addEventListener('input', function() {
        if (/^#[0-9A-F]{6}$/i.test(this.value)) {
            updateColor(this.value);
        }
    });
    
    iconSelect.addEventListener('change', function() {
        const iconSpan = iconPreview.querySelector('.material-icon');
        iconSpan.textContent = this.value || 'book';
    });
});
</script>
@endsection

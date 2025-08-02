{{-- resources/views/admin/pages/archives/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', trans('all.Add File'))

@push('breadcrumbs')
    @php
    $breadcrumbs = [
        ['name' => trans('all.Archive Management'), 'url' => route('admin.archives.index')],
        ['name' => trans('all.Add File')]
    ];
    @endphp
@endpush

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="bg-gradient-to-r from-white to-gray-50 rounded-xl shadow-sm border border-gray-100 mb-6 p-6">
        <div class="flex items-center space-x-4 {{ is_rtl() ? 'space-x-reverse' : '' }}">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-upload text-primary-600 text-xl"></i>
                </div>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ trans('all.Add File') }}</h1>
                <p class="text-sm text-gray-600">{{ trans('all.Upload and organize your files') }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.archives.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="p-6"
              id="uploadForm">
            @csrf

            <div class="space-y-8">
                <!-- File Upload Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-file-upload text-primary-600 {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                        {{ trans('all.File Upload') }}
                    </h3>

                    <!-- Drag & Drop Upload Area -->
                    <div id="upload-area"
                         class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-primary-400 hover:bg-primary-50 transition-all cursor-pointer">
                        <div id="upload-content">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                            <p class="text-lg font-medium text-gray-700 mb-2">{{ trans('all.Drop your file here or click to browse') }}</p>
                            <p class="text-sm text-gray-500">{{ trans('all.Supports images, documents, videos, and more') }}</p>
                            <p class="text-xs text-gray-400 mt-2">{{ trans('all.Maximum file size: 100MB') }}</p>
                        </div>
                        <input type="file"
                               name="file"
                               id="file-input"
                               class="hidden"
                               accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar"
                               required>
                    </div>

                    <!-- File Preview -->
                    <div id="file-preview" class="hidden mt-4">
                        <div class="bg-gray-50 rounded-lg p-4 flex items-center space-x-4 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                            <div id="file-icon" class="flex-shrink-0 w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file text-primary-600"></i>
                            </div>
                            <div class="flex-1">
                                <div id="file-name" class="font-medium text-gray-900"></div>
                                <div id="file-info" class="text-sm text-gray-500"></div>
                            </div>
                            <button type="button"
                                    onclick="removeFile()"
                                    class="text-red-600 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    @error('file')
                        <div class="mt-2 flex items-center text-sm text-red-600">
                            <i class="fas fa-exclamation-circle {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- File Details Section -->
                <div class="border-t border-gray-100 pt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-info-circle text-primary-600 {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                        {{ trans('all.File Details') }}
                    </h3>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="lg:col-span-2">
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-3">
                                {{ trans('all.Title') }}
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="title"
                                   id="title"
                                   value="{{ old('title') }}"
                                   class="block w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all @error('title') border-red-300 ring-2 ring-red-200 @enderror"
                                   placeholder="{{ trans('all.Enter file title') }}"
                                   required>
                            @error('title')
                                <div class="mt-2 flex items-center text-sm text-red-600">
                                    <i class="fas fa-exclamation-circle {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-3">
                                {{ trans('all.Category') }}
                                <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id"
                                    id="category_id"
                                    class="block w-full px-4 py-3 text-gray-900 bg-white border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all @error('category_id') border-red-300 ring-2 ring-red-200 @enderror"
                                    required>
                                <option value="">{{ trans('all.Select Category') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}
                                            style="padding-{{ is_rtl() ? 'right' : 'left' }}: {{ $category->level * 20 }}px;">
                                        {{ str_repeat('—', $category->level) }} {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="mt-2 flex items-center text-sm text-red-600">
                                    <i class="fas fa-exclamation-circle {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-3">
                                {{ trans('all.Status') }}
                            </label>
                            <select name="status"
                                    id="status"
                                    class="block w-full px-4 py-3 text-gray-900 bg-white border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>{{ trans('all.Active') }}</option>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>{{ trans('all.Draft') }}</option>
                                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>{{ trans('all.Archived') }}</option>
                            </select>
                        </div>

                      
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                <a href="{{ route('admin.archives.index') }}"
                   class="inline-flex items-center px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                    <i class="fas fa-times {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                    {{ trans('all.cancel') }}
                </a>
                <button type="submit"
                        id="submit-btn"
                        class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm hover:shadow-md transition-all">
                    <i class="fas fa-upload {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                    {{ trans('all.Upload File') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
<style>
    .upload-dragover {
        border-color: #6366f1 !important;
        background-color: rgba(99, 102, 241, 0.05) !important;
    }

    .file-preview-image {
        max-width: 100%;
        max-height: 200px;
        object-fit: cover;
        border-radius: 0.5rem;
    }

    .progress-bar {
        height: 4px;
        background-color: #e5e7eb;
        border-radius: 2px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background-color: #6366f1;
        transition: width 0.3s ease;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('upload-area');
    const fileInput = document.getElementById('file-input');
    const filePreview = document.getElementById('file-preview');
    const uploadContent = document.getElementById('upload-content');
    const titleInput = document.getElementById('title');
    const submitBtn = document.getElementById('submit-btn');
    const form = document.getElementById('uploadForm');

    let selectedFile = null;

    // Click to upload
    uploadArea.addEventListener('click', () => fileInput.click());

    // Drag and drop handlers
    uploadArea.addEventListener('dragover', handleDragOver);
    uploadArea.addEventListener('dragleave', handleDragLeave);
    uploadArea.addEventListener('drop', handleDrop);

    // File input change
    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            handleFile(e.target.files[0]);
        }
    });

    // Auto-fill title from filename
    function handleFile(file) {
        selectedFile = file;

        // Show file preview
        showFilePreview(file);

        // Auto-fill title if empty
        if (!titleInput.value.trim()) {
            const fileName = file.name.split('.').slice(0, -1).join('.');
            titleInput.value = fileName.replace(/[-_]/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        }
    }

    function showFilePreview(file) {
        const fileName = document.getElementById('file-name');
        const fileInfo = document.getElementById('file-info');
        const fileIcon = document.getElementById('file-icon');

        fileName.textContent = file.name;
        fileInfo.textContent = `${formatFileSize(file.size)} • ${file.type}`;

        // Update icon based on file type
        let iconClass = 'fas fa-file';
        let bgColor = 'bg-primary-100';
        let textColor = 'text-primary-600';

        if (file.type.startsWith('image/')) {
            iconClass = 'fas fa-image';
            bgColor = 'bg-green-100';
            textColor = 'text-green-600';
        } else if (file.type.startsWith('video/')) {
            iconClass = 'fas fa-video';
            bgColor = 'bg-purple-100';
            textColor = 'text-purple-600';
        } else if (file.type.startsWith('audio/')) {
            iconClass = 'fas fa-music';
            bgColor = 'bg-yellow-100';
            textColor = 'text-yellow-600';
        } else if (file.type.includes('pdf')) {
            iconClass = 'fas fa-file-pdf';
            bgColor = 'bg-red-100';
            textColor = 'text-red-600';
        } else if (file.type.includes('word') || file.type.includes('document')) {
            iconClass = 'fas fa-file-word';
            bgColor = 'bg-blue-100';
            textColor = 'text-blue-600';
        }

        fileIcon.className = `flex-shrink-0 w-12 h-12 ${bgColor} rounded-lg flex items-center justify-center`;
        fileIcon.innerHTML = `<i class="${iconClass} ${textColor}"></i>`;

        // Show preview and hide upload area content
        filePreview.classList.remove('hidden');
        uploadContent.innerHTML = `
            <i class="fas fa-check-circle text-2xl text-green-500 mb-2"></i>
            <p class="text-sm text-green-600 font-medium">{{ trans('all.File selected successfully') }}</p>
            <p class="text-xs text-gray-500">{{ trans('all.Click to change file') }}</p>
        `;
        uploadArea.classList.add('border-green-300', 'bg-green-50');
        uploadArea.classList.remove('border-gray-300');
    }

    function removeFile() {
        selectedFile = null;
        fileInput.value = '';
        filePreview.classList.add('hidden');

        // Reset upload area
        uploadContent.innerHTML = `
            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
            <p class="text-lg font-medium text-gray-700 mb-2">{{ trans('all.Drop your file here or click to browse') }}</p>
            <p class="text-sm text-gray-500">{{ trans('all.Supports images, documents, videos, and more') }}</p>
            <p class="text-xs text-gray-400 mt-2">{{ trans('all.Maximum file size: 100MB') }}</p>
        `;
        uploadArea.classList.remove('border-green-300', 'bg-green-50');
        uploadArea.classList.add('border-gray-300');
    }

    function handleDragOver(e) {
        e.preventDefault();
        uploadArea.classList.add('upload-dragover');
    }

    function handleDragLeave(e) {
        e.preventDefault();
        uploadArea.classList.remove('upload-dragover');
    }

    function handleDrop(e) {
        e.preventDefault();
        uploadArea.classList.remove('upload-dragover');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFile(files[0]);
        }
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Form submission with loading state
    form.addEventListener('submit', function(e) {
        if (!selectedFile) {
            e.preventDefault();
            alert('{{ trans('all.Please select a file to upload') }}');
            return;
        }

        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>{{ trans('all.Uploading...') }}';
        submitBtn.disabled = true;

        // Re-enable if there's an error (will be handled by server)
        setTimeout(() => {
            submitBtn.innerHTML = '<i class="fas fa-upload {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>{{ trans('all.Upload File') }}';
            submitBtn.disabled = false;
        }, 10000);
    });

    // Auto-focus title input
    titleInput.focus();
});

// Make removeFile function global
window.removeFile = function() {
    const event = new Event('change');
    document.getElementById('file-input').value = '';
    document.getElementById('file-input').dispatchEvent(event);

    const filePreview = document.getElementById('file-preview');
    const uploadContent = document.getElementById('upload-content');
    const uploadArea = document.getElementById('upload-area');

    filePreview.classList.add('hidden');
    uploadContent.innerHTML = `
        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
        <p class="text-lg font-medium text-gray-700 mb-2">{{ trans('all.Drop your file here or click to browse') }}</p>
        <p class="text-sm text-gray-500">{{ trans('all.Supports images, documents, videos, and more') }}</p>
        <p class="text-xs text-gray-400 mt-2">{{ trans('all.Maximum file size: 100MB') }}</p>
    `;
    uploadArea.classList.remove('border-green-300', 'bg-green-50');
    uploadArea.classList.add('border-gray-300');
};
</script>
@endpush

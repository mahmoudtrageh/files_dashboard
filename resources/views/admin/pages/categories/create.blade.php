{{-- resources/views/admin/pages/categories/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', trans('all.create_category'))

@push('breadcrumbs')
    @php
    $breadcrumbs = [
        ['name' => trans('all.Category Management'), 'url' => route('admin.categories.index')],
        ['name' => trans('all.Create Category')]
    ];
    @endphp
@endpush

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-white to-gray-50 rounded-xl shadow-sm border border-gray-100 mb-6 p-6">
        <div class="flex items-center space-x-4 {{ is_rtl() ? 'space-x-reverse' : '' }}">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-plus text-primary-600 text-xl"></i>
                </div>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ trans('all.Create Category') }}</h1>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Enhanced Form -->
        <form action="{{ route('admin.categories.store') }}" method="POST" class="p-6" id="categoryForm">
            @csrf

            <div class="space-y-8">
                <!-- Basic Information Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Category Name -->
                    <div class="lg:col-span-2">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ trans('all.Category Name') }}
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name') }}"
                                   class="block w-full px-4 py-3 {{ is_rtl() ? 'pr-12' : 'pl-4' }} {{ is_rtl() ? 'pl-4' : 'pr-12' }} text-gray-900 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all @error('name') border-red-300 ring-2 ring-red-200 @enderror"
                                   placeholder="{{ trans('all.Enter Category Name') }}"
                                   required
                                   autocomplete="off"
                                   dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
                            <div class="absolute inset-y-0 {{ is_rtl() ? 'left-0 pl-3' : 'right-0 pr-3' }} flex items-center pointer-events-none">
                                <i class="fas fa-tag text-gray-400"></i>
                            </div>
                        </div>
                        @error('name')
                            <div class="mt-2 flex items-center text-sm text-red-600">
                                <i class="fas fa-exclamation-circle {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Parent Category Section -->
                <div class="border-t border-gray-100 pt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-sitemap text-primary-600 {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                        {{ trans('all.Hierarchy Settings') }}
                    </h3>

                    <div>
                        <label for="parent_id" class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ trans('all.Parent Category') }}
                        </label>
                        <div class="relative">
                            <select name="parent_id"
                                    id="parent_id"
                                    class="block w-full px-4 py-3 {{ is_rtl() ? 'pl-10' : 'pr-10' }} text-gray-900 bg-white border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all @error('parent_id') border-red-300 ring-2 ring-red-200 @enderror">
                                <option value="">{{ trans('all.No Parent') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                            {{ old('parent_id') == $category->id ? 'selected' : '' }}
                                            class="py-2"
                                            style="padding-{{ is_rtl() ? 'right' : 'left' }}: {{ $category->level * 20 }}px;">
                                        {{ str_repeat('—', $category->level) }} {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 {{ is_rtl() ? 'left-0 pl-3' : 'right-0 pr-3' }} flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                        @error('parent_id')
                            <div class="mt-2 flex items-center text-sm text-red-600">
                                <i class="fas fa-exclamation-circle {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Enhanced Form Actions -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                <a href="{{ route('admin.categories.index') }}"
                   class="inline-flex items-center px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                    <i class="fas fa-times {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                    {{ trans('all.cancel') }}
                </a>
                <button type="submit"
                        class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm hover:shadow-md transition-all">
                    <i class="fas fa-save {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                    {{ trans('all.Create Category') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
<style>
    .color-preset:hover,
    .icon-preset:hover {
        transform: scale(1.1);
    }

    .color-preset.active {
        ring: 2px;
        ring-color: #6366f1;
        ring-offset: 2px;
    }

    .icon-preset.active {
        background-color: #6366f1 !important;
        color: white !important;
    }

    .form-section {
        animation: slideInUp 0.3s ease-out;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .preview-container {
        background: linear-gradient(135deg, rgba(249, 250, 251, 0.8) 0%, rgba(243, 244, 246, 0.8) 100%);
        backdrop-filter: blur(10px);
    }

    /* RTL specific form styling */
    @if(is_rtl())
    .rtl-form-input {
        direction: rtl;
        text-align: right;
    }

    .rtl-form-input::placeholder {
        text-align: right;
    }
    @endif
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const colorInput = document.getElementById('color');
    const colorText = document.getElementById('color-text');
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('icon-preview');
    const previewName = document.getElementById('preview-name');
    const previewIcon = document.getElementById('preview-icon');
    const previewIconContainer = document.getElementById('preview-icon-container');

    // Real-time preview updates
    nameInput.addEventListener('input', function() {
        previewName.textContent = this.value || '{{ trans('all.category_name') }}';
    });

    colorInput.addEventListener('input', function() {
        const color = this.value;
        colorText.value = color;
        updatePreviewColor(color);
    });

    iconInput.addEventListener('input', function() {
        const iconClass = this.value || 'fas fa-folder';
        updatePreviewIcon(iconClass);
    });

    // Color preset buttons
    document.querySelectorAll('.color-preset').forEach(button => {
        button.addEventListener('click', function() {
            const color = this.dataset.color;
            colorInput.value = color;
            colorText.value = color;
            updatePreviewColor(color);

            // Update active state
            document.querySelectorAll('.color-preset').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Icon preset buttons
    document.querySelectorAll('.icon-preset').forEach(button => {
        button.addEventListener('click', function() {
            const iconClass = this.dataset.icon;
            iconInput.value = iconClass;
            updatePreviewIcon(iconClass);

            // Update active state
            document.querySelectorAll('.icon-preset').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    function updatePreviewColor(color) {
        previewIcon.style.color = color;
        previewIconContainer.style.backgroundColor = color + '20';
    }

    function updatePreviewIcon(iconClass) {
        iconPreview.className = iconClass;
        previewIcon.className = iconClass;
    }

    // Form validation with enhanced error handling
    const form = document.getElementById('categoryForm');
    form.addEventListener('submit', function(e) {
        const name = nameInput.value.trim();
        if (!name) {
            e.preventDefault();
            nameInput.focus();
            showError(nameInput, '{{ trans('all.category_name_required') }}');
            return false;
        }

        // Show loading indicator
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>{{ trans('all.creating') }}...';
        submitBtn.disabled = true;

        // Re-enable if form submission fails
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });

    function showError(input, message) {
        // Remove existing error
        const existingError = input.parentNode.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }

        // Add error class
        input.classList.add('border-red-300', 'ring-2', 'ring-red-200');

        // Add error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'mt-2 flex items-center text-sm text-red-600 error-message';
        errorDiv.innerHTML = `<i class="fas fa-exclamation-circle {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>${message}`;
        input.parentNode.appendChild(errorDiv);

        // Remove error on input
        input.addEventListener('input', function() {
            input.classList.remove('border-red-300', 'ring-2', 'ring-red-200');
            if (errorDiv) {
                errorDiv.remove();
            }
        }, { once: true });
    }

    // Initialize preview
    const initialColor = colorInput.value;
    updatePreviewColor(initialColor);

    // Auto-focus first input
    nameInput.focus();
});
</script>
@endpush

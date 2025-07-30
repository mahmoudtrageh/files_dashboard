{{-- resources/views/admin/pages/categories/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', trans('files.create_category'))

@push('breadcrumbs')
    @php
    $breadcrumbs = [
        ['name' => trans('files.category_management'), 'url' => route('admin.categories.index')],
        ['name' => trans('files.create_category')]
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
                <h1 class="text-2xl font-bold text-gray-900">{{ trans('files.create_category') }}</h1>
                <p class="text-gray-600 mt-1">{{ trans('files.add_new_category_description') }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Form Header -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-edit text-primary-600 {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                    {{ trans('files.category_information') }}
                </h2>
                <div class="flex items-center space-x-2 text-sm text-gray-500 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                    <i class="fas fa-info-circle"></i>
                    <span>{{ trans('files.required_fields_marked') }}</span>
                </div>
            </div>
        </div>

        <!-- Enhanced Form -->
        <form action="{{ route('admin.categories.store') }}" method="POST" class="p-6" id="categoryForm">
            @csrf

            <div class="space-y-8">
                <!-- Basic Information Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Category Name -->
                    <div class="lg:col-span-2">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ trans('files.category_name') }}
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name') }}"
                                   class="block w-full px-4 py-3 {{ is_rtl() ? 'pr-12' : 'pl-4' }} {{ is_rtl() ? 'pl-4' : 'pr-12' }} text-gray-900 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all @error('name') border-red-300 ring-2 ring-red-200 @enderror"
                                   placeholder="{{ trans('files.enter_category_name') }}"
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

                    <!-- Description -->
                    <div class="lg:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ trans('files.description') }}
                            <span class="text-gray-400 text-xs">({{ trans('files.optional') }})</span>
                        </label>
                        <div class="relative">
                            <textarea name="description"
                                      id="description"
                                      rows="4"
                                      class="block w-full px-4 py-3 {{ is_rtl() ? 'pr-12' : 'pl-4' }} {{ is_rtl() ? 'pl-4' : 'pr-12' }} text-gray-900 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 resize-none transition-all @error('description') border-red-300 ring-2 ring-red-200 @enderror"
                                      placeholder="{{ trans('files.enter_category_description') }}"
                                      dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">{{ old('description') }}</textarea>
                            <div class="absolute top-3 {{ is_rtl() ? 'left-3' : 'right-3' }} pointer-events-none">
                                <i class="fas fa-align-left text-gray-400"></i>
                            </div>
                        </div>
                        @error('description')
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
                        {{ trans('files.hierarchy_settings') }}
                    </h3>

                    <div>
                        <label for="parent_id" class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ trans('files.parent_category') }}
                        </label>
                        <div class="relative">
                            <select name="parent_id"
                                    id="parent_id"
                                    class="block w-full px-4 py-3 {{ is_rtl() ? 'pl-10' : 'pr-10' }} text-gray-900 bg-white border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all @error('parent_id') border-red-300 ring-2 ring-red-200 @enderror">
                                <option value="">{{ trans('files.no_parent') }}</option>
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

                <!-- Appearance Section -->
                <div class="border-t border-gray-100 pt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-palette text-primary-600 {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                        {{ trans('files.appearance_settings') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Color Picker -->
                        <div>
                            <label for="color" class="block text-sm font-semibold text-gray-700 mb-3">
                                {{ trans('files.category_color') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-3">
                                <!-- Color Preview -->
                                <div class="flex items-center space-x-3 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                                    <div class="relative">
                                        <input type="color"
                                               name="color"
                                               id="color"
                                               value="{{ old('color', '#6366f1') }}"
                                               class="h-12 w-20 border-2 border-gray-300 rounded-lg cursor-pointer @error('color') border-red-300 @enderror">
                                    </div>
                                    <input type="text"
                                           id="color-text"
                                           value="{{ old('color', '#6366f1') }}"
                                           class="flex-1 px-4 py-3 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg text-sm font-mono"
                                           readonly>
                                </div>

                                <!-- Preset Colors -->
                                <div class="grid grid-cols-8 gap-2">
                                    @foreach(['#ef4444', '#f97316', '#f59e0b', '#10b981', '#06b6d4', '#3b82f6', '#8b5cf6', '#ec4899'] as $presetColor)
                                        <button type="button"
                                                class="w-8 h-8 rounded-lg border-2 border-gray-200 hover:border-gray-400 transition-colors color-preset"
                                                style="background-color: {{ $presetColor }}"
                                                data-color="{{ $presetColor }}"
                                                title="{{ $presetColor }}">
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            @error('color')
                                <div class="mt-2 flex items-center text-sm text-red-600">
                                    <i class="fas fa-exclamation-circle {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Icon Selector -->
                        <div>
                            <label for="icon" class="block text-sm font-semibold text-gray-700 mb-3">
                                {{ trans('files.category_icon') }}
                                <span class="text-gray-400 text-xs">({{ trans('files.optional') }})</span>
                            </label>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                                    <input type="text"
                                           name="icon"
                                           id="icon"
                                           value="{{ old('icon') }}"
                                           class="flex-1 px-4 py-3 text-gray-900 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all @error('icon') border-red-300 ring-2 ring-red-200 @enderror"
                                           placeholder="e.g., fas fa-folder"
                                           dir="ltr">
                                    <div class="flex-shrink-0 w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                        <i id="icon-preview" class="fas fa-folder text-gray-400 text-lg"></i>
                                    </div>
                                </div>

                                <!-- Popular Icons -->
                                <div class="grid grid-cols-8 gap-2">
                                    @foreach(['fas fa-folder', 'fas fa-star', 'fas fa-heart', 'fas fa-bookmark', 'fas fa-tag', 'fas fa-file', 'fas fa-image', 'fas fa-music'] as $iconClass)
                                        <button type="button"
                                                class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center transition-colors icon-preset"
                                                data-icon="{{ $iconClass }}"
                                                title="{{ $iconClass }}">
                                            <i class="{{ $iconClass }} text-gray-600 text-sm"></i>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            @error('icon')
                                <div class="mt-2 flex items-center text-sm text-red-600">
                                    <i class="fas fa-exclamation-circle {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">{{ trans('files.use_fontawesome_classes') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Settings Section -->
                <div class="border-t border-gray-100 pt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-cog text-primary-600 {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                        {{ trans('files.additional_settings') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Sort Order -->
                        <div>
                            <label for="sort_order" class="block text-sm font-semibold text-gray-700 mb-3">
                                {{ trans('files.sort_order') }}
                            </label>
                            <div class="relative">
                                <input type="number"
                                       name="sort_order"
                                       id="sort_order"
                                       value="{{ old('sort_order', 0) }}"
                                       min="0"
                                       class="block w-full px-4 py-3 {{ is_rtl() ? 'pl-12' : 'pr-12' }} text-gray-900 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all @error('sort_order') border-red-300 ring-2 ring-red-200 @enderror">
                                <div class="absolute inset-y-0 {{ is_rtl() ? 'left-0 pl-3' : 'right-0 pr-3' }} flex items-center pointer-events-none">
                                    <i class="fas fa-sort-numeric-up text-gray-400"></i>
                                </div>
                            </div>
                            @error('sort_order')
                                <div class="mt-2 flex items-center text-sm text-red-600">
                                    <i class="fas fa-exclamation-circle {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">{{ trans('files.lower_numbers_appear_first') }}</p>
                        </div>

                        <!-- Active Status -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">{{ trans('files.status') }}</label>
                            <div class="relative">
                                <label class="inline-flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-100 transition-colors">
                                    <input type="checkbox"
                                           name="is_active"
                                           value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}
                                           class="h-5 w-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500 focus:ring-2">
                                    <div class="{{ is_rtl() ? 'mr-3' : 'ml-3' }}">
                                        <div class="text-sm font-medium text-gray-900">{{ trans('files.active') }}</div>
                                        <div class="text-xs text-gray-500">{{ trans('files.category_will_be_visible') }}</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="border-t border-gray-100 pt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-eye text-primary-600 {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                        {{ trans('files.preview') }}
                    </h3>

                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl border border-gray-200">
                        <div id="category-preview" class="flex items-center space-x-4 p-4 bg-white rounded-lg shadow-sm border border-gray-200 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                            <div class="flex-shrink-0 h-12 w-12 rounded-full flex items-center justify-center transition-all"
                                 id="preview-icon-container" style="background-color: #6366f120;">
                                <i id="preview-icon" class="fas fa-folder transition-all" style="color: #6366f1;"></i>
                            </div>
                            <div class="flex-1">
                                <div id="preview-name" class="font-semibold text-gray-900">{{ trans('files.category_name') }}</div>
                                <div class="text-sm text-gray-500 mt-1">0 {{ trans('files.files') }}</div>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ trans('files.active') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Form Actions -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                <a href="{{ route('admin.categories.index') }}"
                   class="inline-flex items-center px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                    <i class="fas fa-times {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                    {{ trans('files.cancel') }}
                </a>
                <button type="submit"
                        class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm hover:shadow-md transition-all">
                    <i class="fas fa-save {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                    {{ trans('files.create_category') }}
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
        previewName.textContent = this.value || '{{ trans('files.category_name') }}';
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
            showError(nameInput, '{{ trans('files.category_name_required') }}');
            return false;
        }

        // Show loading indicator
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>{{ trans('files.creating') }}...';
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

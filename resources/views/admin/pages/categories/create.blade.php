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
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-xl font-semibold text-gray-900">{{ trans('files.create_category') }}</h1>
            <p class="text-sm text-gray-600 mt-1">{{ trans('files.add_new_category') }}</p>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.categories.store') }}" method="POST" class="p-6">
            @csrf

            <div class="space-y-6">
                <!-- Category Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ trans('files.category_name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-500 @enderror"
                           placeholder="{{ trans('files.enter_category_name') }}"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ trans('files.description') }}
                    </label>
                    <textarea name="description"
                              id="description"
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('description') border-red-500 @enderror"
                              placeholder="{{ trans('files.enter_category_description') }}">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Parent Category -->
                <div>
                    <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ trans('files.parent_category') }}
                    </label>
                    <select name="parent_id"
                            id="parent_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('parent_id') border-red-500 @enderror">
                        <option value="">{{ trans('files.no_parent') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                    {{ old('parent_id') == $category->id ? 'selected' : '' }}
                                    style="padding-left: {{ $category->level * 20 }}px;">
                                {{ str_repeat('—', $category->level) }} {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Color and Icon Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Color -->
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ trans('files.category_color') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="color"
                                   name="color"
                                   id="color"
                                   value="{{ old('color', '#6366f1') }}"
                                   class="h-10 w-16 border border-gray-300 rounded-md @error('color') border-red-500 @enderror">
                            <input type="text"
                                   id="color-text"
                                   value="{{ old('color', '#6366f1') }}"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm"
                                   readonly>
                        </div>
                        @error('color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Icon -->
                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ trans('files.category_icon') }}
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="text"
                                   name="icon"
                                   id="icon"
                                   value="{{ old('icon') }}"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('icon') border-red-500 @enderror"
                                   placeholder="e.g., fas fa-folder">
                            <div class="h-10 w-10 bg-gray-100 rounded-md flex items-center justify-center">
                                <i id="icon-preview" class="fas fa-folder text-gray-400"></i>
                            </div>
                        </div>
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Use FontAwesome class names (optional)</p>
                    </div>
                </div>

                <!-- Sort Order and Status Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ trans('files.sort_order') }}
                        </label>
                        <input type="number"
                               name="sort_order"
                               id="sort_order"
                               value="{{ old('sort_order', 0) }}"
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('sort_order') border-red-500 @enderror">
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ trans('files.status') }}</label>
                        <div class="flex items-center h-10">
                            <label class="flex items-center">
                                <input type="checkbox"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">{{ trans('files.active') }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Preview -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">{{ trans('files.view') }}</h4>
                    <div id="category-preview" class="flex items-center space-x-3 p-3 bg-white rounded-lg">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center"
                             id="preview-icon-container" style="background-color: #6366f120;">
                            <i id="preview-icon" class="fas fa-folder" style="color: #6366f1;"></i>
                        </div>
                        <div>
                            <div id="preview-name" class="font-medium text-gray-900">{{ trans('files.category_name') }}</div>
                            <div class="text-sm text-gray-500">0 {{ trans('files.files') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.categories.index') }}"
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    {{ trans('files.cancel') }}
                </a>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    {{ trans('files.create_category') }}
                </button>
            </div>
        </form>
    </div>
</div>

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

    // Update preview when name changes
    nameInput.addEventListener('input', function() {
        previewName.textContent = this.value || '{{ trans('files.category_name') }}';
    });

    // Update preview when color changes
    colorInput.addEventListener('input', function() {
        const color = this.value;
        colorText.value = color;
        previewIcon.style.color = color;
        previewIconContainer.style.backgroundColor = color + '20';
    });

    // Update preview when icon changes
    iconInput.addEventListener('input', function() {
        const iconClass = this.value || 'fas fa-folder';
        iconPreview.className = iconClass;
        previewIcon.className = iconClass;
    });

    // Initialize preview
    const initialColor = colorInput.value;
    previewIcon.style.color = initialColor;
    previewIconContainer.style.backgroundColor = initialColor + '20';
});
</script>
@endsection

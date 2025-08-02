{{-- resources/views/admin/pages/categories/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', trans('all.Edit Category'))

@push('breadcrumbs')
    @php
    $breadcrumbs = [
        ['name' => trans('all.Category Management'), 'url' => route('admin.categories.index')],
        ['name' => $category->name, 'url' => route('admin.categories.show', $category)],
        ['name' => trans('all.edit')]
    ];
    @endphp
@endpush

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">{{ trans('all.Edit Category') }}</h1>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-8 rounded-full flex items-center justify-center"
                         style="background-color: {{ $category->color }}20;">
                        <i class="{{ $category->icon ?: 'fas fa-folder' }}" style="color: {{ $category->color }}"></i>
                    </div>
                    <span class="font-medium text-gray-900">{{ $category->name }}</span>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Category Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ trans('all.Category Name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $category->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-500 @enderror"
                           placeholder="{{ trans('all.Enter category name') }}"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Parent Category -->
                <div>
                    <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ trans('all.Parent Category') }}
                    </label>
                    <select name="parent_id"
                            id="parent_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('parent_id') border-red-500 @enderror">
                        <option value="">{{ trans('all.Select Parent Category Optional') }}</option>
                        @foreach($categories as $cat)
                            @if($cat->id !== $category->id) {{-- Prevent self-selection --}}
                                <option value="{{ $cat->id }}"
                                        {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}
                                        style="padding-left: {{ $cat->level * 20 }}px;">
                                    {{ str_repeat('—', $cat->level) }} {{ $cat->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                </div>

                <!-- Sort Order and Status Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ trans('all.Sort Order') }}
                        </label>
                        <input type="number"
                               name="sort_order"
                               id="sort_order"
                               value="{{ old('sort_order', $category->sort_order) }}"
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('sort_order') border-red-500 @enderror">
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ trans('all.status') }}</label>
                        <div class="flex items-center h-10">
                            <label class="flex items-center">
                                <input type="checkbox"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">{{ trans('all.active') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.categories.show', $category) }}"
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        {{ trans('all.view') }}
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        {{ trans('all.cancel') }}
                    </a>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        {{ trans('all.Update Category') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Form (hidden) -->
<form id="delete-form" action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

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
        previewName.textContent = this.value || '{{ $category->name }}';
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
        iconPreview.className = iconClass + ' text-gray-400';
        previewIcon.className = iconClass;
    });
});

function deleteCategory() {
    if (confirm('{{ trans('all.Are you sure you want to delete this category?') }}\n{{ trans('all.This action cannot be undone.') }}')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection

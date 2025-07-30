{{-- resources/views/admin/pages/categories/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('Category Management'))

@push('breadcrumbs')
    @php
    $breadcrumbs = [
        ['name' => __('Category Management')]
    ];
    @endphp
@endpush

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('Category Management') }}</h1>
                <p class="text-gray-600">{{ __('Organize your files with a structured category system') }}</p>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary-600">{{ $statistics['total_categories'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">{{ __('Total Categories') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $statistics['root_categories'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">{{ __('Root Categories') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $statistics['categories_with_files'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">{{ __('With Files') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-600">{{ $statistics['empty_categories'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">{{ __('Empty') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Most Used Categories -->
    @if(isset($statistics['most_used_categories']) && $statistics['most_used_categories']->isNotEmpty())
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Most Used Categories') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($statistics['most_used_categories'] as $category)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center"
                             style="background-color: {{ $category->color ?? '#6366f1' }}20;">
                            @if($category->icon)
                                <i class="{{ $category->icon }}" style="color: {{ $category->color ?? '#6366f1' }}"></i>
                            @else
                                <i class="fas fa-folder" style="color: {{ $category->color ?? '#6366f1' }}"></i>
                            @endif
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">{{ $category->name }}</div>
                            <div class="text-sm text-gray-500">{{ $category->files_count }} {{ __('files.files') }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <a href="{{ route('admin.categories.show', ['category' => $category->id]) }}"
                           class="text-primary-600 hover:text-primary-700">
                            <i class="fas fa-arrow-{{ is_rtl() ? 'left' : 'right' }}"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Quick Actions') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('admin.categories.create') }}"
               class="flex items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-primary-400 hover:bg-primary-50 transition-colors">
                <div class="text-center">
                    <i class="fas fa-plus text-2xl text-gray-400 mb-2"></i>
                    <div class="font-medium text-gray-900">{{ __('Create Category') }}</div>
                    <div class="text-sm text-gray-500">{{ __('Add a new category') }}</div>
                </div>
            </a>

            <a href="{{ route('admin.files.index') }}"
               class="flex items-center justify-center p-4 border border-gray-300 rounded-lg hover:border-primary-400 hover:bg-primary-50 transition-colors">
                <div class="text-center">
                    <i class="fas fa-folder-open text-2xl text-gray-400 mb-2"></i>
                    <div class="font-medium text-gray-900">{{ __('Browse Files') }}</div>
                    <div class="text-sm text-gray-500">{{ __('View all files') }}</div>
                </div>
            </a>
        </div>
    </div>

    <!-- Categories Tree -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('All Categories') }}</h2>
                <div class="flex items-center space-x-2">
                    <input type="text"
                           id="search-categories"
                           placeholder="{{ __('Search categories...') }}"
                           class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <button onclick="toggleTreeView()"
                            class="px-3 py-2 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200">
                        <i class="fas fa-list" id="view-toggle-icon"></i>
                    </button>
                </div>
            </div>

            <div id="categories-container" class="space-y-2">
                <div class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
                    <span class="ml-2 text-gray-600">{{ __('Loading categories...') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let categoriesData = [];
let isTreeView = true;

document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
    setupSearch();
});

function loadCategories() {
    fetch('{{ route("admin.categories.tree") }}')
        .then(response => response.json())
        .then(data => {
            categoriesData = data;
            renderCategories(data);
        })
        .catch(error => {
            console.error('Error loading categories:', error);
            document.getElementById('categories-container').innerHTML =
                '<div class="text-center py-8"><p class="text-gray-500">{{ __("Error loading categories") }}</p></div>';
        });
}

function renderCategories(categories) {
    const container = document.getElementById('categories-container');
    if (!categories || categories.length === 0) {
        container.innerHTML = '<div class="text-center py-8"><p class="text-gray-500">{{ __("No categories found") }}</p></div>';
        return;
    }

    let html = '<div class="space-y-1">';
    categories.forEach(category => {
        html += renderCategory(category, 0);
    });
    html += '</div>';

    container.innerHTML = html;
}

function renderCategory(category, level) {
    const indent = isTreeView ? level * 20 : 0;
    const hasChildren = category.children && category.children.length > 0;

    let html = `
        <div class="category-item" data-id="${category.id}" data-name="${category.name.toLowerCase()}">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
                 style="margin-left: ${indent}px;">
                <div class="flex items-center space-x-3">
                    ${hasChildren && isTreeView ? `
                        <button onclick="toggleCategory(${category.id})" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-chevron-right category-toggle" id="toggle-${category.id}"></i>
                        </button>
                    ` : '<div class="w-4"></div>'}

                    <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center"
                         style="background-color: ${category.color || '#6366f1'}20;">
                        <i class="${category.icon || 'fas fa-folder'}" style="color: ${category.color || '#6366f1'}"></i>
                    </div>
                    <div>
                        <div class="font-medium text-gray-900">${category.name}</div>
                        <div class="text-sm text-gray-500">${category.files_count || 0} files</div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="/dashboard/categories/${category.id}"
                       class="text-blue-600 hover:text-blue-700 text-sm px-2 py-1 rounded hover:bg-blue-50">
                       {{ __('View') }}
                    </a>
                    <a href="/dashboard/categories/${category.id}/edit"
                       class="text-green-600 hover:text-green-700 text-sm px-2 py-1 rounded hover:bg-green-50">
                       {{ __('Edit') }}
                    </a>
                    <button onclick="deleteCategory(${category.id}, '${category.name}')"
                            class="text-red-600 hover:text-red-700 text-sm px-2 py-1 rounded hover:bg-red-50">
                        {{ __('Delete') }}
                    </button>
                </div>
            </div>

            ${hasChildren && isTreeView ? `
                <div class="category-children hidden" id="children-${category.id}">
                    ${category.children.map(child => renderCategory(child, level + 1)).join('')}
                </div>
            ` : ''}
        </div>
    `;

    return html;
}

function toggleCategory(categoryId) {
    const children = document.getElementById(`children-${categoryId}`);
    const toggle = document.getElementById(`toggle-${categoryId}`);

    if (children.classList.contains('hidden')) {
        children.classList.remove('hidden');
        toggle.classList.remove('fa-chevron-right');
        toggle.classList.add('fa-chevron-down');
    } else {
        children.classList.add('hidden');
        toggle.classList.remove('fa-chevron-down');
        toggle.classList.add('fa-chevron-right');
    }
}

function toggleTreeView() {
    isTreeView = !isTreeView;
    const icon = document.getElementById('view-toggle-icon');

    if (isTreeView) {
        icon.className = 'fas fa-list';
    } else {
        icon.className = 'fas fa-sitemap';
    }

    renderCategories(categoriesData);
}

function setupSearch() {
    const searchInput = document.getElementById('search-categories');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const query = this.value.toLowerCase();
            filterCategories(query);
        }, 300);
    });
}

function filterCategories(query) {
    const items = document.querySelectorAll('.category-item');

    items.forEach(item => {
        const name = item.dataset.name;
        if (name.includes(query)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function deleteCategory(id, name) {
    if (confirm(`{{ __('Are you sure you want to delete the category') }} "${name}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/categories/${id}`;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection

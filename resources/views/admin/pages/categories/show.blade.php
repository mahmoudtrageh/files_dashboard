{{-- resources/views/admin/pages/categories/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', $category->name)

@push('breadcrumbs')
    @php
    $breadcrumbs = [
        ['name' => __('Category Management'), 'url' => route('admin.categories.index')]
    ];

    // Add breadcrumb trail for category hierarchy
    if ($category->parent) {
        $ancestors = [];
        $current = $category->parent;
        while ($current) {
            array_unshift($ancestors, $current);
            $current = $current->parent;
        }

        foreach ($ancestors as $ancestor) {
            $breadcrumbs[] = ['name' => $ancestor->name, 'url' => route('admin.categories.show', $ancestor)];
        }
    }

    $breadcrumbs[] = ['name' => $category->name];
    @endphp
@endpush

@section('content')
<div class="space-y-6">
    <!-- Category Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 h-16 w-16 rounded-xl flex items-center justify-center"
                     style="background-color: {{ $category->color }}20;">
                    <i class="{{ $category->icon ?: 'fas fa-folder' }} text-2xl" style="color: {{ $category->color }}"></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center space-x-3">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
                        @if(!$category->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ __('Inactive') }}
                            </span>
                        @endif
                    </div>

                    @if($category->description)
                        <p class="text-gray-600 mt-1">{{ $category->description }}</p>
                    @endif

                    <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                        <span>{{ __('Created') }}: {{ $category->created_at->format('M j, Y') }}</span>
                        <span>•</span>
                        <span>{{ __('Updated') }}: {{ $category->updated_at->format('M j, Y') }}</span>
                        @if($category->parent)
                            <span>•</span>
                            <span>{{ __('Parent') }}:
                                <a href="{{ route('admin.categories.show', $category->parent) }}"
                                   class="text-primary-600 hover:text-primary-700">
                                    {{ $category->parent->name }}
                                </a>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.categories.edit', $category) }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <i class="fas fa-edit mr-2"></i>
                    {{ __('Edit') }}
                </a>

                @if($category->files()->count() === 0)
                    <button onclick="deleteCategory()"
                            class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-trash mr-2"></i>
                        {{ __('Delete') }}
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Category Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 bg-blue-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-file text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $category->files()->count() }}</div>
                    <div class="text-sm text-gray-500">{{ __('Direct Files') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 bg-green-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-sitemap text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $category->children()->count() }}</div>
                    <div class="text-sm text-gray-500">{{ __('Subcategories') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 bg-purple-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-layer-group text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $category->getAllFiles()->count() }}</div>
                    <div class="text-sm text-gray-500">{{ __('Total Files') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 bg-yellow-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-sort-numeric-up text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $category->sort_order }}</div>
                    <div class="text-sm text-gray-500">{{ __('Sort Order') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subcategories -->
    @if($category->children->isNotEmpty())
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">{{ __('Subcategories') }}</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($category->children->sortBy('sort_order') as $child)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center"
                                 style="background-color: {{ $child->color }}20;">
                                <i class="{{ $child->icon ?: 'fas fa-folder' }}" style="color: {{ $child->color }}"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">{{ $child->name }}</div>
                                <div class="text-sm text-gray-500">{{ $child->files()->count() }} {{ __('files') }}</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.categories.show', $child) }}"
                               class="text-primary-600 hover:text-primary-700 text-sm">
                                {{ __('View') }}
                            </a>
                            <a href="{{ route('admin.categories.edit', $child) }}"
                               class="text-green-600 hover:text-green-700 text-sm">
                                {{ __('Edit') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Files in Category -->
    @if($files && $files->count() > 0)
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('Files in Category') }}</h2>
                <a href="{{ route('admin.files.index', ['category' => $category->id]) }}"
                   class="text-primary-600 hover:text-primary-700 text-sm">
                    {{ __('View All Files') }}
                </a>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($files->take(6) as $file)
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0">
                            @if($file->isImage())
                                <img src="{{ $file->thumbnail_url }}"
                                     alt="{{ $file->name }}"
                                     class="h-10 w-10 rounded object-cover">
                            @else
                                <div class="h-10 w-10 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-file text-gray-500"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-900 truncate">{{ $file->name }}</div>
                            <div class="text-sm text-gray-500">{{ $file->formatted_size }}</div>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('admin.files.show', $file) }}"
                               class="text-primary-600 hover:text-primary-700">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($files->count() > 6)
                <div class="mt-4 text-center">
                    <a href="{{ route('admin.files.index', ['category' => $category->id]) }}"
                       class="text-primary-600 hover:text-primary-700 text-sm">
                        {{ __('View :count more files', ['count' => $files->count() - 6]) }}
                    </a>
                </div>
            @endif
        </div>
    </div>
    @else
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">{{ __('Files') }}</h2>
        </div>
        <div class="p-6">
            <div class="text-center py-8">
                <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">{{ __('No files in this category yet') }}</p>
                <a href="{{ route('admin.files.create', ['category' => $category->id]) }}"
                   class="mt-2 inline-flex items-center text-primary-600 hover:text-primary-700">
                    <i class="fas fa-plus mr-1"></i>
                    {{ __('Add Files') }}
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Quick Actions') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.categories.create', ['parent_id' => $category->id]) }}"
               class="flex items-center justify-center p-4 bg-white border border-gray-300 rounded-lg hover:border-primary-400 hover:bg-primary-50 transition-colors">
                <div class="text-center">
                    <i class="fas fa-plus text-2xl text-gray-400 mb-2"></i>
                    <div class="font-medium text-gray-900">{{ __('Add Subcategory') }}</div>
                    <div class="text-sm text-gray-500">{{ __('Create child category') }}</div>
                </div>
            </a>

            <a href="{{ route('admin.files.create', ['category' => $category->id]) }}"
               class="flex items-center justify-center p-4 bg-white border border-gray-300 rounded-lg hover:border-primary-400 hover:bg-primary-50 transition-colors">
                <div class="text-center">
                    <i class="fas fa-upload text-2xl text-gray-400 mb-2"></i>
                    <div class="font-medium text-gray-900">{{ __('Upload Files') }}</div>
                    <div class="text-sm text-gray-500">{{ __('Add files to category') }}</div>
                </div>
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center justify-center p-4 bg-white border border-gray-300 rounded-lg hover:border-primary-400 hover:bg-primary-50 transition-colors">
                <div class="text-center">
                    <i class="fas fa-arrow-left text-2xl text-gray-400 mb-2"></i>
                    <div class="font-medium text-gray-900">{{ __('Back to Categories') }}</div>
                    <div class="text-sm text-gray-500">{{ __('Return to main page') }}</div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Delete Form (hidden) -->
<form id="delete-form" action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteCategory() {
    if (confirm('{{ __('Are you sure you want to delete this category?') }}\n{{ __('This action cannot be undone.') }}')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection

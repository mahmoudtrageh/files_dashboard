{{-- resources/views/admin/components/category-list.blade.php --}}
@php
    $categories = $categories ?? collect();
    $layout = $layout ?? 'grid'; // 'grid', 'list', 'compact'
    $showActions = $showActions ?? true;
    $showStats = $showStats ?? true;
    $showDescription = $showDescription ?? true;
@endphp

@if($categories->isEmpty())
    <div class="text-center py-8">
        <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('No Categories') }}</h3>
        <p class="text-gray-500">{{ __('No categories found matching your criteria.') }}</p>
    </div>
@else
    @if($layout === 'grid')
        <!-- Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
                <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 h-12 w-12 rounded-lg flex items-center justify-center"
                                 style="background-color: {{ $category->color }}20;">
                                <i class="{{ $category->icon ?: 'fas fa-folder' }} text-xl" style="color: {{ $category->color }}"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900">{{ $category->name }}</h3>
                                @if($showStats)
                                    <div class="flex items-center space-x-4 text-sm text-gray-500 mt-1">
                                        <span>{{ $category->files_count ?? $category->files()->count() }} files</span>
                                        @if($category->children()->count() > 0)
                                            <span>{{ $category->children()->count() }} subcategories</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($showActions)
                            <div class="flex items-center space-x-1">
                                <a href="{{ route('admin.categories.show', $category) }}"
                                   class="text-gray-400 hover:text-gray-600 p-1">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="text-gray-400 hover:text-gray-600 p-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        @endif
                    </div>

                    @if($showDescription && $category->description)
                        <p class="text-gray-600 text-sm mt-3 line-clamp-2">{{ $category->description }}</p>
                    @endif

                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            @if(!$category->is_active)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ __('Inactive') }}
                                </span>
                            @endif
                            @if($category->parent)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $category->parent->name }}
                                </span>
                            @endif
                        </div>

                        <div class="text-xs text-gray-500">
                            {{ $category->updated_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @elseif($layout === 'list')
        <!-- List Layout -->
        <div class="space-y-4">
            @foreach($categories as $category)
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 h-10 w-10 rounded-lg flex items-center justify-center"
                                 style="background-color: {{ $category->color }}20;">
                                <i class="{{ $category->icon ?: 'fas fa-folder' }}" style="color: {{ $category->color }}"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-2">
                                    <h3 class="text-base font-medium text-gray-900">{{ $category->name }}</h3>
                                    @if(!$category->is_active)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ __('Inactive') }}
                                        </span>
                                    @endif
                                </div>
                                @if($showDescription && $category->description)
                                    <p class="text-gray-600 text-sm mt-1">{{ $category->description }}</p>
                                @endif
                                @if($showStats)
                                    <div class="flex items-center space-x-4 text-sm text-gray-500 mt-1">
                                        <span>{{ $category->files_count ?? $category->files()->count() }} files</span>
                                        @if($category->children()->count() > 0)
                                            <span>{{ $category->children()->count() }} subcategories</span>
                                        @endif
                                        @if($category->parent)
                                            <span>Parent: {{ $category->parent->name }}</span>
                                        @endif
                                        <span>Updated {{ $category->updated_at->diffForHumans() }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($showActions)
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.categories.show', $category) }}"
                                   class="text-blue-600 hover:text-blue-700 text-sm px-2 py-1 rounded hover:bg-blue-50">
                                    {{ __('View') }}
                                </a>
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="text-green-600 hover:text-green-700 text-sm px-2 py-1 rounded hover:bg-green-50">
                                    {{ __('Edit') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

    @else
        <!-- Compact Layout -->
        <div class="space-y-2">
            @foreach($categories as $category)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center"
                             style="background-color: {{ $category->color }}20;">
                            <i class="{{ $category->icon ?: 'fas fa-folder' }}" style="color: {{ $category->color }}"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">{{ $category->name }}</div>
                            @if($showStats)
                                <div class="text-sm text-gray-500">{{ $category->files_count ?? $category->files()->count() }} files</div>
                            @endif
                        </div>
                    </div>

                    @if($showActions)
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.categories.show', $category) }}"
                               class="text-blue-600 hover:text-blue-700 text-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.categories.edit', $category) }}"
                               class="text-green-600 hover:text-green-700 text-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
@endif

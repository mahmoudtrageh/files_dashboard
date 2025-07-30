<div class="space-y-6">
    <!-- Enhanced Header with Actions -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-folder-tree text-primary-600 {{ is_rtl() ? 'ml-3' : 'mr-3' }}"></i>
                {{ trans('files.file_manager') }}
            </h2>
            <p class="text-sm text-gray-600 mt-1">{{ trans('files.manage_organize_files') }}</p>
        </div>

        <div class="flex items-center space-x-3 {{ is_rtl() ? 'space-x-reverse' : '' }}">
            <!-- Enhanced View Mode Toggle -->
            <div class="flex rounded-lg bg-gray-100 p-1 border border-gray-200">
                <button
                    wire:click="$set('viewMode', 'grid')"
                    class="px-4 py-2 text-sm font-medium rounded-md transition-all duration-200 {{ $viewMode === 'grid' ? 'bg-white text-primary-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}"
                    title="{{ trans('files.grid_view') }}">
                    <i class="fas fa-th-large"></i>
                    <span class="hidden sm:inline {{ is_rtl() ? 'mr-2' : 'ml-2' }}">{{ trans('files.grid_view') }}</span>
                </button>
                <button
                    wire:click="$set('viewMode', 'list')"
                    class="px-4 py-2 text-sm font-medium rounded-md transition-all duration-200 {{ $viewMode === 'list' ? 'bg-white text-primary-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}"
                    title="{{ trans('files.list_view') }}">
                    <i class="fas fa-list"></i>
                    <span class="hidden sm:inline {{ is_rtl() ? 'mr-2' : 'ml-2' }}">{{ trans('files.list_view') }}</span>
                </button>
            </div>

            <!-- Upload Button with Enhanced Design -->
            <button
                wire:click="showUploadForm"
                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                <i class="fas fa-cloud-upload-alt {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                {{ trans('files.upload_file') }}
            </button>
        </div>
    </div>

    <!-- Enhanced Filters Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Enhanced Search -->
            <div>
                <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-search {{ is_rtl() ? 'ml-1' : 'mr-1' }} text-primary-600"></i>
                    {{ trans('files.search') }}
                </label>
                <div class="relative">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        class="block w-full pl-10 pr-4 py-3 text-gray-900 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all"
                        placeholder="{{ trans('files.search_files') }}"
                    >
                    <div class="absolute inset-y-0 {{ is_rtl() ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    @if($search)
                        <button
                            wire:click="$set('search', '')"
                            class="absolute inset-y-0 {{ is_rtl() ? 'left-0 pl-3' : 'right-0 pr-3' }} flex items-center">
                            <i class="fas fa-times text-gray-400 hover:text-gray-600 transition-colors"></i>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Enhanced Category Filter -->
            <div>
                <label for="category-filter" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-sitemap {{ is_rtl() ? 'ml-1' : 'mr-1' }} text-primary-600"></i>
                    {{ trans('files.category') }}
                </label>
                <div class="relative">
                    <select
                        wire:model.live="categoryFilter"
                        class="block w-full px-4 py-3 pr-10 text-gray-900 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                        <option value="">{{ trans('files.all_categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category['id'] }}">
                                {{ str_repeat('— ', $category['level']) }}{{ $category['name'] }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 {{ is_rtl() ? 'left-0 pl-3' : 'right-0 pr-3' }} flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Enhanced Type Filter -->
            <div>
                <label for="type-filter" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-file-alt {{ is_rtl() ? 'ml-1' : 'mr-1' }} text-primary-600"></i>
                    {{ trans('files.file_type') }}
                </label>
                <div class="relative">
                    <select
                        wire:model.live="typeFilter"
                        class="block w-full px-4 py-3 pr-10 text-gray-900 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                        @foreach($fileTypes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 {{ is_rtl() ? 'left-0 pl-3' : 'right-0 pr-3' }} flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Enhanced Per Page -->
            <div>
                <label for="per-page" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-list-ol {{ is_rtl() ? 'ml-1' : 'mr-1' }} text-primary-600"></i>
                    {{ trans('files.per_page') }}
                </label>
                <div class="relative">
                    <select
                        wire:model.live="perPage"
                        class="block w-full px-4 py-3 pr-10 text-gray-900 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                        <option value="15">15</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <div class="absolute inset-y-0 {{ is_rtl() ? 'left-0 pl-3' : 'right-0 pr-3' }} flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Quick Actions -->
        <div class="mt-6 flex items-center justify-between pt-4 border-t border-gray-100">
            <div class="flex items-center space-x-4 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                <button
                    wire:click="resetFilters"
                    class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 font-medium transition-colors">
                    <i class="fas fa-undo {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                    {{ trans('files.reset_filters') }}
                </button>

                @if($search || $categoryFilter || $typeFilter !== 'all')
                    <span class="text-sm text-gray-500">
                        {{ trans('files.showing_filtered_results') }}
                    </span>
                @endif
            </div>

            @if($showBulkActions)
                <div class="flex items-center space-x-3 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                    <div class="relative">
                        <select
                            wire:model="bulkAction"
                            class="text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                            <option value="">{{ trans('files.bulk_actions') }}</option>
                            <option value="delete">{{ trans('files.delete') }}</option>
                            <option value="move">{{ trans('files.move_to_category') }}</option>
                            <option value="public">{{ trans('files.make_public') }}</option>
                            <option value="private">{{ trans('files.make_private') }}</option>
                        </select>
                    </div>

                    @if($bulkAction === 'move')
                        <div class="relative">
                            <select
                                wire:model="bulkMoveCategory"
                                class="text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                                <option value="">{{ trans('files.select_category') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <button
                        wire:click="executeBulkAction"
                        class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm rounded-lg transition-colors {{ empty($bulkAction) ? 'opacity-50 cursor-not-allowed' : '' }}"
                        @if(empty($bulkAction)) disabled @endif>
                        {{ trans('files.apply') }}
                    </button>

                    <span class="text-sm text-gray-600">
                        {{ count($selectedFiles) }} {{ trans('files.selected') }}
                    </span>
                </div>
            @endif
        </div>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="flex justify-center items-center py-8">
        <div class="flex items-center space-x-3 {{ is_rtl() ? 'space-x-reverse' : '' }}">
            <div class="animate-spin rounded-full h-6 w-6 border-2 border-primary-600 border-t-transparent"></div>
            <span class="text-sm text-gray-600 font-medium">{{ trans('files.loading') }}</span>
        </div>
    </div>

    <!-- Enhanced Files Display -->
    <div wire:loading.class="opacity-50 pointer-events-none">
        @if($viewMode === 'grid')
            <!-- Enhanced Grid View -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                @forelse($files as $file)
                    <div class="group bg-white rounded-xl shadow-sm hover:shadow-lg border border-gray-200 hover:border-primary-200 transition-all duration-200 overflow-hidden">
                        <!-- File Preview -->
                        <div class="aspect-square bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center relative overflow-hidden">
                            <!-- Checkbox -->
                            <div class="absolute top-3 {{ is_rtl() ? 'left-3' : 'right-3' }} z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                                <label class="flex items-center">
                                    <input
                                        type="checkbox"
                                        wire:model.live="selectedFiles"
                                        value="{{ $file->id }}"
                                        class="h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 shadow-sm">
                                </label>
                            </div>

                            @if($file->is_image)
                                <img
                                    src="{{ $file->file_url }}"
                                    alt="{{ $file->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    loading="lazy">
                            @else
                                <div class="text-center p-4">
                                    <i class="{{ $file->file_icon }} text-5xl text-gray-400 mb-3 group-hover:text-primary-500 transition-colors"></i>
                                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">{{ $file->extension }}</p>
                                </div>
                            @endif

                            <!-- Overlay Actions -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <div class="flex items-center space-x-2 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                                    <button
                                        wire:click="downloadFile({{ $file->id }})"
                                        class="p-2 bg-white text-gray-700 rounded-full hover:bg-gray-100 transition-colors shadow-md"
                                        title="{{ trans('files.download') }}">
                                        <i class="fas fa-download text-sm"></i>
                                    </button>
                                    <button
                                        wire:click="editFile({{ $file->id }})"
                                        class="p-2 bg-white text-gray-700 rounded-full hover:bg-gray-100 transition-colors shadow-md"
                                        title="{{ trans('files.edit') }}">
                                        <i class="fas fa-edit text-sm"></i>
                                    </button>
                                    <button
                                        wire:click="deleteFile({{ $file->id }})"
                                        onclick="return confirm('{{ trans('files.are_you_sure') }}')"
                                        class="p-2 bg-white text-red-600 rounded-full hover:bg-red-50 transition-colors shadow-md"
                                        title="{{ trans('files.delete') }}">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Enhanced File Info -->
                        <div class="p-4">
                            <h3 class="font-semibold text-sm text-gray-900 truncate group-hover:text-primary-600 transition-colors"
                                title="{{ $file->title }}">
                                {{ $file->title }}
                            </h3>
                            <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                                <span class="flex items-center">
                                    <i class="fas fa-weight-hanging {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ $file->human_readable_size }}
                                </span>
                                <div class="flex items-center">
                                    @if($file->is_public)
                                        <i class="fas fa-eye text-green-500" title="{{ trans('files.public') }}"></i>
                                    @else
                                        <i class="fas fa-eye-slash text-gray-400" title="{{ trans('files.private') }}"></i>
                                    @endif
                                </div>
                            </div>
                            @if($file->category)
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                          style="background-color: {{ $file->category->color }}20; color: {{ $file->category->color }}">
                                        @if($file->category->icon)
                                            <i class="{{ $file->category->icon }} {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                                        @endif
                                        {{ $file->category->name }}
                                    </span>
                                </div>
                            @endif
                            <div class="mt-2 text-xs text-gray-400">
                                {{ $file->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="text-center py-16">
                            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-folder-open text-4xl text-gray-300"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ trans('files.no_files_found') }}</h3>
                            <p class="text-gray-500 mb-4">{{ trans('files.try_different_search') }}</p>
                            <button
                                wire:click="showUploadForm"
                                class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-plus {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                                {{ trans('files.upload_first_file') }}
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>
        @else
            <!-- Enhanced List View -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-{{ is_rtl() ? 'right' : 'left' }} w-12">
                                    <input
                                        type="checkbox"
                                        wire:model.live="selectAll"
                                        class="h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                </th>
                                <th
                                    wire:click="sortBy('title')"
                                    class="px-6 py-4 text-{{ is_rtl() ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700 transition-colors">
                                    <div class="flex items-center space-x-1 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                                        <span>{{ trans('files.title') }}</span>
                                        @if($sortBy === 'title')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-300"></i>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-{{ is_rtl() ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans('files.category') }}
                                </th>
                                <th
                                    wire:click="sortBy('size')"
                                    class="px-6 py-4 text-{{ is_rtl() ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700 transition-colors">
                                    <div class="flex items-center space-x-1 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                                        <span>{{ trans('files.size') }}</span>
                                        @if($sortBy === 'size')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-300"></i>
                                        @endif
                                    </div>
                                </th>
                                <th
                                    wire:click="sortBy('created_at')"
                                    class="px-6 py-4 text-{{ is_rtl() ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700 transition-colors">
                                    <div class="flex items-center space-x-1 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                                        <span>{{ trans('files.uploaded') }}</span>
                                        @if($sortBy === 'created_at')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-300"></i>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-{{ is_rtl() ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans('files.actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($files as $file)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <input
                                            type="checkbox"
                                            wire:model.live="selectedFiles"
                                            value="{{ $file->id }}"
                                            class="h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                                            <div class="flex-shrink-0">
                                                @if($file->is_image)
                                                    <img src="{{ $file->file_url }}"
                                                         alt="{{ $file->title }}"
                                                         class="h-10 w-10 rounded-lg object-cover">
                                                @else
                                                    <div class="h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                                        <i class="{{ $file->file_icon }} text-gray-500"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="font-medium text-gray-900 truncate">{{ $file->title }}</div>
                                                <div class="text-sm text-gray-500">{{ $file->original_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($file->category)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                  style="background-color: {{ $file->category->color }}20; color: {{ $file->category->color }}">
                                                @if($file->category->icon)
                                                    <i class="{{ $file->category->icon }} {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                                                @endif
                                                {{ $file->category->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-sm">{{ trans('files.uncategorized') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $file->human_readable_size }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <div class="flex items-center space-x-1 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                                            <span>{{ $file->created_at->diffForHumans() }}</span>
                                            @if($file->is_public)
                                                <i class="fas fa-eye text-green-500 text-xs" title="{{ trans('files.public') }}"></i>
                                            @else
                                                <i class="fas fa-eye-slash text-gray-400 text-xs" title="{{ trans('files.private') }}"></i>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-{{ is_rtl() ? 'left' : 'right' }} text-sm font-medium">
                                        <div class="flex items-center space-x-2 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                                            <button
                                                wire:click="downloadFile({{ $file->id }})"
                                                class="text-primary-600 hover:text-primary-900 p-1 rounded transition-colors"
                                                title="{{ trans('files.download') }}">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button
                                                wire:click="editFile({{ $file->id }})"
                                                class="text-yellow-600 hover:text-yellow-900 p-1 rounded transition-colors"
                                                title="{{ trans('files.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button
                                                wire:click="deleteFile({{ $file->id }})"
                                                onclick="return confirm('{{ trans('files.are_you_sure') }}')"
                                                class="text-red-600 hover:text-red-900 p-1 rounded transition-colors"
                                                title="{{ trans('files.delete') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-folder-open text-4xl text-gray-300"></i>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ trans('files.no_files_found') }}</h3>
                                        <p class="text-gray-500 mb-4">{{ trans('files.try_different_search') }}</p>
                                        <button
                                            wire:click="showUploadForm"
                                            class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                                            <i class="fas fa-plus {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                                            {{ trans('files.upload_first_file') }}
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Enhanced Pagination -->
    @if($files->hasPages())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    {{ trans('files.showing') }}
                    <span class="font-medium">{{ $files->firstItem() }}</span>
                    {{ trans('files.to') }}
                    <span class="font-medium">{{ $files->lastItem() }}</span>
                    {{ trans('files.of') }}
                    <span class="font-medium">{{ $files->total() }}</span>
                    {{ trans('files.results') }}
                </div>
                {{ $files->links() }}
            </div>
        </div>
    @endif

    <!-- Enhanced Upload Modal -->
    @if($showUploadModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="hideUploadForm"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-xl text-{{ is_rtl() ? 'right' : 'left' }} overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="uploadFiles">
                        <div class="bg-white px-6 pt-6 pb-4">
                            <!-- Modal Header -->
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-cloud-upload-alt text-primary-600 {{ is_rtl() ? 'ml-3' : 'mr-3' }}"></i>
                                    {{ trans('files.upload_file') }}
                                </h3>
                                <button
                                    type="button"
                                    wire:click="hideUploadForm"
                                    class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class="fas fa-times text-lg"></i>
                                </button>
                            </div>

                            <div class="space-y-6">
                                <!-- Enhanced File Upload -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                                        <i class="fas fa-file {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                                        {{ trans('files.select_file') }}
                                    </label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-primary-400 transition-colors">
                                        <div class="space-y-1 text-center">
                                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                                    <span>{{ trans('files.upload_a_file') }}</span>
                                                    <input
                                                        id="file-upload"
                                                        name="file-upload"
                                                        type="file"
                                                        wire:model="uploadFile"
                                                        class="sr-only"
                                                        accept="*/*">
                                                </label>
                                                <p class="{{ is_rtl() ? 'pr-1' : 'pl-1' }}">{{ trans('files.or_drag_and_drop') }}</p>
                                            </div>
                                            <p class="text-xs text-gray-500">{{ trans('files.supported_formats_info') }}</p>
                                        </div>
                                    </div>
                                    @error('uploadFile')
                                        <div class="mt-2 flex items-center text-sm text-red-600">
                                            <i class="fas fa-exclamation-circle {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Enhanced Title Input -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                                        <i class="fas fa-tag {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                                        {{ trans('files.title') }}
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="uploadTitle"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                        placeholder="{{ trans('files.enter_file_title') }}">
                                    @error('uploadTitle')
                                        <div class="mt-2 flex items-center text-sm text-red-600">
                                            <i class="fas fa-exclamation-circle {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Enhanced Description -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                                        <i class="fas fa-align-left {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                                        {{ trans('files.description') }}
                                        <span class="text-gray-400 text-xs">({{ trans('files.optional') }})</span>
                                    </label>
                                    <textarea
                                        wire:model="uploadDescription"
                                        rows="3"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 resize-none transition-all"
                                        placeholder="{{ trans('files.enter_file_description') }}"></textarea>
                                </div>

                                <!-- Enhanced Category Selection -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                                        <i class="fas fa-sitemap {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                                        {{ trans('files.category') }}
                                    </label>

                                    @if(!$showNewCategoryInput)
                                        <div class="flex items-center space-x-2 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                                            <select
                                                wire:model="uploadCategory"
                                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                                                <option value="">{{ trans('files.select_category') }}</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category['id'] }}">
                                                        {{ str_repeat('— ', $category['level']) }}{{ $category['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button
                                                type="button"
                                                wire:click="toggleNewCategoryInput"
                                                class="px-4 py-3 text-primary-600 hover:text-primary-700 border border-primary-200 hover:border-primary-300 rounded-lg transition-colors"
                                                title="{{ trans('files.add_new_category') }}">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    @else
                                        <div class="flex items-center space-x-2 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                                            <input
                                                type="text"
                                                wire:model="newCategoryName"
                                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                                placeholder="{{ trans('files.new_category_name') }}">
                                            <button
                                                type="button"
                                                wire:click="createNewCategory"
                                                class="px-4 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors">
                                                {{ trans('files.add') }}
                                            </button>
                                            <button
                                                type="button"
                                                wire:click="toggleNewCategoryInput"
                                                class="px-4 py-3 text-gray-600 hover:text-gray-700 border border-gray-300 rounded-lg transition-colors">
                                                {{ trans('files.cancel') }}
                                            </button>
                                        </div>
                                        @error('newCategoryName')
                                            <div class="mt-2 flex items-center text-sm text-red-600">
                                                <i class="fas fa-exclamation-circle {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    @endif
                                </div>

                                <!-- Enhanced Public Toggle -->
                                <div>
                                    <label class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-100 transition-colors">
                                        <input
                                            type="checkbox"
                                            wire:model="uploadIsPublic"
                                            class="h-5 w-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                        <div class="{{ is_rtl() ? 'mr-3' : 'ml-3' }}">
                                            <div class="text-sm font-medium text-gray-900 flex items-center">
                                                <i class="fas fa-eye {{ is_rtl() ? 'ml-2' : 'mr-2' }} text-primary-600"></i>
                                                {{ trans('files.this_file_public') }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">{{ trans('files.public_files_accessible') }}</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Enhanced Modal Footer -->
                        <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                            <button
                                type="button"
                                wire:click="hideUploadForm"
                                class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                                {{ trans('files.cancel') }}
                            </button>
                            <button
                                type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all shadow-sm hover:shadow-md"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fas fa-upload {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                                    {{ trans('files.upload') }}
                                </span>
                                <span wire:loading>
                                    <i class="fas fa-spinner fa-spin {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                                    {{ trans('files.uploading') }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Enhanced Edit Modal -->
    @if($showEditModal && $editingFile)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="hideEditForm"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-xl text-{{ is_rtl() ? 'right' : 'left' }} overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="updateFile">
                        <div class="bg-white px-6 pt-6 pb-4">
                            <!-- Modal Header -->
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-edit text-primary-600 {{ is_rtl() ? 'ml-3' : 'mr-3' }}"></i>
                                    {{ trans('files.edit_file') }}
                                </h3>
                                <button
                                    type="button"
                                    wire:click="hideEditForm"
                                    class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class="fas fa-times text-lg"></i>
                                </button>
                            </div>

                            <div class="space-y-6">
                                <!-- Title -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                                        <i class="fas fa-tag {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                                        {{ trans('files.title') }}
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="editTitle"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                                    @error('editTitle')
                                        <div class="mt-2 flex items-center text-sm text-red-600">
                                            <i class="fas fa-exclamation-circle {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                                        <i class="fas fa-align-left {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                                        {{ trans('files.description') }}
                                    </label>
                                    <textarea
                                        wire:model="editDescription"
                                        rows="3"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 resize-none transition-all"></textarea>
                                </div>

                                <!-- Category -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                                        <i class="fas fa-sitemap {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                                        {{ trans('files.category') }}
                                    </label>
                                    <select
                                        wire:model="editCategory"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                                        <option value="">{{ trans('files.select_category') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category['id'] }}">
                                                {{ str_repeat('— ', $category['level']) }}{{ $category['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Public Toggle -->
                                <div>
                                    <label class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-100 transition-colors">
                                        <input
                                            type="checkbox"
                                            wire:model="editIsPublic"
                                            class="h-5 w-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                        <div class="{{ is_rtl() ? 'mr-3' : 'ml-3' }}">
                                            <div class="text-sm font-medium text-gray-900 flex items-center">
                                                <i class="fas fa-eye {{ is_rtl() ? 'ml-2' : 'mr-2' }} text-primary-600"></i>
                                                {{ trans('files.this_file_public') }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">{{ trans('files.public_files_accessible') }}</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                            <button
                                type="button"
                                wire:click="hideEditForm"
                                class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all">
                                {{ trans('files.cancel') }}
                            </button>
                            <button
                                type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 transition-all shadow-sm hover:shadow-md">
                                <i class="fas fa-save {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                                {{ trans('files.update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('livewire:init', function() {
    // Enhanced event listeners
    Livewire.on('fileUploaded', function(event) {
        showNotification(event.message, 'success');
    });

    Livewire.on('fileUpdated', function(event) {
        showNotification(event.message, 'success');
    });

    Livewire.on('fileDeleted', function(event) {
        showNotification(event.message, 'success');
    });

    Livewire.on('categoryCreated', function(event) {
        showNotification(event.message, 'success');
    });

    Livewire.on('bulkActionCompleted', function(event) {
        showNotification(event.message, 'success');
    });

    Livewire.on('error', function(event) {
        showNotification(event.message, 'error');
    });
});

// Enhanced notification system
function showNotification(message, type = 'success') {
    // Remove existing notifications
    document.querySelectorAll('.notification-toast').forEach(toast => toast.remove());

    const toast = document.createElement('div');
    toast.className = `notification-toast fixed top-4 {{ is_rtl() ? 'left-4' : 'right-4' }} max-w-sm bg-white rounded-lg shadow-lg border-l-4 p-4 z-50 transform transition-all duration-300 translate-x-full`;

    const colors = {
        success: 'border-green-500',
        error: 'border-red-500',
        warning: 'border-yellow-500',
        info: 'border-blue-500'
    };

    const icons = {
        success: 'fas fa-check-circle text-green-500',
        error: 'fas fa-exclamation-circle text-red-500',
        warning: 'fas fa-exclamation-triangle text-yellow-500',
        info: 'fas fa-info-circle text-blue-500'
    };

    toast.classList.add(colors[type] || colors.info);

    toast.innerHTML = `
        <div class="flex items-center">
            <i class="${icons[type] || icons.info} {{ is_rtl() ? 'ml-3' : 'mr-3' }}"></i>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-900">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="{{ is_rtl() ? 'mr-3' : 'ml-3' }} text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

    document.body.appendChild(toast);

    // Show with animation
    setTimeout(() => toast.classList.remove('translate-x-full'), 100);

    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}
</script>

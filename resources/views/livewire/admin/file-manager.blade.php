<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ __('File Manager') }}</h2>
            <p class="text-sm text-gray-600">{{ __('Manage and organize your files') }}</p>
        </div>

        <div class="flex items-center space-x-3 space-x-reverse">
            <!-- View Mode Toggle -->
            <div class="flex rounded-md shadow-sm" role="group">
                <button
                    wire:click="$set('viewMode', 'grid')"
                    class="px-4 py-2 text-sm font-medium {{ $viewMode === 'grid' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-primary-500"
                >
                    <i class="fas fa-th-large"></i>
                </button>
                <button
                    wire:click="$set('viewMode', 'list')"
                    class="px-4 py-2 text-sm font-medium {{ $viewMode === 'list' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-primary-500"
                >
                    <i class="fas fa-list"></i>
                </button>
            </div>

            <!-- Upload Button -->
            <button
                wire:click="showUploadForm"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
            >
                <i class="fas fa-upload mr-2"></i>
                {{ __('Upload File') }}
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Search') }}</label>
                <div class="relative">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        class="block w-full pr-10 border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                        placeholder="{{ __('Search files...') }}"
                    >
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Category Filter -->
            <div>
                <label for="category-filter" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Category') }}</label>
                <select
                    wire:model.live="categoryFilter"
                    class="block w-full border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                >
                    <option value="">{{ __('All Categories') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category['id'] }}">
                            {{ str_repeat('— ', $category['level']) }}{{ $category['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <label for="type-filter" class="block text-sm font-medium text-gray-700 mb-1">{{ __('File Type') }}</label>
                <select
                    wire:model.live="typeFilter"
                    class="block w-full border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                >
                    @foreach($fileTypes as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Per Page -->
            <div>
                <label for="per-page" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Per Page') }}</label>
                <select
                    wire:model.live="perPage"
                    class="block w-full border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                >
                    <option value="15">15</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-4 flex items-center justify-between">
            <button
                wire:click="resetFilters"
                class="text-sm text-gray-600 hover:text-gray-900"
            >
                <i class="fas fa-undo mr-1"></i>
                {{ __('Reset Filters') }}
            </button>

            @if($showBulkActions)
                <div class="flex items-center space-x-3 space-x-reverse">
                    <select
                        wire:model="bulkAction"
                        class="text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"
                    >
                        <option value="">{{ __('Bulk Actions') }}</option>
                        <option value="delete">{{ __('Delete') }}</option>
                        <option value="move">{{ __('Move to Category') }}</option>
                        <option value="public">{{ __('Make Public') }}</option>
                        <option value="private">{{ __('Make Private') }}</option>
                    </select>

                    @if($bulkAction === 'move')
                        <select
                            wire:model="bulkMoveCategory"
                            class="text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"
                        >
                            <option value="">{{ __('Select Category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                            @endforeach
                        </select>
                    @endif

                    <button
                        wire:click="executeBulkAction"
                        class="px-3 py-1 bg-primary-600 text-white text-sm rounded-md hover:bg-primary-700"
                        @if(empty($bulkAction)) disabled @endif
                    >
                        {{ __('Apply') }}
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="flex justify-center items-center py-4">
        <div class="flex items-center space-x-2 space-x-reverse">
            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-primary-600"></div>
            <span class="text-sm text-gray-600">{{ __('Loading...') }}</span>
        </div>
    </div>

    <!-- Files Grid/List -->
    <div wire:loading.class="opacity-50">
        @if($viewMode === 'grid')
            <!-- Grid View -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @forelse($files as $file)
                    <div class="bg-white rounded-lg shadow hover:shadow-md transition-shadow duration-200 border border-gray-200">
                        <!-- File Preview -->
                        <div class="aspect-square bg-gray-50 rounded-t-lg flex items-center justify-center relative overflow-hidden">
                            <!-- Checkbox -->
                            <div class="absolute top-2 right-2 z-10">
                                <input
                                    type="checkbox"
                                    wire:model.live="selectedFiles"
                                    value="{{ $file->id }}"
                                    class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                >
                            </div>

                            @if($file->is_image)
                                <img
                                    src="{{ $file->file_url }}"
                                    alt="{{ $file->title }}"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                >
                            @else
                                <div class="text-center">
                                    <i class="{{ $file->file_icon }} text-4xl text-gray-400 mb-2"></i>
                                    <p class="text-xs text-gray-500 uppercase font-medium">{{ $file->extension }}</p>
                                </div>
                            @endif

                            <!-- Overlay Actions -->
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-200 flex items-center justify-center space-x-2 space-x-reverse">
                                <button
                                    wire:click="downloadFile({{ $file->id }})"
                                    class="p-2 bg-white text-gray-700 rounded-full hover:bg-gray-100"
                                    title="{{ __('Download') }}"
                                >
                                    <i class="fas fa-download text-sm"></i>
                                </button>
                                <button
                                    wire:click="editFile({{ $file->id }})"
                                    class="p-2 bg-white text-gray-700 rounded-full hover:bg-gray-100"
                                    title="{{ __('Edit') }}"
                                >
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                <button
                                    wire:click="deleteFile({{ $file->id }})"
                                    onclick="return confirm('{{ __('Are you sure?') }}')"
                                    class="p-2 bg-white text-red-600 rounded-full hover:bg-gray-100"
                                    title="{{ __('Delete') }}"
                                >
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <!-- File Info -->
                        <div class="p-3">
                            <h3 class="font-medium text-sm text-gray-900 truncate" title="{{ $file->title }}">
                                {{ $file->title }}
                            </h3>
                            <div class="mt-1 flex items-center justify-between text-xs text-gray-500">
                                <span>{{ $file->human_readable_size }}</span>
                                @if($file->is_public)
                                    <i class="fas fa-eye text-green-500" title="{{ __('Public') }}"></i>
                                @else
                                    <i class="fas fa-eye-slash text-gray-400" title="{{ __('Private') }}"></i>
                                @endif
                            </div>
                            @if($file->category)
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
                                          style="background-color: {{ $file->category->color }}20; color: {{ $file->category->color }}">
                                        @if($file->category->icon)
                                            <i class="{{ $file->category->icon }} mr-1"></i>
                                        @endif
                                        {{ $file->category->name }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="text-center py-12">
                            <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('No files found') }}</h3>
                            <p class="text-gray-500">{{ __('Try adjusting your search or filters') }}</p>
                        </div>
                    </div>
                @endforelse
            </div>
        @else
            <!-- List View -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right">
                                <input
                                    type="checkbox"
                                    wire:model.live="selectAll"
                                    class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                >
                            </th>
                            <th
                                wire:click="sortBy('title')"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700"
                            >
                                {{ __('Title') }}
                                @if($sortBy === 'title')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Category') }}
                            </th>
                            <th
                                wire:click="sortBy('size')"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700"
                            >
                                {{ __('Size') }}
                                @if($sortBy === 'size')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </th>
                            <th
                                wire:click="sortBy('created_at')"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700"
                            >
                                {{ __('Uploaded') }}
                                @if($sortBy === 'created_at')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($files as $file)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $file->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center space-x-2 space-x-reverse">
                                        <button
                                            wire:click="downloadFile({{ $file->id }})"
                                            class="text-primary-600 hover:text-primary-900"
                                            title="{{ __('Download') }}"
                                        >
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button
                                            wire:click="editFile({{ $file->id }})"
                                            class="text-yellow-600 hover:text-yellow-900"
                                            title="{{ __('Edit') }}"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button
                                            wire:click="deleteFile({{ $file->id }})"
                                            onclick="return confirm('{{ __('Are you sure?') }}')"
                                            class="text-red-600 hover:text-red-900"
                                            title="{{ __('Delete') }}"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @if($file->is_public)
                                            <i class="fas fa-eye text-green-500" title="{{ __('Public') }}"></i>
                                        @else
                                            <i class="fas fa-eye-slash text-gray-400" title="{{ __('Private') }}"></i>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('No files found') }}</h3>
                                    <p class="text-gray-500">{{ __('Try adjusting your search or filters') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($files->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6 rounded-b-lg">
            {{ $files->links() }}
        </div>
    @endif

    <!-- Upload Modal -->
    @if($showUploadModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="hideUploadForm"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="uploadFiles">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:text-right w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                        {{ __('Upload File') }}
                                    </h3>

                                    <div class="space-y-4">
                                        <!-- File Upload -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Select File') }}</label>
                                            <input
                                                type="file"
                                                wire:model="uploadFile"
                                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100"
                                                accept="*/*"
                                            >
                                            @error('uploadFile') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Title -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Title') }}</label>
                                            <input
                                                type="text"
                                                wire:model="uploadTitle"
                                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                                placeholder="{{ __('Enter file title') }}"
                                            >
                                            @error('uploadTitle') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Description -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Description') }}</label>
                                            <textarea
                                                wire:model="uploadDescription"
                                                rows="3"
                                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                                placeholder="{{ __('Enter file description (optional)') }}"
                                            ></textarea>
                                        </div>

                                        <!-- Category -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Category') }}</label>

                                            @if(!$showNewCategoryInput)
                                                <div class="flex items-center space-x-2 space-x-reverse">
                                                    <select
                                                        wire:model="uploadCategory"
                                                        class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                                    >
                                                        <option value="">{{ __('Select Category') }}</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category['id'] }}">
                                                                {{ str_repeat('— ', $category['level']) }}{{ $category['name'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button
                                                        type="button"
                                                        wire:click="toggleNewCategoryInput"
                                                        class="px-3 py-2 text-sm text-primary-600 hover:text-primary-700"
                                                        title="{{ __('Add New Category') }}"
                                                    >
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <div class="flex items-center space-x-2 space-x-reverse">
                                                    <input
                                                        type="text"
                                                        wire:model="newCategoryName"
                                                        class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                                        placeholder="{{ __('New category name') }}"
                                                    >
                                                    <button
                                                        type="button"
                                                        wire:click="createNewCategory"
                                                        class="px-3 py-2 bg-primary-600 text-white text-sm rounded-md hover:bg-primary-700"
                                                    >
                                                        {{ __('Add') }}
                                                    </button>
                                                    <button
                                                        type="button"
                                                        wire:click="toggleNewCategoryInput"
                                                        class="px-3 py-2 text-sm text-gray-600 hover:text-gray-700"
                                                    >
                                                        {{ __('Cancel') }}
                                                    </button>
                                                </div>
                                                @error('newCategoryName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                            @endif
                                        </div>

                                        <!-- Public Toggle -->
                                        <div class="flex items-center">
                                            <input
                                                type="checkbox"
                                                wire:model="uploadIsPublic"
                                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                            >
                                            <label class="mr-2 block text-sm text-gray-900">
                                                {{ __('Make this file public') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button
                                type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm"
                                wire:loading.attr="disabled"
                            >
                                <span wire:loading.remove>{{ __('Upload') }}</span>
                                <span wire:loading>{{ __('Uploading...') }}</span>
                            </button>
                            <button
                                type="button"
                                wire:click="hideUploadForm"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:w-auto sm:text-sm"
                            >
                                {{ __('Cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Modal -->
    @if($showEditModal && $editingFile)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="hideEditForm"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="updateFile">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:text-right w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                        {{ __('Edit File') }}
                                    </h3>

                                    <div class="space-y-4">
                                        <!-- Title -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Title') }}</label>
                                            <input
                                                type="text"
                                                wire:model="editTitle"
                                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                            >
                                            @error('editTitle') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Description -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Description') }}</label>
                                            <textarea
                                                wire:model="editDescription"
                                                rows="3"
                                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                            ></textarea>
                                        </div>

                                        <!-- Category -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Category') }}</label>
                                            <select
                                                wire:model="editCategory"
                                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                            >
                                                <option value="">{{ __('Select Category') }}</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category['id'] }}">
                                                        {{ str_repeat('— ', $category['level']) }}{{ $category['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Public Toggle -->
                                        <div class="flex items-center">
                                            <input
                                                type="checkbox"
                                                wire:model="editIsPublic"
                                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                            >
                                            <label class="mr-2 block text-sm text-gray-900">
                                                {{ __('Make this file public') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button
                                type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                {{ __('Update') }}
                            </button>
                            <button
                                type="button"
                                wire:click="hideEditForm"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:w-auto sm:text-sm"
                            >
                                {{ __('Cancel') }}
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
        Livewire.on('fileUploaded', function(event) {
            // Show success notification
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

    function showNotification(message, type = 'success') {
        // You can implement your notification system here
        // For now, we'll use a simple alert
        if (type === 'success') {
            // You can replace this with a toast notification
            console.log('Success: ' + message);
        } else {
            console.log('Error: ' + message);
        }
    }
</script>

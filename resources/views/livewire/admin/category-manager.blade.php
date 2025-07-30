<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ __('Category Management') }}</h2>
            <p class="text-sm text-gray-600">{{ __('Organize your files with categories') }}</p>
        </div>

        <div class="flex items-center space-x-3 space-x-reverse">
            <!-- View Mode Toggle -->
            <div class="flex rounded-md shadow-sm" role="group">
                <button
                    wire:click="$set('viewMode', 'list')"
                    class="px-4 py-2 text-sm font-medium {{ $viewMode === 'list' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-primary-500"
                >
                    <i class="fas fa-list"></i>
                </button>
                <button
                    wire:click="$set('viewMode', 'tree')"
                    class="px-4 py-2 text-sm font-medium {{ $viewMode === 'tree' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-primary-500"
                >
                    <i class="fas fa-sitemap"></i>
                </button>
            </div>

            <!-- Create Button -->
            <button
                wire:click="showCreateForm"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
            >
                <i class="fas fa-plus mr-2"></i>
                {{ __('Create Category') }}
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Search') }}</label>
                <div class="relative">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        class="block w-full pr-10 border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                        placeholder="{{ __('Search categories...') }}"
                    >
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Per Page (only for list view) -->
            @if($viewMode === 'list')
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
            @endif

            <!-- Actions -->
            <div class="flex items-end">
                <button
                    wire:click="resetFilters"
                    class="text-sm text-gray-600 hover:text-gray-900"
                >
                    <i class="fas fa-undo mr-1"></i>
                    {{ __('Reset Filters') }}
                </button>
            </div>
        </div>

        <!-- Bulk Actions -->
        @if($showBulkActions)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex items-center space-x-3 space-x-reverse">
                    <select
                        wire:model="bulkAction"
                        class="text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"
                    >
                        <option value="">{{ __('Bulk Actions') }}</option>
                        <option value="activate">{{ __('Activate') }}</option>
                        <option value="deactivate">{{ __('Deactivate') }}</option>
                        <option value="delete">{{ __('Delete') }}</option>
                    </select>

                    <button
                        wire:click="executeBulkAction"
                        class="px-3 py-1 bg-primary-600 text-white text-sm rounded-md hover:bg-primary-700"
                        @if(empty($bulkAction)) disabled @endif
                    >
                        {{ __('Apply') }}
                    </button>

                    <span class="text-sm text-gray-600">
                        {{ count($selectedCategories) }} {{ __('selected') }}
                    </span>
                </div>
            </div>
        @endif
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="flex justify-center items-center py-4">
        <div class="flex items-center space-x-2 space-x-reverse">
            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-primary-600"></div>
            <span class="text-sm text-gray-600">{{ __('Loading...') }}</span>
        </div>
    </div>

    <!-- Categories Display -->
    <div wire:loading.class="opacity-50">
        @if($viewMode === 'list')
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
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Category') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Files Count') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Status') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Created') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($categories as $category)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <input
                                        type="checkbox"
                                        wire:model.live="selectedCategories"
                                        value="{{ $category->id }}"
                                        class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                    >
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full flex items-center justify-center"
                                                 style="background-color: {{ $category->color }}20;">
                                                @if($category->icon)
                                                    <i class="{{ $category->icon }}" style="color: {{ $category->color }}"></i>
                                                @else
                                                    <i class="fas fa-folder" style="color: {{ $category->color }}"></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mr-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $category->name }}
                                                @if($category->parent)
                                                    <span class="text-xs text-gray-500">
                                                        ({{ __('under') }} {{ $category->parent->name }})
                                                    </span>
                                                @endif
                                            </div>
                                            @if($category->description)
                                                <div class="text-sm text-gray-500">{{ Str::limit($category->description, 50) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $category->files_count ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <button
                                        wire:click="toggleCategoryStatus({{ $category->id }})"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
                                    >
                                        {{ $category->is_active ? __('Active') : __('Inactive') }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $category->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center space-x-2 space-x-reverse">
                                        <button
                                            wire:click="editCategory({{ $category->id }})"
                                            class="text-yellow-600 hover:text-yellow-900"
                                            title="{{ __('Edit') }}"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button
                                            wire:click="deleteCategory({{ $category->id }})"
                                            onclick="return confirm('{{ __('Are you sure? This will also move any child categories to the parent level.') }}')"
                                            class="text-red-600 hover:text-red-900"
                                            title="{{ __('Delete') }}"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('No categories found') }}</h3>
                                    <p class="text-gray-500">{{ __('Create your first category to get started') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                @if($categories->hasPages())
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        @else
            <!-- Tree View -->
            <div class="bg-white rounded-lg shadow p-6">
                @if($categories->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($categories as $category)
                            @include('admin.partials.category-tree-item', ['category' => $category, 'level' => 0])
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-sitemap text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('No categories found') }}</h3>
                        <p class="text-gray-500">{{ __('Create your first category to see the tree structure') }}</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    @if($showForm)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="hideForm"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="saveCategory">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:text-right w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                        {{ $editingCategory ? __('Edit Category') : __('Create Category') }}
                                    </h3>

                                    <div class="space-y-4">
                                        <!-- Name -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Name') }}</label>
                                            <input
                                                type="text"
                                                wire:model="name"
                                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                                placeholder="{{ __('Enter category name') }}"
                                            >
                                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Description -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Description') }}</label>
                                            <textarea
                                                wire:model="description"
                                                rows="3"
                                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                                placeholder="{{ __('Enter category description (optional)') }}"
                                            ></textarea>
                                        </div>

                                        <!-- Parent Category -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Parent Category') }}</label>
                                            <select
                                                wire:model="parentId"
                                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                            >
                                                <option value="">{{ __('No Parent (Root Category)') }}</option>
                                                @foreach($parentCategories as $parent)
                                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('parentId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Color and Icon -->
                                        <div class="grid grid-cols-2 gap-4">
                                            <!-- Color -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Color') }}</label>
                                                <div class="grid grid-cols-5 gap-2">
                                                    @foreach($colorOptions as $colorValue => $colorName)
                                                        <button
                                                            type="button"
                                                            wire:click="$set('color', '{{ $colorValue }}')"
                                                            class="w-8 h-8 rounded-full border-2 {{ $color === $colorValue ? 'border-gray-400' : 'border-gray-200' }}"
                                                            style="background-color: {{ $colorValue }}"
                                                            title="{{ $colorName }}"
                                                        ></button>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- Icon -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Icon') }}</label>
                                                <select
                                                    wire:model="icon"
                                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                                >
                                                    <option value="">{{ __('No Icon') }}</option>
                                                    @foreach($iconOptions as $iconClass => $iconName)
                                                        <option value="{{ $iconClass }}">{{ $iconName }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Sort Order -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Sort Order') }}</label>
                                            <input
                                                type="number"
                                                wire:model="sortOrder"
                                                min="0"
                                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                                placeholder="0"
                                            >
                                        </div>

                                        <!-- Active Toggle -->
                                        <div class="flex items-center">
                                            <input
                                                type="checkbox"
                                                wire:model="isActive"
                                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                            >
                                            <label class="mr-2 block text-sm text-gray-900">
                                                {{ __('Active') }}
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
                                {{ $editingCategory ? __('Update') : __('Create') }}
                            </button>
                            <button
                                type="button"
                                wire:click="hideForm"
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
        Livewire.on('categoryActioned', function(event) {
            showNotification(event.message, 'success');
        });

        Livewire.on('error', function(event) {
            showNotification(event.message, 'error');
        });
    });

    function showNotification(message, type = 'success') {
        // Implement your notification system here
        console.log(type + ': ' + message);
    }
</script>

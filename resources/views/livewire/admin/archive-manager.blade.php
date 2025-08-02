{{-- resources/views/livewire/admin/archive-manager.blade.php --}}
<div class="space-y-6">
    <!-- Header with Statistics -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ trans('all.Archive Management') }}</h1>
                <p class="text-sm text-gray-600">{{ trans('all.Organize and manage your files') }}</p>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary-600">{{ $statistics['total_archives'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">{{ trans('all.Total Files') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $statistics['total_size'] ?? '0 B' }}</div>
                    <div class="text-sm text-gray-500">{{ trans('all.Total Size') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $statistics['by_type']['images'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">{{ trans('all.Images') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $statistics['by_type']['documents'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">{{ trans('all.Documents') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
            <!-- Search -->
            <div>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="{{ trans('all.Search files...') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <!-- Category Filter -->
            <div>
                <select wire:model.live="categoryFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">{{ trans('all.All Categories') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                                style="padding-{{ is_rtl() ? 'right' : 'left' }}: {{ $category->level * 20 }}px;">
                            {{ str_repeat('—', $category->level) }} {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- File Type Filter -->
            <div>
                <select wire:model.live="fileTypeFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">{{ trans('all.All Types') }}</option>
                    @foreach($fileTypes as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <select wire:model.live="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="active">{{ trans('all.Active') }}</option>
                    <option value="archived">{{ trans('all.Archived') }}</option>
                    <option value="draft">{{ trans('all.Draft') }}</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-2 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                <button wire:click="resetFilters" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    {{ trans('all.Clear') }}
                </button>
                <button wire:click="showCreateModal" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                    <i class="fas fa-plus {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                    {{ trans('all.Add File') }}
                </button>
            </div>
        </div>

        <!-- Bulk Actions -->
        @if($showBulkActions)
            <div class="flex items-center space-x-4 {{ is_rtl() ? 'space-x-reverse' : '' }} pt-4 border-t border-gray-200">
                <select wire:model="bulkAction" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <option value="">{{ trans('all.Bulk Actions') }}</option>
                    <option value="activate">{{ trans('all.activate') }}</option>
                    <option value="archive">{{ trans('all.Move to Category') }}</option>
                    <option value="delete">{{ trans('all.delete') }}</option>
                </select>
                <button wire:click="executeBulkAction"
                        class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 text-sm"
                        @if(empty($bulkAction)) disabled @endif>
                    {{ trans('all.Apply') }}
                </button>
                <span class="text-sm text-gray-600">
                    {{ count($selectedArchives) }} {{ trans('all.selected') }}
                </span>
            </div>
        @endif
    </div>

    <!-- Loading Indicator -->
    <div wire:loading.delay class="flex justify-center items-center py-4">
        <div class="flex items-center space-x-2 {{ is_rtl() ? 'space-x-reverse' : '' }}">
            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-primary-600"></div>
            <span class="text-sm text-gray-600">{{ trans('all.Loading...') }}</span>
        </div>
    </div>

    <!-- Archives Grid -->
    <div wire:loading.class="opacity-50" class="bg-white rounded-lg shadow overflow-hidden">
        @if($archives->count() > 0)
            <!-- Grid Header -->
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center space-x-4 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                    <input type="checkbox"
                           wire:model.live="selectAll"
                           class="rounded border-gray-300 text-primary-600">
                    <span class="text-sm text-gray-600">{{ trans('all.Select All') }}</span>
                </div>
                <div class="flex items-center space-x-2 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                    <button wire:click="$set('viewMode', 'grid')"
                            class="p-2 {{ $viewMode === 'grid' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-600' }} rounded">
                        <i class="fas fa-th"></i>
                    </button>
                    <button wire:click="$set('viewMode', 'list')"
                            class="p-2 {{ $viewMode === 'list' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-600' }} rounded">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>

            @if($viewMode === 'grid')
                <!-- Grid View -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6">
                    @foreach($archives as $archive)
                        <div class="bg-gray-50 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                            <div class="relative">
                                <!-- Checkbox -->
                                <div class="absolute top-2 {{ is_rtl() ? 'right-2' : 'left-2' }} z-10">
                                    <input type="checkbox"
                                           wire:model.live="selectedArchives"
                                           value="{{ $archive->id }}"
                                           class="rounded border-gray-300 text-primary-600">
                                </div>

                                <!-- File Preview -->
                                <div class="h-48 bg-gray-200 flex items-center justify-center">
                                    @if($archive->file_type === 'image')
                                        <img src="{{ $archive->getFirstMediaUrl('files', 'preview') ?: $archive->getFirstMediaUrl('files') }}"
                                             alt="{{ $archive->title }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="text-center">
                                            <i class="fas fa-{{ $archive->file_type === 'document' ? 'file-alt' : ($archive->file_type === 'video' ? 'video' : ($archive->file_type === 'audio' ? 'music' : 'file')) }} text-4xl text-gray-400 mb-2"></i>
                                            <div class="text-xs text-gray-500 uppercase">{{ $archive->file_extension }}</div>
                                        </div>
                                    @endif
                                </div>

                                <!-- File Type Badge -->
                                <div class="absolute top-2 {{ is_rtl() ? 'left-2' : 'right-2' }}">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $archive->file_type === 'image' ? 'bg-green-100 text-green-800' :
                                           ($archive->file_type === 'document' ? 'bg-blue-100 text-blue-800' :
                                            ($archive->file_type === 'video' ? 'bg-purple-100 text-purple-800' :
                                             ($archive->file_type === 'audio' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'))) }}">
                                        {{ ucfirst($archive->file_type) }}
                                    </span>
                                </div>
                            </div>

                            <!-- File Info -->
                            <div class="p-4">
                                <h3 class="font-medium text-gray-900 mb-1 truncate" title="{{ $archive->title }}">
                                    {{ $archive->title }}
                                </h3>

                                <div class="flex items-center space-x-2 {{ is_rtl() ? 'space-x-reverse' : '' }} text-sm text-gray-500 mb-2">
                                    <div class="flex items-center space-x-1 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                                        <div class="w-3 h-3 rounded-full" style="background-color: {{ $archive->category->color }}"></div>
                                        <span>{{ $archive->category->name }}</span>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center text-xs text-gray-500">
                                    <span>{{ $archive->file_size }}</span>
                                    <span>{{ $archive->created_at->diffForHumans() }}</span>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-200">
                                    <div class="flex items-center space-x-2 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                                        <button wire:click="showViewModal({{ $archive->id }})"
                                               class="text-blue-600 hover:text-blue-700 text-sm">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button wire:click="showEditModal({{ $archive->id }})"
                                               class="text-green-600 hover:text-green-700 text-sm">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="downloadArchive({{ $archive->id }})"
                                               class="text-purple-600 hover:text-purple-700 text-sm">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                    <button wire:click="deleteArchive({{ $archive->id }})"
                                            wire:confirm="{{ trans('all.Are you sure you want to delete the selected files?') }}"
                                            class="text-red-600 hover:text-red-700 text-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Pagination -->
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                {{ $archives->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ trans('all.No files found') }}</h3>
                <p class="text-gray-500 mb-4">{{ trans('all.Upload your first file to get started') }}</p>
                <button wire:click="showCreateModal"
                       class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                    <i class="fas fa-plus {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                    {{ trans('all.Add File') }}
                </button>
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="hideModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-{{ is_rtl() ? 'right' : 'left' }} overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="saveArchive">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:text-{{ is_rtl() ? 'right' : 'left' }} w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                        {{ $modalTitle }}
                                    </h3>

                                    <div class="space-y-4">
                                        <!-- File Upload -->
                                        @if(!$editingArchive)
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ trans('all.File') }}</label>
                                                <input type="file"
                                                       wire:model="file"
                                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100"
                                                       accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar">
                                                @error('file') <span class="text-red-500 text-sm">{{ trans('all.' . $message) }}</span> @enderror
                                            </div>
                                        @endif

                                        <!-- Title -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ trans('all.Title') }}</label>
                                            <input type="text"
                                                   wire:model="title"
                                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                                   placeholder="{{ trans('all.Enter file title') }}">
                                            @error('title') <span class="text-red-500 text-sm">{{ trans('all.' . $message) }}</span> @enderror
                                        </div>

                                        <!-- Category -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ trans('all.Category') }}</label>
                                            <select wire:model="categoryId"
                                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                <option value="">{{ trans('all.Select Category') }}</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('categoryId') <span class="text-red-500 text-sm">{{ trans('all.' . $message) }}</span> @enderror
                                        </div>

                                        <!-- Status -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ trans('all.Status') }}</label>
                                            <select wire:model="status"
                                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                <option value="active">{{ trans('all.Active') }}</option>
                                                <option value="draft">{{ trans('all.Draft') }}</option>
                                                <option value="archived">{{ trans('all.Archived') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                                <span wire:loading.remove>{{ $editingArchive ? trans('all.update') : trans('all.create') }}</span>
                                <span wire:loading>{{ trans('all.uploading...') }}</span>
                            </button>
                            <button type="button"
                                    wire:click="hideModal"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:w-auto sm:text-sm">
                                {{ trans('all.cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', function() {
        Livewire.on('success', function(event) {
            showNotification(event.message, 'success');
        });

        Livewire.on('error', function(event) {
            showNotification(event.message, 'error');
        });
    });

    function showNotification(message, type = 'success') {
        // You can implement your notification system here
        // For now, using browser alert
        if (type === 'success') {
            // Show success toast/notification
            console.log('Success: ' + message);
        } else {
            // Show error toast/notification
            console.log('Error: ' + message);
        }
    }
</script>
@endpush

{{-- resources/views/admin/partials/category-tree-item.blade.php --}}
@php
    $level = $level ?? 0;
    $maxLevel = $maxLevel ?? 3;
    $showActions = $showActions ?? true;
    $selectable = $selectable ?? false;
    $selectedId = $selectedId ?? null;
@endphp

<div class="category-tree-item" data-category-id="{{ $category->id }}" data-level="{{ $level }}">
    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors {{ $level > 0 ? 'ml-' . ($level * 4) : '' }}">
        <div class="flex items-center space-x-3 flex-1">
            <!-- Toggle Button for Children -->
            @if($category->children->isNotEmpty() && $level < $maxLevel)
                <button type="button"
                        onclick="toggleCategoryChildren({{ $category->id }})"
                        class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <i class="fas fa-chevron-right transition-transform" id="toggle-{{ $category->id }}"></i>
                </button>
            @else
                <div class="w-4"></div>
            @endif

            <!-- Selection Checkbox (if selectable) -->
            @if($selectable)
                <label class="flex items-center">
                    <input type="checkbox"
                           name="selected_categories[]"
                           value="{{ $category->id }}"
                           {{ $selectedId == $category->id ? 'checked' : '' }}
                           class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                </label>
            @endif

            <!-- Category Icon -->
            <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center"
                 style="background-color: {{ $category->color }}20;">
                <i class="{{ $category->icon ?: 'fas fa-folder' }}" style="color: {{ $category->color }}"></i>
            </div>

            <!-- Category Info -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center space-x-2">
                    <h4 class="font-medium text-gray-900 truncate">{{ $category->name }}</h4>
                    @if(!$category->is_active)
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            {{ __('Inactive') }}
                        </span>
                    @endif
                </div>
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <span>{{ $category->files_count ?? $category->files()->count() }} {{ __('files') }}</span>
                    @if($category->children->isNotEmpty())
                        <span>{{ $category->children->count() }} {{ __('subcategories') }}</span>
                    @endif
                    @if($category->description)
                        <span class="truncate max-w-xs" title="{{ $category->description }}">{{ $category->description }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        @if($showActions)
            <div class="flex items-center space-x-2 ml-4">
                <a href="{{ route('admin.categories.show', $category) }}"
                   class="text-blue-600 hover:text-blue-700 text-sm px-2 py-1 rounded hover:bg-blue-50 transition-colors"
                   title="{{ __('View Category') }}">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('admin.categories.edit', $category) }}"
                   class="text-green-600 hover:text-green-700 text-sm px-2 py-1 rounded hover:bg-green-50 transition-colors"
                   title="{{ __('Edit Category') }}">
                    <i class="fas fa-edit"></i>
                </a>
                @if($category->files()->count() === 0)
                    <button onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}')"
                            class="text-red-600 hover:text-red-700 text-sm px-2 py-1 rounded hover:bg-red-50 transition-colors"
                            title="{{ __('Delete Category') }}">
                        <i class="fas fa-trash"></i>
                    </button>
                @endif
            </div>
        @endif
    </div>

    <!-- Children Categories -->
    @if($category->children->isNotEmpty() && $level < $maxLevel)
        <div class="category-children hidden mt-2 space-y-1" id="children-{{ $category->id }}">
            @foreach($category->children->sortBy('sort_order') as $child)
                @include('admin.partials.category-tree-item', [
                    'category' => $child,
                    'level' => $level + 1,
                    'maxLevel' => $maxLevel,
                    'showActions' => $showActions,
                    'selectable' => $selectable,
                    'selectedId' => $selectedId
                ])
            @endforeach
        </div>
    @endif
</div>

@if($level === 0)
    @push('scripts')
    <script>
    function toggleCategoryChildren(categoryId) {
        const children = document.getElementById(`children-${categoryId}`);
        const toggle = document.getElementById(`toggle-${categoryId}`);

        if (children && toggle) {
            if (children.classList.contains('hidden')) {
                children.classList.remove('hidden');
                toggle.style.transform = 'rotate(90deg)';
            } else {
                children.classList.add('hidden');
                toggle.style.transform = 'rotate(0deg)';
            }
        }
    }

    function deleteCategory(id, name) {
        if (confirm(`{{ __('Are you sure you want to delete the category') }} "${name}"?\n{{ __('This action cannot be undone.') }}`)) {
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

    // Auto-expand categories with selected items
    document.addEventListener('DOMContentLoaded', function() {
        const selectedCheckboxes = document.querySelectorAll('input[name="selected_categories[]"]:checked');
        selectedCheckboxes.forEach(checkbox => {
            let parent = checkbox.closest('.category-tree-item');
            while (parent) {
                const parentId = parent.dataset.categoryId;
                const children = document.getElementById(`children-${parentId}`);
                const toggle = document.getElementById(`toggle-${parentId}`);

                if (children && toggle && children.classList.contains('hidden')) {
                    children.classList.remove('hidden');
                    toggle.style.transform = 'rotate(90deg)';
                }

                parent = parent.parentElement.closest('.category-tree-item');
            }
        });
    });
    </script>
    @endpush
@endif

{{-- resources/views/admin/components/category-selector.blade.php --}}
@php
    $categories = $categories ?? collect();
    $selectedId = $selectedId ?? old($name ?? 'category_id');
    $name = $name ?? 'category_id';
    $placeholder = $placeholder ?? __('Select a category');
    $allowEmpty = $allowEmpty ?? true;
    $multiple = $multiple ?? false;
    $showTree = $showTree ?? false;
@endphp

@if($showTree)
    <!-- Tree View Selector -->
    <div class="category-selector-tree">
        <div class="border border-gray-300 rounded-md p-4 max-h-64 overflow-y-auto">
            @if($categories->isEmpty())
                <p class="text-gray-500 text-center py-4">{{ __('No categories available') }}</p>
            @else
                @foreach($categories->where('parent_id', null) as $category)
                    @include('admin.partials.category-tree-item', [
                        'category' => $category,
                        'level' => 0,
                        'showActions' => false,
                        'selectable' => true,
                        'selectedId' => $selectedId
                    ])
                @endforeach
            @endif
        </div>
    </div>
@else
    <!-- Dropdown Selector -->
    <select name="{{ $name }}{{ $multiple ? '[]' : '' }}"
            id="{{ $name }}"
            {{ $multiple ? 'multiple' : '' }}
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error($name) border-red-500 @enderror">

        @if($allowEmpty && !$multiple)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach($categories as $category)
            <option value="{{ is_array($category) ? $category['id'] : $category->id }}"
                    {{ (is_array($selectedId) ? in_array((is_array($category) ? $category['id'] : $category->id), $selectedId) : $selectedId == (is_array($category) ? $category['id'] : $category->id)) ? 'selected' : '' }}
                    style="padding-left: {{ (is_array($category) ? $category['level'] : $category->level) * 20 }}px;">
                {{ str_repeat('—', (is_array($category) ? $category['level'] : $category->level)) }} {{ is_array($category) ? $category['name'] : $category->name }}
                @if(isset($category['files_count']) || (is_object($category) && isset($category->files_count)))
                    ({{ is_array($category) ? $category['files_count'] : $category->files_count }} {{ __('files') }})
                @endif
            </option>
        @endforeach
    </select>
@endif

@error($name)
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror

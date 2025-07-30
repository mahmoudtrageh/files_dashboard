<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;
use App\Services\CategoryService;

class CategoryManager extends Component
{
    use WithPagination;

    // Search and filters
    public $search = '';
    public $perPage = 15;

    // Create/Edit form
    public $showForm = false;
    public $editingCategory = null;
    public $name = '';
    public $description = '';
    public $color = '#6366f1';
    public $icon = '';
    public $parentId = '';
    public $isActive = true;
    public $sortOrder = 0;

    // Bulk actions
    public $selectedCategories = [];
    public $selectAll = false;
    public $showBulkActions = false;
    public $bulkAction = '';

    // View mode
    public $viewMode = 'list'; // list or tree

    protected $categoryService;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        'icon' => 'nullable|string|max:50',
        'parentId' => 'nullable|exists:categories,id',
        'isActive' => 'boolean',
        'sortOrder' => 'integer|min:0'
    ];

    public function boot()
    {
        $this->categoryService = app(CategoryService::class);
    }

    public function mount()
    {
        $this->search = request('search', '');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedCategories = $this->getCategories()->pluck('id')->toArray();
        } else {
            $this->selectedCategories = [];
        }
        $this->updateBulkActionsVisibility();
    }

    public function updatedSelectedCategories()
    {
        $this->updateBulkActionsVisibility();
    }

    public function showCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function editCategory($categoryId)
    {
        $this->editingCategory = Category::findOrFail($categoryId);
        $this->name = $this->editingCategory->name;
        $this->description = $this->editingCategory->description;
        $this->color = $this->editingCategory->color;
        $this->icon = $this->editingCategory->icon;
        $this->parentId = $this->editingCategory->parent_id;
        $this->isActive = $this->editingCategory->is_active;
        $this->sortOrder = $this->editingCategory->sort_order;
        $this->showForm = true;
    }

    public function saveCategory()
    {
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'description' => $this->description,
                'color' => $this->color,
                'icon' => $this->icon,
                'parent_id' => $this->parentId ?: null,
                'is_active' => $this->isActive,
                'sort_order' => $this->sortOrder
            ];

            if ($this->editingCategory) {
                // Check for circular reference
                if ($this->parentId && $this->wouldCreateCircularReference($this->editingCategory->id, $this->parentId)) {
                    $this->addError('parentId', __('Cannot set this category as parent - it would create a circular reference'));
                    return;
                }

                $this->categoryService->updateCategory($this->editingCategory, $data);
                $message = __('Category updated successfully');
            } else {
                $this->categoryService->createCategory($data);
                $message = __('Category created successfully');
            }

            $this->hideForm();
            $this->dispatch('categoryActioned', ['message' => $message]);

        } catch (\Exception $e) {
            $this->addError('name', $e->getMessage());
        }
    }

    public function deleteCategory($categoryId)
    {
        try {
            $category = Category::findOrFail($categoryId);
            $this->categoryService->deleteCategory($category);

            $this->dispatch('categoryActioned', [
                'message' => __('Category deleted successfully')
            ]);

        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => $e->getMessage()]);
        }
    }

    public function toggleCategoryStatus($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $category->update(['is_active' => !$category->is_active]);

        $this->dispatch('categoryActioned', [
            'message' => __('Category status updated')
        ]);
    }

    public function executeBulkAction()
    {
        if (empty($this->selectedCategories) || empty($this->bulkAction)) {
            return;
        }

        try {
            switch ($this->bulkAction) {
                case 'delete':
                    $count = 0;
                    foreach ($this->selectedCategories as $categoryId) {
                        try {
                            $category = Category::find($categoryId);
                            if ($category) {
                                $this->categoryService->deleteCategory($category);
                                $count++;
                            }
                        } catch (\Exception $e) {
                            // Skip categories that can't be deleted
                            continue;
                        }
                    }
                    $this->dispatch('categoryActioned', [
                        'message' => __(':count categories deleted', ['count' => $count])
                    ]);
                    break;

                case 'activate':
                    Category::whereIn('id', $this->selectedCategories)->update(['is_active' => true]);
                    $this->dispatch('categoryActioned', [
                        'message' => __('Selected categories activated')
                    ]);
                    break;

                case 'deactivate':
                    Category::whereIn('id', $this->selectedCategories)->update(['is_active' => false]);
                    $this->dispatch('categoryActioned', [
                        'message' => __('Selected categories deactivated')
                    ]);
                    break;
            }

            $this->resetBulkActions();

        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => $e->getMessage()]);
        }
    }

    public function hideForm()
    {
        $this->showForm = false;
        $this->editingCategory = null;
        $this->resetForm();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->resetPage();
    }

    private function getCategories()
    {
        if ($this->viewMode === 'tree') {
            return $this->categoryService->getCategoryTree();
        }

        return $this->categoryService->getPaginatedCategories(
            $this->search,
            $this->perPage
        );
    }

    private function getParentCategories()
    {
        $query = Category::active()->orderBy('name');

        // Exclude current category and its descendants when editing
        if ($this->editingCategory) {
            $excludeIds = [$this->editingCategory->id];
            $this->getDescendantIds($this->editingCategory, $excludeIds);
            $query->whereNotIn('id', $excludeIds);
        }

        return $query->get(['id', 'name', 'parent_id']);
    }

    private function getDescendantIds($category, &$ids)
    {
        foreach ($category->children as $child) {
            $ids[] = $child->id;
            $this->getDescendantIds($child, $ids);
        }
    }

    private function wouldCreateCircularReference($categoryId, $parentId)
    {
        $current = Category::find($parentId);

        while ($current) {
            if ($current->id == $categoryId) {
                return true;
            }
            $current = $current->parent;
        }

        return false;
    }

    private function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->color = '#6366f1';
        $this->icon = '';
        $this->parentId = '';
        $this->isActive = true;
        $this->sortOrder = 0;
        $this->resetErrorBag();
    }

    private function resetBulkActions()
    {
        $this->selectedCategories = [];
        $this->selectAll = false;
        $this->showBulkActions = false;
        $this->bulkAction = '';
    }

    private function updateBulkActionsVisibility()
    {
        $this->showBulkActions = !empty($this->selectedCategories);
    }

    public function render()
    {
        return view('livewire.admin.category-manager', [
            'categories' => $this->getCategories(),
            'parentCategories' => $this->getParentCategories(),
            'iconOptions' => $this->getIconOptions(),
            'colorOptions' => $this->getColorOptions()
        ]);
    }

    private function getIconOptions()
    {
        return [
            'fas fa-folder' => __('Folder'),
            'fas fa-file' => __('File'),
            'fas fa-image' => __('Image'),
            'fas fa-video' => __('Video'),
            'fas fa-music' => __('Music'),
            'fas fa-code' => __('Code'),
            'fas fa-book' => __('Book'),
            'fas fa-archive' => __('Archive'),
            'fas fa-star' => __('Star'),
            'fas fa-heart' => __('Heart'),
            'fas fa-tag' => __('Tag'),
            'fas fa-bookmark' => __('Bookmark')
        ];
    }

    private function getColorOptions()
    {
        return [
            '#ef4444' => __('Red'),
            '#f97316' => __('Orange'),
            '#eab308' => __('Yellow'),
            '#22c55e' => __('Green'),
            '#06b6d4' => __('Cyan'),
            '#3b82f6' => __('Blue'),
            '#6366f1' => __('Indigo'),
            '#8b5cf6' => __('Purple'),
            '#ec4899' => __('Pink'),
            '#64748b' => __('Gray')
        ];
    }
}

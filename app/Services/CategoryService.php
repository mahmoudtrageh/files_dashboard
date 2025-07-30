<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CategoryService
{
    /**
     * Get paginated categories
     */
    public function getPaginatedCategories(
        ?string $search = null,
        int $perPage = 15,
        bool $onlyRoot = false
    ): LengthAwarePaginator {
        $query = Category::query()->with(['parent', 'children']);

        if ($onlyRoot) {
            $query->root();
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('sort_order')
                     ->orderBy('name')
                     ->paginate($perPage);
    }

    /**
     * Get all categories for dropdown
     */
    public function getCategoriesForSelect(): Collection
    {
        return Category::active()
            ->with('parent')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(function ($category) {
                $category->level = $this->getCategoryLevel($category);
                $category->display_name = $category->full_path;
                $category->has_children = $category->hasChildren();
                return $category;
            });
    }

    /**
     * Create new category
     */
    public function createCategory(array $data): Category
    {
        // Generate unique slug
        $slug = $this->generateUniqueSlug($data['name']);

        return Category::create([
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'color' => $data['color'] ?? '#6366f1',
            'icon' => $data['icon'] ?? null,
            'parent_id' => $data['parent_id'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'sort_order' => $data['sort_order'] ?? $this->getNextSortOrder($data['parent_id'] ?? null)
        ]);
    }

    /**
     * Update category
     */
    public function updateCategory(Category $category, array $data): Category
    {
        // Generate new slug if name changed
        if (isset($data['name']) && $data['name'] !== $category->name) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $category->id);
        }

        $category->update($data);
        return $category->fresh();
    }

    /**
     * Delete category
     */
    public function deleteCategory(Category $category): bool
    {
        // Check if category has files
        if ($category->files()->exists()) {
            throw new \Exception('Cannot delete category that contains files. Please move or delete files first.');
        }

        // Move children to parent or make them root categories
        if ($category->children()->exists()) {
            $category->children()->update(['parent_id' => $category->parent_id]);
        }

        return $category->delete();
    }

    /**
     * Get category tree structure
     */
    public function getCategoryTree(): Collection
    {
        return Category::active()
            ->root()
            ->with(['children' => function ($query) {
                $query->active()->orderBy('sort_order')->orderBy('name');
            }])
            ->withCount('files')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(function ($category) {
                return $this->formatCategoryForTree($category);
            });
    }

    /**
     * Search categories by name
     */
    public function searchCategories(string $query): Collection
    {
        return Category::active()
            ->where('name', 'like', "%{$query}%")
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'slug', 'color', 'icon']);
    }

    /**
     * Create category from name (for dynamic creation)
     */
    public function createCategoryFromName(string $name): Category
    {
        // Check if category already exists
        $existing = Category::where('name', $name)->first();
        if ($existing) {
            return $existing;
        }

        return $this->createCategory([
            'name' => $name,
            'is_active' => true
        ]);
    }

    /**
     * Get category statistics
     */
    public function getCategoryStatistics(): array
    {
        return [
            'total_categories' => Category::active()->count(),
            'root_categories' => Category::active()->root()->count(),
            'categories_with_files' => Category::has('files')->count(),
            'empty_categories' => Category::doesntHave('files')->count(),
            'most_used_categories' => Category::with(['files'])
                ->withCount('files')
                ->having('files_count', '>', 0)
                ->orderBy('files_count', 'desc')
                ->limit(5)
                ->get()
        ];
    }

    /**
     * Reorder categories
     */
    public function reorderCategories(array $categoryOrders): bool
    {
        foreach ($categoryOrders as $order) {
            Category::where('id', $order['id'])
                ->update([
                    'sort_order' => $order['sort_order'],
                    'parent_id' => $order['parent_id'] ?? null
                ]);
        }

        return true;
    }

    /**
     * Get breadcrumb for category
     */
    public function getBreadcrumb(Category $category): array
    {
        return $category->breadcrumb;
    }

    /**
     * Generate unique slug
     */
    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if slug exists
     */
    private function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $query = Category::where('slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Get next sort order for parent
     */
    private function getNextSortOrder(?int $parentId): int
    {
        $lastOrder = Category::where('parent_id', $parentId)
            ->max('sort_order');

        return ($lastOrder ?? 0) + 1;
    }

    /**
     * Get category level (depth)
     */
    private function getCategoryLevel(Category $category): int
    {
        $level = 0;
        $current = $category;

        while ($current->parent) {
            $level++;
            $current = $current->parent;
        }

        return $level;
    }

    /**
     * Format category for tree display
     */
    private function formatCategoryForTree(Category $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'color' => $category->color,
            'icon' => $category->icon,
            'files_count' => $category->files_count,
            'total_files_count' => $category->total_files_count,
            'has_children' => $category->children->isNotEmpty(),
            'children' => $category->children->map(function ($child) {
                return $this->formatCategoryForTree($child);
            })
        ];
    }
}

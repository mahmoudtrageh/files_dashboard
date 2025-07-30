<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use App\Http\Requests\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display the category management page
     */
    public function index(): View
    {
        if (!auth()->guard('admin')->user()->can('category.view')) {
            abort(403, 'Unauthorized action.');
        }

        $statistics = $this->categoryService->getCategoryStatistics();

        return view('admin.pages.categories.index', compact('statistics'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create(): View
    {
        if (!auth()->guard('admin')->user()->can('category.create')) {
            abort(403, 'Unauthorized action.');
        }

        $categories = $this->categoryService->getCategoriesForSelect();

        return view('admin.pages.categories.create', compact('categories'));
    }

    /**
     * Store a newly created category
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        try {
            $this->categoryService->createCategory($request->validated());

            return redirect()->route('admin.categories.index')
                ->with('success', __('Category created successfully'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified category
     */
    public function show(Category $category): View
    {
        if (!auth()->guard('admin')->user()->can('category.view')) {
            abort(403, 'Unauthorized action.');
        }

        $category->load(['parent', 'children', 'files']);
        $files = $this->categoryService->getFilesByCategory($category, 15);

        return view('admin.pages.categories.show', compact('category', 'files'));
    }

    /**
     * Show the form for editing the specified category
     */
    public function edit(Category $category): View
    {
        if (!auth()->guard('admin')->user()->can('category.edit')) {
            abort(403, 'Unauthorized action.');
        }

        $categories = $this->categoryService->getCategoriesForSelect();

        return view('admin.pages.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified category
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        try {
            $this->categoryService->updateCategory($category, $request->validated());

            return redirect()->route('admin.categories.index')
                ->with('success', __('Category updated successfully'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified category
     */
    public function destroy(Category $category): RedirectResponse
    {
        if (!auth()->guard('admin')->user()->can('category.delete')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $this->categoryService->deleteCategory($category);

            return redirect()->route('admin.categories.index')
                ->with('success', __('Category deleted successfully'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Get categories for AJAX requests
     */
    public function search($query = null): JsonResponse
    {
        if (!auth()->guard('admin')->user()->can('category.view')) {
            abort(403, 'Unauthorized action.');
        }

        $categories = $this->categoryService->searchCategories($query ?? '');

        return response()->json($categories);
    }

    /**
     * Get category tree for API
     */
    public function tree(): JsonResponse
    {
        if (!auth()->guard('admin')->user()->can('category.view')) {
            abort(403, 'Unauthorized action.');
        }

        $tree = $this->categoryService->getCategoryTree();

        return response()->json($tree);
    }

    /**
     * Reorder categories
     */
    public function reorder(Request $request): JsonResponse
    {
        if (!auth()->guard('admin')->user()->can('category.edit')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:categories,id',
            'categories.*.sort_order' => 'required|integer|min:0',
            'categories.*.parent_id' => 'nullable|exists:categories,id'
        ]);

        try {
            $this->categoryService->reorderCategories($request->categories);

            return response()->json(['success' => true, 'message' => __('Categories reordered successfully')]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}

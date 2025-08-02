<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Archive;
use App\Models\Category;
use App\Services\ArchiveService;
use App\Services\CategoryService;
use App\Http\Requests\Admin\Archive\StoreArchiveRequest;
use App\Http\Requests\Admin\Archive\UpdateArchiveRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ArchiveController extends Controller
{
    protected $archiveService;
    protected $categoryService;

    public function __construct(ArchiveService $archiveService, CategoryService $categoryService)
    {
        $this->archiveService = $archiveService;
        $this->categoryService = $categoryService;
    }

    /**
     * Display the archive management page
     */
    public function index(Request $request): View
    {
        if (!auth()->guard('admin')->user()->can('archive.view')) {
            abort(403, trans('all.unauthorized_action'));
        }

        $filters = [
            'search' => $request->get('search'),
            'category_id' => $request->get('category_id'),
            'file_type' => $request->get('file_type'),
            'status' => $request->get('status', 'active'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_order' => $request->get('sort_order', 'desc')
        ];

        $archives = $this->archiveService->getPaginatedArchives($filters, 20);
        $categories = $this->categoryService->getCategoriesForSelect();
        $statistics = $this->archiveService->getArchiveStatistics();

        return view('admin.pages.archives.index', compact('archives', 'categories', 'statistics', 'filters'));
    }

    /**
     * Show the form for creating a new archive
     */
    public function create(): View
    {
        if (!auth()->guard('admin')->user()->can('archive.create')) {
            abort(403, trans('all.unauthorized_action'));
        }

        $categories = $this->categoryService->getCategoriesForSelect();

        return view('admin.pages.archives.create', compact('categories'));
    }

    /**
     * Store a newly created archive
     */
    public function store(StoreArchiveRequest $request): RedirectResponse
    {
        try {
            $archive = $this->archiveService->createArchive(
                $request->validated(),
                $request->file('file')
            );

            return redirect()->route('admin.archives.index')
                ->with('success', trans('all.archive_created_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified archive
     */
    public function show(Archive $archive): View
    {
        if (!auth()->guard('admin')->user()->can('archive.view')) {
            abort(403, trans('all.unauthorized_action'));
        }

        $archive->load(['category', 'media']);

        return view('admin.pages.archives.show', compact('archive'));
    }

    /**
     * Show the form for editing the specified archive
     */
    public function edit(Archive $archive): View
    {
        if (!auth()->guard('admin')->user()->can('archive.edit')) {
            abort(403, trans('all.unauthorized_action'));
        }

        $categories = $this->categoryService->getCategoriesForSelect();

        return view('admin.pages.archives.edit', compact('archive', 'categories'));
    }

    /**
     * Update the specified archive
     */
    public function update(UpdateArchiveRequest $request, Archive $archive): RedirectResponse
    {
        try {
            $this->archiveService->updateArchive(
                $archive,
                $request->validated(),
                $request->file('file')
            );

            return redirect()->route('admin.archives.index')
                ->with('success', trans('all.archive_updated_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified archive
     */
    public function destroy(Archive $archive): RedirectResponse
    {
        if (!auth()->guard('admin')->user()->can('archive.delete')) {
            abort(403, trans('all.unauthorized_action'));
        }

        try {
            $this->archiveService->deleteArchive($archive);

            return redirect()->route('admin.archives.index')
                ->with('success', trans('all.archive_deleted_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Download the archive file
     */
    public function download(Archive $archive)
    {
        if (!auth()->guard('admin')->user()->can('archive.view')) {
            abort(403, trans('all.unauthorized_action'));
        }

        $file = $archive->getPrimaryFile();

        if (!$file) {
            abort(404, trans('all.file_not_found'));
        }

        return response()->download($file->getPath(), $file->name);
    }

    /**
     * Search archives via AJAX
     */
    public function search(Request $request): JsonResponse
    {
        if (!auth()->guard('admin')->user()->can('archive.view')) {
            abort(403, trans('all.unauthorized_action'));
        }

        $query = $request->get('query', '');
        $filters = [
            'category_id' => $request->get('category_id'),
            'file_type' => $request->get('file_type')
        ];

        $archives = $this->archiveService->searchArchives($query, $filters);

        return response()->json($archives->map(function ($archive) {
            return [
                'id' => $archive->id,
                'title' => $archive->title,
                'slug' => $archive->slug,
                'category' => $archive->category->name,
                'file_type' => $archive->file_type,
                'file_size' => $archive->file_size,
                'created_at' => $archive->created_at->format('M j, Y'),
                'url' => route('admin.archives.show', $archive)
            ];
        }));
    }

    /**
     * Get archives by category
     */
    public function byCategory(Category $category, Request $request): JsonResponse
    {
        if (!auth()->guard('admin')->user()->can('archive.view')) {
            abort(403, trans('all.unauthorized_action'));
        }

        $includeSubcategories = $request->boolean('include_subcategories', true);
        $archives = $this->archiveService->getArchivesByCategory($category, $includeSubcategories);

        return response()->json([
            'category' => $category->name,
            'archives' => $archives->map(function ($archive) {
                return [
                    'id' => $archive->id,
                    'title' => $archive->title,
                    'file_type' => $archive->file_type,
                    'thumbnail' => $archive->getFirstMediaUrl('files', 'thumb'),
                    'url' => route('admin.archives.show', $archive)
                ];
            })
        ]);
    }

    /**
     * Bulk operations on archives
     */
    public function bulkAction(Request $request): JsonResponse
    {
        if (!auth()->guard('admin')->user()->can('archive.edit')) {
            abort(403, trans('all.unauthorized_action'));
        }

        $request->validate([
            'action' => 'required|in:delete,move,change_status',
            'archive_ids' => 'required|array',
            'archive_ids.*' => 'exists:archives,id',
            'category_id' => 'required_if:action,move|exists:categories,id',
            'status' => 'required_if:action,change_status|in:active,archived,draft'
        ]);

        try {
            $archiveIds = $request->archive_ids;
            $count = 0;

            switch ($request->action) {
                case 'delete':
                    foreach ($archiveIds as $archiveId) {
                        $archive = Archive::find($archiveId);
                        if ($archive) {
                            $this->archiveService->deleteArchive($archive);
                            $count++;
                        }
                    }
                    $message = trans('all.archives_deleted', ['count' => $count]);
                    break;

                case 'move':
                    $count = $this->archiveService->moveArchivesToCategory($archiveIds, $request->category_id);
                    $message = trans('all.archives_moved', ['count' => $count]);
                    break;

                case 'change_status':
                    $count = $this->archiveService->bulkUpdateStatus($archiveIds, $request->status);
                    $message = trans('all.archives_status_updated', ['count' => $count]);
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'count' => $count
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get archive statistics
     */
    public function statistics(): JsonResponse
    {
        if (!auth()->guard('admin')->user()->can('archive.view')) {
            abort(403, trans('all.unauthorized_action'));
        }

        $statistics = $this->archiveService->getArchiveStatistics();

        return response()->json($statistics);
    }

    /**
     * Find duplicate files
     */
    public function duplicates(): JsonResponse
    {
        if (!auth()->guard('admin')->user()->can('archive.view')) {
            abort(403, trans('all.unauthorized_action'));
        }

        $duplicates = $this->archiveService->findDuplicates();

        return response()->json($duplicates);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FileService;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FileController extends Controller
{
    protected $fileService;
    protected $categoryService;

    public function __construct(FileService $fileService, CategoryService $categoryService)
    {
        $this->fileService = $fileService;
        $this->categoryService = $categoryService;
    }

    /**
     * Display the file management page
     */
    public function index(): View
    {
        if (!auth()->guard('admin')->user()->can('file.view')) {
            abort(403, 'Unauthorized action.');
        }

        $statistics = $this->fileService->getFileStatistics();

        return view('admin.pages.files.index', compact('statistics'));
    }

    /**
     * Download a file
     */
    public function download($id)
    {
        if (!auth()->guard('admin')->user()->can('file.download')) {
            abort(403, 'Unauthorized action.');
        }

        $file = \App\Models\File::findOrFail($id);

        return $this->fileService->downloadFile($file);
    }

    /**
     * Show file details
     */
    public function show($id): View
    {
        if (!auth()->guard('admin')->user()->can('file.view')) {
            abort(403, 'Unauthorized action.');
        }

        $file = \App\Models\File::with(['category', 'uploader'])->findOrFail($id);

        return view('admin.pages.files.show', compact('file'));
    }

    /**
     * Get file statistics for dashboard
     */
    public function statistics()
    {
        if (!auth()->guard('admin')->user()->can('file.view')) {
            abort(403, 'Unauthorized action.');
        }

        return response()->json($this->fileService->getFileStatistics());
    }
}

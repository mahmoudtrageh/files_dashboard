<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Archive;
use App\Models\Category;
use App\Services\ArchiveService;
use App\Services\CategoryService;
use Illuminate\Http\UploadedFile;

class ArchiveManager extends Component
{
    use WithPagination, WithFileUploads;

    // Search and filters
    public $search = '';
    public $categoryFilter = '';
    public $fileTypeFilter = '';
    public $statusFilter = 'active';
    public $perPage = 20;
    public $sortBy = 'created_at';
    public $sortOrder = 'desc';

    // Create/Edit form
    public $showModal = false;
    public $modalTitle = '';
    public $editingArchive = null;
    public $title = '';
    public $categoryId = '';
    public $status = 'active';
    public $file = null;

    // Bulk actions
    public $selectedArchives = [];
    public $selectAll = false;
    public $showBulkActions = false;
    public $bulkAction = '';

    // View modes
    public $viewMode = 'grid'; // grid or list

    // States
    public $isLoading = false;
    public $uploading = false;

    protected $archiveService;
    protected $categoryService;

    protected $rules = [
        'title' => 'required|string|max:255',
        'categoryId' => 'required|exists:categories,id',
        'status' => 'in:active,archived,draft',
        'file' => 'nullable|file|max:102400' // 100MB
    ];

    protected $messages = [
        'title.required' => 'title_is_required',
        'categoryId.required' => 'category_is_required',
        'categoryId.exists' => 'selected_category_is_invalid',
        'file.max' => 'file_size_too_large'
    ];

    public function boot()
    {
        $this->archiveService = app(ArchiveService::class);
        $this->categoryService = app(CategoryService::class);
    }

    public function mount()
    {
        if (!auth()->guard('admin')->user()->can('archive.view')) {
            abort(403, trans('all.unauthorized_action'));
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatedFileTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedArchives = $this->getArchives()->pluck('id')->toArray();
        } else {
            $this->selectedArchives = [];
        }
        $this->updateBulkActionsVisibility();
    }

    public function updatedSelectedArchives()
    {
        $this->updateBulkActionsVisibility();
    }

    public function showCreateModal()
    {
        if (!auth()->guard('admin')->user()->can('archive.create')) {
            $this->dispatch('error', ['message' => trans('all.unauthorized_action')]);
            return;
        }

        $this->resetForm();
        $this->modalTitle = trans('all.Add File');
        $this->showModal = true;
    }

    public function showEditModal($archiveId)
    {
        if (!auth()->guard('admin')->user()->can('archive.edit')) {
            $this->dispatch('error', ['message' => trans('all.unauthorized_action')]);
            return;
        }

        $this->editingArchive = Archive::findOrFail($archiveId);
        $this->title = $this->editingArchive->title;
        $this->categoryId = $this->editingArchive->category_id;
        $this->status = $this->editingArchive->status;
        $this->modalTitle = trans('all.Edit File');
        $this->showModal = true;
    }

    public function showViewModal($archiveId)
    {
        if (!auth()->guard('admin')->user()->can('archive.view')) {
            $this->dispatch('error', ['message' => trans('all.unauthorized_action')]);
            return;
        }

        $archive = Archive::with(['category', 'media'])->findOrFail($archiveId);
        $this->dispatch('showArchiveDetails', ['archive' => $archive]);
    }

    public function saveArchive()
    {
        $this->uploading = true;

        // Adjust validation rules based on whether we're creating or editing
        $rules = $this->rules;
        if (!$this->editingArchive) {
            $rules['file'] = 'required|file|max:102400';
        }

        $this->validate($rules);

        try {
            $data = [
                'title' => $this->title,
                'category_id' => $this->categoryId,
                'status' => $this->status
            ];

            if ($this->editingArchive) {
                $this->archiveService->updateArchive($this->editingArchive, $data, $this->file);
                $message = trans('all.archive_updated_successfully');
            } else {
                if (!$this->file) {
                    $this->addError('file', trans('all.File is required'));
                    return;
                }

                $this->archiveService->createArchive($data, $this->file);
                $message = trans('all.archive_created_successfully');
            }

            $this->hideModal();
            $this->dispatch('success', ['message' => $message]);
            $this->resetPage();

        } catch (\Exception $e) {
            $this->addError('title', $e->getMessage());
        } finally {
            $this->uploading = false;
        }
    }

    public function deleteArchive($archiveId)
    {
        if (!auth()->guard('admin')->user()->can('archive.delete')) {
            $this->dispatch('error', ['message' => trans('all.unauthorized_action')]);
            return;
        }

        try {
            $archive = Archive::findOrFail($archiveId);
            $this->archiveService->deleteArchive($archive);

            $this->dispatch('success', [
                'message' => trans('all.archive_deleted_successfully')
            ]);

        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => $e->getMessage()]);
        }
    }

    public function downloadArchive($archiveId)
    {
        if (!auth()->guard('admin')->user()->can('archive.view')) {
            $this->dispatch('error', ['message' => trans('all.unauthorized_action')]);
            return;
        }

        $archive = Archive::findOrFail($archiveId);
        $file = $archive->getPrimaryFile();

        if (!$file) {
            $this->dispatch('error', ['message' => trans('all.File not found')]);
            return;
        }

        return response()->download($file->getPath(), $file->name);
    }

    public function executeBulkAction()
    {
        if (empty($this->selectedArchives) || empty($this->bulkAction)) {
            return;
        }

        if (!auth()->guard('admin')->user()->can('archive.edit')) {
            $this->dispatch('error', ['message' => trans('all.unauthorized_action')]);
            return;
        }

        try {
            switch ($this->bulkAction) {
                case 'delete':
                    $count = 0;
                    foreach ($this->selectedArchives as $archiveId) {
                        try {
                            $archive = Archive::find($archiveId);
                            if ($archive) {
                                $this->archiveService->deleteArchive($archive);
                                $count++;
                            }
                        } catch (\Exception $e) {
                            continue;
                        }
                    }
                    $message = trans('all.:count archives deleted', ['count' => $count]);
                    break;

                case 'activate':
                    $count = $this->archiveService->bulkUpdateStatus($this->selectedArchives, 'active');
                    $message = trans('all.:count archives activated', ['count' => $count]);
                    break;

                case 'archive':
                    $count = $this->archiveService->bulkUpdateStatus($this->selectedArchives, 'archived');
                    $message = trans('all.:count archives archived', ['count' => $count]);
                    break;
            }

            $this->resetBulkActions();
            $this->dispatch('success', ['message' => $message]);

        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => $e->getMessage()]);
        }
    }

    public function hideModal()
    {
        $this->showModal = false;
        $this->editingArchive = null;
        $this->resetForm();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->categoryFilter = '';
        $this->fileTypeFilter = '';
        $this->statusFilter = 'active';
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortOrder = $this->sortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortOrder = 'asc';
        }
        $this->resetPage();
    }

    private function getArchives()
    {
        $filters = [
            'search' => $this->search,
            'category_id' => $this->categoryFilter,
            'file_type' => $this->fileTypeFilter,
            'status' => $this->statusFilter,
            'sort_by' => $this->sortBy,
            'sort_order' => $this->sortOrder
        ];

        return $this->archiveService->getPaginatedArchives($filters, $this->perPage);
    }

    private function getCategories()
    {
        return $this->categoryService->getCategoriesForSelect();
    }

    private function getStatistics()
    {
        return $this->archiveService->getArchiveStatistics();
    }

    private function resetForm()
    {
        $this->title = '';
        $this->categoryId = '';
        $this->status = 'active';
        $this->file = null;
        $this->resetErrorBag();
    }

    private function resetBulkActions()
    {
        $this->selectedArchives = [];
        $this->selectAll = false;
        $this->showBulkActions = false;
        $this->bulkAction = '';
    }

    private function updateBulkActionsVisibility()
    {
        $this->showBulkActions = !empty($this->selectedArchives);
    }

    public function render()
    {
        return view('livewire.admin.archive-manager', [
            'archives' => $this->getArchives(),
            'categories' => $this->getCategories(),
            'statistics' => $this->getStatistics(),
            'fileTypes' => $this->getFileTypes()
        ]);
    }

    private function getFileTypes()
    {
        return [
            'images' => trans('all.Images'),
            'documents' => trans('all.Documents'),
            'videos' => trans('all.Videos'),
            'audio' => trans('all.Audio')
        ];
    }
}

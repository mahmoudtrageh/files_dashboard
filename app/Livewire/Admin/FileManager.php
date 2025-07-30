<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\File;
use App\Models\Category;
use App\Services\FileService;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Storage;

class FileManager extends Component
{
    use WithFileUploads, WithPagination;

    // Search and filters
    public $search = '';
    public $categoryFilter = '';
    public $typeFilter = 'all';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 15;

    // Upload form
    public $showUploadModal = false;
    public $uploadFile;
    public $uploadTitle = '';
    public $uploadDescription = '';
    public $uploadCategory = '';
    public $uploadIsPublic = false;
    public $newCategoryName = '';
    public $showNewCategoryInput = false;

    // Edit form
    public $showEditModal = false;
    public $editingFile;
    public $editTitle = '';
    public $editDescription = '';
    public $editCategory = '';
    public $editIsPublic = false;

    // Bulk actions
    public $selectedFiles = [];
    public $selectAll = false;
    public $showBulkActions = false;
    public $bulkAction = '';
    public $bulkMoveCategory = '';

    // View mode
    public $viewMode = 'grid'; // grid or list

    protected $fileService;
    protected $categoryService;

    public function boot()
    {
        $this->fileService = app(FileService::class);
        $this->categoryService = app(CategoryService::class);
    }

    public function mount()
    {
        $this->categoryFilter = request('category', '');
        $this->search = request('search', '');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatedTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedFiles = $this->getFiles()->pluck('id')->toArray();
        } else {
            $this->selectedFiles = [];
        }
        $this->updateBulkActionsVisibility();
    }

    public function updatedSelectedFiles()
    {
        $this->updateBulkActionsVisibility();
    }

    public function showUploadForm()
    {
        $this->resetUploadForm();
        $this->showUploadModal = true;
    }

    public function hideUploadForm()
    {
        $this->showUploadModal = false;
        $this->resetUploadForm();
    }

    public function toggleNewCategoryInput()
    {
        $this->showNewCategoryInput = !$this->showNewCategoryInput;
        if ($this->showNewCategoryInput) {
            $this->uploadCategory = '';
        }
    }

    public function createNewCategory()
    {
        $this->validate([
            'newCategoryName' => 'required|string|max:255|unique:categories,name'
        ]);

        try {
            $category = $this->categoryService->createCategoryFromName($this->newCategoryName);
            $this->uploadCategory = $category->id;
            $this->newCategoryName = '';
            $this->showNewCategoryInput = false;

            $this->dispatch('categoryCreated', [
                'message' => __('Category created successfully')
            ]);
        } catch (\Exception $e) {
            $this->addError('newCategoryName', $e->getMessage());
        }
    }

    public function uploadFiles()
    {
        $this->validate([
            'uploadFile' => 'required|file|max:51200', // 50MB max
            'uploadTitle' => 'required|string|max:255',
            'uploadDescription' => 'nullable|string',
            'uploadCategory' => 'nullable|exists:categories,id'
        ]);

        try {
            $this->fileService->uploadFile(
                $this->uploadFile,
                [
                    'title' => $this->uploadTitle,
                    'description' => $this->uploadDescription,
                    'category_id' => $this->uploadCategory ?: null,
                    'is_public' => $this->uploadIsPublic
                ],
                auth()->guard('admin')->id()
            );

            $this->hideUploadForm();
            $this->dispatch('fileUploaded', [
                'message' => __('File uploaded successfully')
            ]);

        } catch (\Exception $e) {
            $this->addError('uploadFile', $e->getMessage());
        }
    }

    public function editFile($fileId)
    {
        $this->editingFile = File::findOrFail($fileId);
        $this->editTitle = $this->editingFile->title;
        $this->editDescription = $this->editingFile->description;
        $this->editCategory = $this->editingFile->category_id;
        $this->editIsPublic = $this->editingFile->is_public;
        $this->showEditModal = true;
    }

    public function updateFile()
    {
        $this->validate([
            'editTitle' => 'required|string|max:255',
            'editDescription' => 'nullable|string',
            'editCategory' => 'nullable|exists:categories,id'
        ]);

        try {
            $this->fileService->updateFile($this->editingFile, [
                'title' => $this->editTitle,
                'description' => $this->editDescription,
                'category_id' => $this->editCategory ?: null,
                'is_public' => $this->editIsPublic
            ]);

            $this->hideEditForm();
            $this->dispatch('fileUpdated', [
                'message' => __('File updated successfully')
            ]);

        } catch (\Exception $e) {
            $this->addError('editTitle', $e->getMessage());
        }
    }

    public function hideEditForm()
    {
        $this->showEditModal = false;
        $this->editingFile = null;
        $this->resetEditForm();
    }

    public function deleteFile($fileId)
    {
        try {
            $file = File::findOrFail($fileId);
            $this->fileService->deleteFile($file);

            $this->dispatch('fileDeleted', [
                'message' => __('File deleted successfully')
            ]);

        } catch (\Exception $e) {
            $this->dispatch('error', [
                'message' => $e->getMessage()
            ]);
        }
    }

    public function downloadFile($fileId)
    {
        $file = File::findOrFail($fileId);
        return $this->fileService->downloadFile($file);
    }

    public function executeBulkAction()
    {
        if (empty($this->selectedFiles) || empty($this->bulkAction)) {
            return;
        }

        try {
            switch ($this->bulkAction) {
                case 'delete':
                    $count = $this->fileService->bulkDeleteFiles($this->selectedFiles);
                    $this->dispatch('bulkActionCompleted', [
                        'message' => __(':count files deleted', ['count' => $count])
                    ]);
                    break;

                case 'move':
                    if (empty($this->bulkMoveCategory)) {
                        $this->addError('bulkMoveCategory', 'Please select a category');
                        return;
                    }
                    $count = $this->fileService->moveFilesToCategory(
                        $this->selectedFiles,
                        $this->bulkMoveCategory ?: null
                    );
                    $this->dispatch('bulkActionCompleted', [
                        'message' => __(':count files moved', ['count' => $count])
                    ]);
                    break;

                case 'public':
                    File::whereIn('id', $this->selectedFiles)->update(['is_public' => true]);
                    $this->dispatch('bulkActionCompleted', [
                        'message' => __('Files made public')
                    ]);
                    break;

                case 'private':
                    File::whereIn('id', $this->selectedFiles)->update(['is_public' => false]);
                    $this->dispatch('bulkActionCompleted', [
                        'message' => __('Files made private')
                    ]);
                    break;
            }

            $this->resetBulkActions();

        } catch (\Exception $e) {
            $this->dispatch('error', [
                'message' => $e->getMessage()
            ]);
        }
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'desc';
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->categoryFilter = '';
        $this->typeFilter = 'all';
        $this->resetPage();
    }

    private function getFiles()
    {
        return $this->fileService->getPaginatedFiles(
            $this->search,
            $this->categoryFilter ?: null,
            $this->typeFilter,
            $this->perPage,
            $this->sortBy,
            $this->sortDirection
        );
    }

    private function getCategories()
    {
        return $this->categoryService->getCategoriesForSelect();
    }

    private function resetUploadForm()
    {
        $this->uploadFile = null;
        $this->uploadTitle = '';
        $this->uploadDescription = '';
        $this->uploadCategory = '';
        $this->uploadIsPublic = false;
        $this->newCategoryName = '';
        $this->showNewCategoryInput = false;
    }

    private function resetEditForm()
    {
        $this->editTitle = '';
        $this->editDescription = '';
        $this->editCategory = '';
        $this->editIsPublic = false;
    }

    private function resetBulkActions()
    {
        $this->selectedFiles = [];
        $this->selectAll = false;
        $this->showBulkActions = false;
        $this->bulkAction = '';
        $this->bulkMoveCategory = '';
    }

    private function updateBulkActionsVisibility()
    {
        $this->showBulkActions = !empty($this->selectedFiles);
    }

    public function render()
    {
        return view('livewire.admin.file-manager', [
            'files' => $this->getFiles(),
            'categories' => $this->getCategories(),
            'fileTypes' => [
                'all' => __('All Types'),
                'image' => __('Images'),
                'video' => __('Videos'),
                'audio' => __('Audio'),
                'document' => __('Documents'),
                'office' => __('Office Files'),
                'archive' => __('Archives'),
                'other' => __('Other')
            ]
        ]);
    }
}

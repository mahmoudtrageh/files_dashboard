<?php

namespace App\Services;

use App\Models\File;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class FileService
{
    /**
     * Get paginated files with filters
     */
    public function getPaginatedFiles(
        ?string $search = null,
        ?int $categoryId = null,
        ?string $fileType = null,
        int $perPage = 15,
        string $sortBy = 'created_at',
        string $sortDirection = 'desc'
    ): LengthAwarePaginator {
        $query = File::query()
            ->with(['category', 'uploader'])
            ->active();

        // Apply search filter
        if ($search) {
            $query->search($search);
        }

        // Apply category filter
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Apply file type filter
        if ($fileType && $fileType !== 'all') {
            $query->ofType($fileType);
        }

        // Apply sorting
        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * Upload and store file
     */
    public function uploadFile(
        UploadedFile $uploadedFile,
        array $data,
        int $uploadedBy
    ): File {
        // Generate unique filename
        $originalName = $uploadedFile->getClientOriginalName();
        $extension = $uploadedFile->getClientOriginalExtension();
        $fileName = time() . '_' . Str::random(10) . '.' . $extension;

        // Store file
        $filePath = $uploadedFile->storeAs('files', $fileName, 'public');

        // Get file metadata
        $metadata = $this->extractFileMetadata($uploadedFile, $filePath);

        // Create file record
        return File::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'original_name' => $originalName,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'mime_type' => $uploadedFile->getMimeType(),
            'extension' => $extension,
            'size' => $uploadedFile->getSize(),
            'metadata' => $metadata,
            'category_id' => $data['category_id'] ?? null,
            'uploaded_by' => $uploadedBy,
            'is_public' => $data['is_public'] ?? false,
            'is_active' => true
        ]);
    }

    /**
     * Update file
     */
    public function updateFile(File $file, array $data): File
    {
        $file->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? $file->description,
            'category_id' => $data['category_id'] ?? $file->category_id,
            'is_public' => $data['is_public'] ?? $file->is_public
        ]);

        return $file->fresh();
    }

    /**
     * Delete file
     */
    public function deleteFile(File $file): bool
    {
        return $file->delete();
    }

    /**
     * Get file statistics
     */
    public function getFileStatistics(): array
    {
        return [
            'total_files' => File::active()->count(),
            'total_size' => File::active()->sum('size'),
            'files_by_type' => File::active()
                ->selectRaw('
                    CASE
                        WHEN mime_type LIKE "image/%" THEN "image"
                        WHEN mime_type LIKE "video/%" THEN "video"
                        WHEN mime_type LIKE "audio/%" THEN "audio"
                        WHEN mime_type = "application/pdf" THEN "document"
                        ELSE "other"
                    END as file_type,
                    COUNT(*) as count
                ')
                ->groupBy('file_type')
                ->pluck('count', 'file_type')
                ->toArray(),
            'files_by_category' => Category::withCount('files')
                ->having('files_count', '>', 0)
                ->pluck('files_count', 'name')
                ->toArray(),
            'recent_uploads' => File::active()
                ->with(['category', 'uploader'])
                ->latest()
                ->limit(5)
                ->get(),
            'popular_files' => File::active()
                ->with(['category', 'uploader'])
                ->orderBy('download_count', 'desc')
                ->limit(5)
                ->get()
        ];
    }

    /**
     * Extract file metadata
     */
    private function extractFileMetadata(UploadedFile $file, string $filePath): array
    {
        $metadata = [
            'original_name' => $file->getClientOriginalName(),
            'uploaded_at' => now()->toISOString()
        ];

        // For images, get dimensions
        if (Str::startsWith($file->getMimeType(), 'image/')) {
            $fullPath = Storage::disk('public')->path($filePath);
            if (file_exists($fullPath)) {
                [$width, $height] = getimagesize($fullPath);
                $metadata['dimensions'] = [
                    'width' => $width,
                    'height' => $height
                ];
            }
        }

        return $metadata;
    }

    /**
     * Download file and increment counter
     */
    public function downloadFile(File $file): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $file->incrementDownloadCount();

        return Storage::disk('public')->download(
            $file->file_path,
            $file->original_name
        );
    }

    /**
     * Get files by category including subcategories
     */
    public function getFilesByCategory(Category $category, int $perPage = 15): LengthAwarePaginator
    {
        // Get all category IDs including subcategories
        $categoryIds = $this->getCategoryWithChildren($category);

        return File::query()
            ->with(['category', 'uploader'])
            ->active()
            ->whereIn('category_id', $categoryIds)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get category IDs including all children
     */
    private function getCategoryWithChildren(Category $category): array
    {
        $categoryIds = [$category->id];

        foreach ($category->children as $child) {
            $categoryIds = array_merge($categoryIds, $this->getCategoryWithChildren($child));
        }

        return $categoryIds;
    }

    /**
     * Bulk delete files
     */
    public function bulkDeleteFiles(array $fileIds): int
    {
        $files = File::whereIn('id', $fileIds)->get();
        $deletedCount = 0;

        foreach ($files as $file) {
            if ($this->deleteFile($file)) {
                $deletedCount++;
            }
        }

        return $deletedCount;
    }

    /**
     * Move files to different category
     */
    public function moveFilesToCategory(array $fileIds, ?int $categoryId): int
    {
        return File::whereIn('id', $fileIds)->update(['category_id' => $categoryId]);
    }
}

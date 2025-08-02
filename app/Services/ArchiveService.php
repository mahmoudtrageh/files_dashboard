<?php

namespace App\Services;

use App\Models\Archive;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class ArchiveService
{
    // Allowed mime types with their categories
    private const ALLOWED_MIME_TYPES = [
        'images' => [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml',
        ],
        'documents' => [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
            'text/csv',
        ],
        'audio' => [
            'audio/mpeg',
            'audio/mp3',
            'audio/ogg',
            'audio/wav',
            'audio/webm',
        ],
        'video' => [
            'video/mp4',
            'video/avi',
            'video/quicktime',
            'video/x-msvideo',
            'video/x-ms-wmv',
            'video/webm',
        ],
        'archives' => [
            'application/zip',
            'application/x-rar-compressed',
            'application/x-7z-compressed',
        ],
    ];

    private const MAX_FILE_SIZE = 104857600; // 100MB

    /**
     * Get paginated archives with filters
     */
    public function getPaginatedArchives(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Archive::with(['category', 'media'])
            ->active();

        // Apply filters
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (!empty($filters['category_id'])) {
            $query->inCategory($filters['category_id']);
        }

        if (!empty($filters['file_type'])) {
            $query->byFileType($filters['file_type']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Sort
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';

        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }

    /**
     * Create new archive with file
     */
    public function createArchive(array $data, UploadedFile $file): Archive
    {
        $this->validateFile($file);

        return DB::transaction(function () use ($data, $file) {
            $archive = Archive::create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'category_id' => $data['category_id'],
                'status' => $data['status'] ?? 'active',
                'metadata' => $this->buildMetadata($file),
            ]);

            $this->attachFileToArchive($archive, $file, $data['title']);

            return $archive->load(['category', 'media']);
        });
    }

    /**
     * Update archive
     */
    public function updateArchive(Archive $archive, array $data, ?UploadedFile $file = null): Archive
    {
        return DB::transaction(function () use ($archive, $data, $file) {
            $updateData = [
                'title' => $data['title'],
                'description' => $data['description'] ?? $archive->description,
                'category_id' => $data['category_id'],
                'status' => $data['status'] ?? $archive->status,
            ];

            if ($file) {
                $this->validateFile($file);
                $updateData['metadata'] = $this->buildMetadata($file);
            }

            $archive->update($updateData);

            if ($file) {
                $this->attachFileToArchive($archive, $file, $data['title'], true);
            }

            return $archive->fresh(['category', 'media']);
        });
    }

    /**
     * Validate uploaded file
     */
    private function validateFile(UploadedFile $file): void
    {
        if (!$file->isValid()) {
            throw new \Exception(trans('validation.file_upload_error', [], 'File upload error'));
        }

        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new \Exception(trans('validation.file_size_too_large', [], 'File size too large (max 100MB)'));
        }

        $mimeType = $file->getMimeType();
        $allowedMimes = array_merge(...array_values(self::ALLOWED_MIME_TYPES));

        if (!in_array($mimeType, $allowedMimes)) {
            throw new \Exception(trans('validation.file_type_not_allowed', [], 'File type not allowed'));
        }
    }

    /**
     * Build metadata array from file
     */
    private function buildMetadata(UploadedFile $file): array
    {
        return [
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
            'uploaded_at' => now()->toDateTimeString(),
        ];
    }

    /**
     * Attach file to archive - IMPROVED VERSION
     */
    private function attachFileToArchive(Archive $archive, UploadedFile $file, string $title, bool $replace = false): void
    {
        try {
            if ($replace) {
                $archive->clearMediaCollection('files');
            }

            $mediaAdder = $archive->addMedia($file)
                ->usingName($title)
                ->usingFileName($this->sanitizeFileName($file->getClientOriginalName()));

            // Add custom properties based on file type
            $fileType = $this->getFileType($file);
            $mediaAdder->withCustomProperties([
                'file_type' => $fileType,
                'original_extension' => $file->getClientOriginalExtension(),
            ]);

            $mediaAdder->toMediaCollection('files');

        } catch (FileDoesNotExist $e) {
            throw new \Exception('File does not exist');
        } catch (FileIsTooBig $e) {
            throw new \Exception('File size too large');
        } catch (\Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage(), [
                'archive_id' => $archive->id ?? 'new',
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'error' => $e->getTraceAsString()
            ]);
            throw new \Exception('File upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Get file type category from uploaded file
     */
    private function getFileType(UploadedFile $file): string
    {
        $mimeType = $file->getMimeType();

        foreach (self::ALLOWED_MIME_TYPES as $type => $mimes) {
            if (in_array($mimeType, $mimes)) {
                return $type;
            }
        }

        return 'other';
    }

    /**
     * Delete archive
     */
    public function deleteArchive(Archive $archive): bool
    {
        return DB::transaction(function () use ($archive) {
            // Media will be deleted automatically via model events
            return $archive->delete();
        });
    }

    /**
     * Get archive statistics
     */
    public function getArchiveStatistics(): array
    {
        try {
            return [
                'total_archives' => Archive::active()->count(),
                'total_size' => $this->getTotalStorageSize(),
                'by_type' => $this->getArchivesByType(),
                'by_category' => $this->getArchivesCountByCategory(),
                'recent_archives' => $this->getRecentArchives(5),
                'popular_categories' => $this->getPopularCategories(5),
                'storage_by_category' => $this->getStorageByCategory()
            ];
        } catch (\Exception $e) {
            Log::error('Error getting archive statistics: ' . $e->getMessage());
            return $this->getDefaultStatistics();
        }
    }

    /**
     * Search archives
     */
    public function searchArchives(string $query, array $filters = [], int $limit = 50): Collection
    {
        $archives = Archive::with(['category', 'media'])
            ->active()
            ->search($query);

        // Apply additional filters
        if (!empty($filters['category_id'])) {
            $archives->inCategory($filters['category_id']);
        }

        if (!empty($filters['file_type'])) {
            $archives->byFileType($filters['file_type']);
        }

        return $archives->limit($limit)->get();
    }

    /**
     * Get archives by category (including subcategories)
     */
    public function getArchivesByCategory(Category $category, bool $includeSubcategories = true): Collection
    {
        $query = Archive::with(['category', 'media'])->active();

        if ($includeSubcategories) {
            $categoryIds = $this->getCategoryWithDescendants($category);
            $query->whereIn('category_id', $categoryIds);
        } else {
            $query->inCategory($category->id);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Move archives to different category
     */
    public function moveArchivesToCategory(array $archiveIds, int $categoryId): int
    {
        return Archive::whereIn('id', $archiveIds)
            ->update(['category_id' => $categoryId]);
    }

    /**
     * Bulk update archive status
     */
    public function bulkUpdateStatus(array $archiveIds, string $status): int
    {
        $validStatuses = ['active', 'archived', 'draft'];

        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException('Invalid status provided');
        }

        return Archive::whereIn('id', $archiveIds)
            ->update(['status' => $status]);
    }

    /**
     * Find duplicate files based on hash
     */
    public function findDuplicates(): Collection
    {
        return DB::table('media')
            ->select('sha1', DB::raw('COUNT(*) as count'), DB::raw('GROUP_CONCAT(id) as media_ids'))
            ->where('model_type', Archive::class)
            ->whereNotNull('sha1')
            ->groupBy('sha1')
            ->having('count', '>', 1)
            ->get()
            ->map(function ($duplicate) {
                $mediaIds = explode(',', $duplicate->media_ids);
                $archives = Archive::whereHas('media', function ($query) use ($mediaIds) {
                    $query->whereIn('id', $mediaIds);
                })->with('media', 'category')->get();

                return [
                    'sha1' => $duplicate->sha1,
                    'count' => $duplicate->count,
                    'archives' => $archives->map(function ($archive) {
                        return [
                            'id' => $archive->id,
                            'title' => $archive->title,
                            'category' => $archive->category->name ?? 'Uncategorized',
                            'size' => $archive->file_size,
                            'created_at' => $archive->created_at->format('M j, Y')
                        ];
                    })
                ];
            });
    }

    /**
     * Get total storage size
     */
    private function getTotalStorageSize(): string
    {
        $totalBytes = DB::table('media')
            ->where('model_type', Archive::class)
            ->sum('size') ?? 0;

        return $this->formatBytes($totalBytes);
    }

    /**
     * Get archives count by type
     */
    private function getArchivesByType(): array
    {
        $types = [
            'images' => 0,
            'documents' => 0,
            'videos' => 0,
            'audio' => 0,
            'archives' => 0,
            'others' => 0
        ];

        // Get archives with their media
        $archives = Archive::with('media')->active()->get();

        foreach ($archives as $archive) {
            $file = $archive->getPrimaryFile();
            if (!$file) {
                $types['others']++;
                continue;
            }

            $mimeType = $file->mime_type;
            $typeFound = false;

            foreach (self::ALLOWED_MIME_TYPES as $type => $mimes) {
                if (in_array($mimeType, $mimes)) {
                    $types[$type]++;
                    $typeFound = true;
                    break;
                }
            }

            if (!$typeFound) {
                $types['others']++;
            }
        }

        return $types;
    }

    /**
     * Get archives count by category (for statistics)
     */
    private function getArchivesCountByCategory(): Collection
    {
        try {
            return Archive::select('category_id', DB::raw('count(*) as count'))
                ->with('category:id,name,color,icon')
                ->active()
                ->groupBy('category_id')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            Log::error('Error getting archives by category: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get recent archives
     */
    private function getRecentArchives(int $limit = 5): Collection
    {
        return Archive::with(['category', 'media'])
            ->active()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get popular categories
     */
    private function getPopularCategories(int $limit = 5): Collection
    {
        return Category::select('categories.*', DB::raw('count(archives.id) as archives_count'))
            ->leftJoin('archives', 'categories.id', '=', 'archives.category_id')
            ->where('archives.status', 'active')
            ->groupBy('categories.id')
            ->orderBy('archives_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get storage usage by category
     */
    private function getStorageByCategory(): Collection
    {
        try {
            return Archive::select('category_id', DB::raw('COUNT(*) as archives_count'))
                ->with(['category:id,name,color,icon'])
                ->selectSub(function ($query) {
                    $query->select(DB::raw('COALESCE(SUM(media.size), 0)'))
                          ->from('media')
                          ->whereColumn('media.model_id', 'archives.id')
                          ->where('media.model_type', Archive::class);
                }, 'total_size')
                ->active()
                ->groupBy('category_id')
                ->orderByDesc('total_size')
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'category' => $item->category,
                        'archives_count' => $item->archives_count,
                        'total_size' => $this->formatBytes($item->total_size ?? 0),
                        'total_size_bytes' => $item->total_size ?? 0
                    ];
                });
        } catch (\Exception $e) {
            Log::error('Error getting storage by category: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get category with all descendants
     */
    private function getCategoryWithDescendants(Category $category): array
    {
        $categoryIds = [$category->id];
        $this->collectDescendantIds($category, $categoryIds);
        return $categoryIds;
    }

    /**
     * Recursively collect descendant category IDs
     */
    private function collectDescendantIds(Category $category, array &$ids): void
    {
        if ($category->relationLoaded('children') || $category->children()->exists()) {
            foreach ($category->children as $child) {
                $ids[] = $child->id;
                $this->collectDescendantIds($child, $ids);
            }
        }
    }

    /**
     * Sanitize filename
     */
    private function sanitizeFileName(string $fileName): string
    {
        // Get file extension
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $name = pathinfo($fileName, PATHINFO_FILENAME);

        // Clean the name
        $name = preg_replace('/[^a-zA-Z0-9_-]/', '-', $name);
        $name = preg_replace('/-+/', '-', $name); // Remove multiple dashes
        $name = trim($name, '-');

        // Ensure we have a name
        if (empty($name)) {
            $name = 'file-' . time();
        }

        // Limit length
        $name = substr($name, 0, 100);

        return strtolower($name . '.' . $extension);
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Get default statistics when error occurs
     */
    private function getDefaultStatistics(): array
    {
        return [
            'total_archives' => 0,
            'total_size' => '0 B',
            'by_type' => [
                'images' => 0,
                'documents' => 0,
                'videos' => 0,
                'audio' => 0,
                'archives' => 0,
                'others' => 0
            ],
            'by_category' => collect(),
            'recent_archives' => collect(),
            'popular_categories' => collect(),
            'storage_by_category' => collect()
        ];
    }
}

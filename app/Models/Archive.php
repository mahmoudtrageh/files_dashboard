<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Archive extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'status',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Category relationship
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Configure media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('files')
            ->acceptsMimeTypes([
                // Images
                'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
                // Documents
                'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'text/plain', 'text/csv',
                // Videos
                'video/mp4', 'video/avi', 'video/quicktime', 'video/x-msvideo', 'video/webm',
                // Audio
                'audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/ogg',
                // Archives
                'application/zip', 'application/x-rar-compressed', 'application/x-7z-compressed'
            ])
            ->singleFile(); // Only one file per archive
    }

    /**
     * Configure media conversions - FIXED VERSION
     */
    public function registerMediaConversions(Media $media = null): void
    {
        // Only create conversions for images
        if ($media && str_starts_with($media->mime_type, 'image/')) {
            $this->addMediaConversion('thumb')
                ->width(150)
                ->height(150)
                ->optimize()
                ->nonQueued()
                ->performOnCollections('files');

            $this->addMediaConversion('preview')
                ->width(500)
                ->height(500)
                ->optimize()
                ->nonQueued()
                ->performOnCollections('files');
        }
    }

    /**
     * Scope for active archives
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for archived items
     */
    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('status', 'archived');
    }

    /**
     * Scope for draft items
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for search
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->whereFullText(['title'], $search)
            ->orWhere('title', 'like', "%{$search}%");
    }

    /**
     * Scope by category
     */
    public function scopeInCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope by file type
     */
    public function scopeByFileType(Builder $query, string $type): Builder
    {
        return $query->whereHas('media', function ($mediaQuery) use ($type) {
            switch ($type) {
                case 'images':
                    $mediaQuery->where('mime_type', 'like', 'image/%');
                    break;
                case 'documents':
                    $mediaQuery->whereIn('mime_type', [
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-powerpoint',
                        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        'text/plain',
                        'text/csv'
                    ]);
                    break;
                case 'videos':
                    $mediaQuery->where('mime_type', 'like', 'video/%');
                    break;
                case 'audio':
                    $mediaQuery->where('mime_type', 'like', 'audio/%');
                    break;
                default:
                    // For 'others' or unknown types
                    $mediaQuery->where('mime_type', 'not like', 'image/%')
                              ->where('mime_type', 'not like', 'video/%')
                              ->where('mime_type', 'not like', 'audio/%')
                              ->whereNotIn('mime_type', [
                                  'application/pdf',
                                  'application/msword',
                                  'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                  'application/vnd.ms-excel',
                                  'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                  'application/vnd.ms-powerpoint',
                                  'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                  'text/plain',
                                  'text/csv'
                              ]);
                    break;
            }
        });
    }

    /**
     * Get primary file
     */
    public function getPrimaryFile()
    {
        return $this->getFirstMedia('files');
    }

    /**
     * Get file type based on primary file
     */
    public function getFileTypeAttribute(): string
    {
        $file = $this->getPrimaryFile();

        if (!$file) {
            return 'unknown';
        }

        $mimeType = $file->mime_type;

        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        } elseif (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        } elseif (in_array($mimeType, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain'
        ])) {
            return 'document';
        } else {
            return 'file';
        }
    }

    /**
     * Get file size in human readable format
     */
    public function getFileSizeAttribute(): string
    {
        $file = $this->getPrimaryFile();

        if (!$file || !$file->size) {
            return '0 B';
        }

        return $this->formatBytes($file->size);
    }

    /**
     * Get file extension
     */
    public function getFileExtensionAttribute(): string
    {
        $file = $this->getPrimaryFile();

        if (!$file) {
            return '';
        }

        return pathinfo($file->name, PATHINFO_EXTENSION);
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
     * Get category breadcrumb
     */
    public function getCategoryBreadcrumbAttribute(): array
    {
        return $this->category ? $this->category->breadcrumb : [];
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($archive) {
            $archive->clearMediaCollection('files');
        });
    }
}

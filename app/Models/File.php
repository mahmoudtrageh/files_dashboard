<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\File as MediaFile;

class File extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'file_type',
        'mime_type',
        'size',
        'original_name',
        'extension',
        'is_active',
        'uploaded_by',
        'metadata'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'size' => 'integer',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Define media collections
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('files')
            ->acceptsMimeTypes([
                // Images
                'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
                // Videos
                'video/mp4', 'video/avi', 'video/mov', 'video/wmv', 'video/webm', 'video/mkv',
                // Documents
                'application/pdf', 'text/plain', 'text/csv',
                // Office
                'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                // Archives
                'application/zip', 'application/x-rar-compressed', 'application/x-7z-compressed',
                // Audio
                'audio/mp3', 'audio/wav', 'audio/ogg', 'audio/m4a'
            ])
            ->singleFile();

        $this->addMediaCollection('thumbnails')
            ->acceptsMimeTypes(['image/jpeg', 'image/png']);
    }

    // Define media conversions
    public function registerMediaConversions(Media $media = null): void
    {
        // For images - create thumbnails
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 150, 150)
            ->optimize()
            ->quality(85)
            ->performOnCollections('files')
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->fit(Manipulations::FIT_MAX, 800, 600)
            ->optimize()
            ->quality(90)
            ->performOnCollections('files')
            ->nonQueued();

        // For videos - extract thumbnail
        $this->addMediaConversion('video_thumb')
            ->extractVideoFrameAtSecond(1)
            ->fit(Manipulations::FIT_CROP, 300, 200)
            ->performOnCollections('files')
            ->nonQueued();
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'uploaded_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('file_type', $type);
    }

    public function scopeByCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeImages($query)
    {
        return $query->where('file_type', 'image');
    }

    public function scopeVideos($query)
    {
        return $query->where('file_type', 'video');
    }

    public function scopeDocuments($query)
    {
        return $query->whereIn('file_type', ['document', 'office']);
    }

    // Accessors
    public function getFormattedSizeAttribute(): string
    {
        return formatBytes($this->size);
    }

    public function getFileUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('files');
        return $media ? $media->getUrl() : null;
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('files');

        if (!$media) {
            return null;
        }

        // For videos, try to get video thumbnail
        if ($this->file_type === 'video') {
            try {
                return $media->getUrl('video_thumb');
            } catch (\Exception $e) {
                return null;
            }
        }

        // For images, get thumbnail
        if ($this->file_type === 'image') {
            try {
                return $media->getUrl('thumb');
            } catch (\Exception $e) {
                return $media->getUrl();
            }
        }

        return null;
    }

    public function getMediumUrlAttribute(): ?string
    {
        if ($this->file_type !== 'image') {
            return $this->file_url;
        }

        $media = $this->getFirstMedia('files');

        if (!$media) {
            return null;
        }

        try {
            return $media->getUrl('medium');
        } catch (\Exception $e) {
            return $media->getUrl();
        }
    }

    public function getIconAttribute(): string
    {
        return getFileIcon($this->mime_type, $this->extension);
    }

    public function getColorAttribute(): string
    {
        return getFileColor($this->mime_type);
    }

    // Helper methods for compatibility with different templates
    public function isImage(): bool
    {
        return $this->file_type === 'image';
    }

    public function isVideo(): bool
    {
        return $this->file_type === 'video';
    }

    public function isDocument(): bool
    {
        return in_array($this->file_type, ['document', 'office']);
    }

    public function canPreview(): bool
    {
        return in_array($this->file_type, ['image', 'video']);
    }

    public function getIcon(): string
    {
        return getFileIcon($this->mime_type, $this->extension);
    }

    public function getColorClass(): string
    {
        return getFileColor($this->mime_type);
    }

    // Static methods
    public static function getFileTypeFromMime(string $mimeType): string
    {
        return getFileTypeCategory($mimeType);
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($file) {
            $file->uploaded_by = auth()->guard('admin')->id();
        });

        static::deleting(function ($file) {
            // Clean up media files when file is deleted
            $file->clearMediaCollection('files');
            $file->clearMediaCollection('thumbnails');
        });
    }
}

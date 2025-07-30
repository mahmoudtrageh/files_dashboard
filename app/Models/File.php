<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'original_name',
        'file_name',
        'file_path',
        'mime_type',
        'extension',
        'size',
        'metadata',
        'category_id',
        'uploaded_by',
        'is_public',
        'is_active',
        'last_accessed_at',
        'download_count'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_public' => 'boolean',
        'is_active' => 'boolean',
        'last_accessed_at' => 'datetime',
        'size' => 'integer',
        'download_count' => 'integer'
    ];

    protected $appends = [
        'file_url',
        'human_readable_size',
        'file_type',
        'is_image'
    ];

    /**
     * Get the category that owns the file
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the admin who uploaded the file
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'uploaded_by');
    }

    /**
     * Scope for active files
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for public files
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope for specific file types
     */
    public function scopeOfType($query, string $type)
    {
        $mimeTypes = $this->getMimeTypesByCategory($type);
        return $query->whereIn('mime_type', $mimeTypes);
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('original_name', 'like', "%{$search}%");
        });
    }

    /**
     * Get the file URL
     */
    public function getFileUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }

    /**
     * Get human readable file size
     */
    public function getHumanReadableSizeAttribute(): string
    {
        return formatBytes($this->size);
    }

    /**
     * Get file type category
     */
    public function getFileTypeAttribute(): string
    {
        return getFileTypeCategory($this->mime_type);
    }

    /**
     * Check if file is an image
     */
    public function getIsImageAttribute(): bool
    {
        return $this->file_type === 'image';
    }

    /**
     * Get file icon based on type
     */
    public function getFileIconAttribute(): string
    {
        return getFileIcon($this->mime_type);
    }

    /**
     * Get mime types by category
     */
    private function getMimeTypesByCategory(string $category): array
    {
        $categories = [
            'image' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'],
            'video' => ['video/mp4', 'video/avi', 'video/mov', 'video/wmv', 'video/flv'],
            'audio' => ['audio/mp3', 'audio/wav', 'audio/ogg', 'audio/m4a'],
            'document' => ['application/pdf', 'text/plain'],
            'office' => [
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation'
            ],
            'archive' => ['application/zip', 'application/x-rar-compressed', 'application/x-7z-compressed']
        ];

        return $categories[$category] ?? [];
    }

    /**
     * Increment download count
     */
    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
        $this->update(['last_accessed_at' => now()]);
    }

    /**
     * Delete file from storage when model is deleted
     */
    protected static function booted()
    {
        static::deleting(function ($file) {
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
        });
    }
}

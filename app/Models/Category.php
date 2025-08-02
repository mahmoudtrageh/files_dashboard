<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'parent_id',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Get the parent category
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Scope for active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for root categories (no parent)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get the full category path
     */
    public function getFullPathAttribute(): string
    {
        if ($this->parent) {
            return $this->parent->full_path . ' > ' . $this->name;
        }

        return $this->name;
    }

    /**
     * Get files count including subcategories
     */
    public function getTotalFilesCountAttribute(): int
    {
        $count = $this->files()->count();

        foreach ($this->children as $child) {
            $count += $child->total_files_count;
        }

        return $count;
    }

    /**
     * Check if category has children
     */
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Get all descendant categories
     */
    public function descendants(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->with('descendants');
    }

    /**
     * Get breadcrumb trail
     */
    public function getBreadcrumbAttribute(): array
    {
        $breadcrumb = [];
        $category = $this;

        while ($category) {
            array_unshift($breadcrumb, [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug
            ]);
            $category = $category->parent;
        }

        return $breadcrumb;
    }

    /**
     * Archives relationship - NEW
     */
    public function archives(): HasMany
    {
        return $this->hasMany(Archive::class);
    }

    /**
     * Active archives relationship - NEW
     */
    public function activeArchives(): HasMany
    {
        return $this->archives()->where('status', 'active');
    }

    /**
     * Check if category has archives - NEW
     */
    public function hasArchives(): bool
    {
        return $this->archives()->exists();
    }

    /**
     * Get archives count - NEW
     */
    public function getArchivesCountAttribute(): int
    {
        return $this->archives()->count();
    }

    /**
     * Get active archives count - NEW
     */
    public function getActiveArchivesCountAttribute(): int
    {
        return $this->activeArchives()->count();
    }

    /**
     * Get total storage size for this category - NEW
     */
    public function getTotalStorageSizeAttribute(): int
    {
        return $this->archives()
            ->join('media', 'archives.id', '=', 'media.model_id')
            ->where('media.model_type', Archive::class)
            ->sum('media.size');
    }

    /**
     * Get formatted storage size - NEW
     */
    public function getFormattedStorageSizeAttribute(): string
    {
        return $this->formatBytes($this->total_storage_size);
    }

    /**
     * Get all descendant category IDs - NEW
     */
    public function getDescendantIds(): array
    {
        $ids = [];
        $this->collectDescendantIds($ids);
        return $ids;
    }

    /**
     * Recursively collect descendant IDs - NEW
     */
    private function collectDescendantIds(array &$ids): void
    {
        foreach ($this->children as $child) {
            $ids[] = $child->id;
            $child->collectDescendantIds($ids);
        }
    }

    /**
     * Get archives including from subcategories - NEW
     */
    public function getArchivesWithDescendants()
    {
        $categoryIds = array_merge([$this->id], $this->getDescendantIds());

        return Archive::whereIn('category_id', $categoryIds);
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            // Move child categories to parent level
            $category->children()->update(['parent_id' => $category->parent_id]);

            // Note: Archives will be handled by the ArchiveService
            // We don't delete them automatically to prevent data loss
        });
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

}

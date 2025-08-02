<?php

// app/Helpers/helper.php

use App\Models\Admin;
use App\Models\Setting;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\File;
use App\Models\Category;

/**
 * ==============================================
 * ADMIN & SYSTEM FUNCTIONS
 * ==============================================
 */

if (!function_exists('getAdminCount')) {
    function getAdminCount(): int
    {
        return Admin::count();
    }
}

if (!function_exists('getRoleCount')) {
    function getRoleCount(): int
    {
        return Role::count();
    }
}

if (!function_exists('getPermissionCount')) {
    function getPermissionCount(): int
    {
        return Permission::count();
    }
}

if (!function_exists('getCategoriesCount')) {
    function getCategoriesCount(): int
    {
        return Category::active()->count();
    }
}

/**
 * ==============================================
 * SETTINGS & MEDIA FUNCTIONS
 * ==============================================
 */

if (!function_exists('settings')) {
    function settings(string $key, mixed $default = null): mixed
    {
        $setting = Setting::select('value')->where('key', $key)->first();
        return $setting?->value ?? $default;
    }
}

if (!function_exists('getMediaUrl')) {
    function getMediaUrl(string $key): ?string
    {
        $setting = Setting::select('value')->where('key', $key)->first();

        if (!$setting) {
            return null;
        }

        $media = $setting->getFirstMedia($key);
        if ($media) {
            return $media->getUrl();
        }

        return $setting->value;
    }
}

/**
 * ==============================================
 * LOCALIZATION FUNCTIONS
 * ==============================================
 */

if (!function_exists('available_locales')) {
    /**
     * Get available locales
     */
    function available_locales(): array
    {
        return config('app.available_locales', [
            'en' => [
                'name' => 'English',
                'script' => 'Latn',
                'native' => 'English',
                'regional' => 'en_US',
                'direction' => 'ltr'
            ],
            'ar' => [
                'name' => 'Arabic',
                'script' => 'Arab',
                'native' => 'العربية',
                'regional' => 'ar_SA',
                'direction' => 'rtl'
            ]
        ]);
    }
}

if (!function_exists('current_locale')) {
    /**
     * Get current locale
     */
    function current_locale(): string
    {
        return app()->getLocale();
    }
}

if (!function_exists('is_rtl')) {
    /**
     * Check if current locale is RTL
     */
    function is_rtl(?string $locale = null): bool
    {
        $locale = $locale ?: app()->getLocale();
        $locales = available_locales();

        return isset($locales[$locale]) && $locales[$locale]['direction'] === 'rtl';
    }
}

if (!function_exists('locale_url')) {
    /**
     * Generate URL with locale parameter
     */
    function locale_url(string $locale): string
    {
        return url()->current() . '?' . http_build_query(array_merge(
            request()->query(),
            ['lang' => $locale]
        ));
    }
}

/**
 * ==============================================
 * TEXT & DATE FORMATTING FUNCTIONS
 * ==============================================
 */

if (!function_exists('format_date_for_humans')) {
    /**
     * Format date for humans in current locale
     */
    function format_date_for_humans(\Carbon\Carbon $date): string
    {
        if (is_rtl()) {
            return $date->locale('ar')->diffForHumans();
        }

        return $date->diffForHumans();
    }
}

if (!function_exists('truncate_text')) {
    /**
     * Truncate text with proper RTL support
     */
    function truncate_text(string $text, int $limit = 100, string $end = '...'): string
    {
        if (mb_strlen($text) <= $limit) {
            return $text;
        }

        return mb_substr($text, 0, $limit) . $end;
    }
}

if (!function_exists('formatBytes')) {
    /**
     * Format bytes to human readable format
     */
    function formatBytes(int|float $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

        if ($bytes == 0) {
            return '0 B';
        }

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

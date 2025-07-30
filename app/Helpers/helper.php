<?php

use App\Models\Admin;
use App\Models\Setting;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

function getAdminCount()
{
    return Admin::count();
}

function getRoleCount()
{
    return Role::count();
}

function getPermissionCount()
{
    return Permission::count();
}

function settings($key)
{
    return Setting::select('value')->where('key', $key)->first()->value ?? null;
}

function getMediaUrl($key)
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

// function getMedia($key)
// {
//     $setting = Setting::where('key', $key)->first();
//     return $setting ? $setting->getMedia($key) : collect();
// }

if (!function_exists('available_locales')) {
    function available_locales() {
        return config('app.available_locales', []);
    }
}

if (!function_exists('current_locale')) {
    function current_locale() {
        return app()->getLocale();
    }
}

if (!function_exists('is_rtl')) {
    function is_rtl($locale = null) {
        $locale = $locale ?: app()->getLocale();
        $locales = config('app.available_locales', []);

        return isset($locales[$locale]) && $locales[$locale]['direction'] === 'rtl';
    }
}

if (!function_exists('locale_url')) {
    function locale_url($locale) {
        return url()->current() . '?' . http_build_query(array_merge(
            request()->query(),
            ['lang' => $locale]
        ));
    }
}

if (!function_exists('formatBytes')) {
    /**
     * Format bytes to human readable format
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

if (!function_exists('getFileIcon')) {
    /**
     * Get file icon based on mime type
     *
     * @param string $mimeType
     * @return string
     */
    function getFileIcon($mimeType)
    {
        $icons = [
            // Images
            'image/jpeg' => 'fas fa-image text-green-500',
            'image/jpg' => 'fas fa-image text-green-500',
            'image/png' => 'fas fa-image text-green-500',
            'image/gif' => 'fas fa-image text-green-500',
            'image/webp' => 'fas fa-image text-green-500',
            'image/svg+xml' => 'fas fa-image text-green-500',

            // Videos
            'video/mp4' => 'fas fa-video text-red-500',
            'video/avi' => 'fas fa-video text-red-500',
            'video/mov' => 'fas fa-video text-red-500',
            'video/wmv' => 'fas fa-video text-red-500',
            'video/flv' => 'fas fa-video text-red-500',
            'video/webm' => 'fas fa-video text-red-500',

            // Audio
            'audio/mp3' => 'fas fa-music text-purple-500',
            'audio/wav' => 'fas fa-music text-purple-500',
            'audio/ogg' => 'fas fa-music text-purple-500',
            'audio/m4a' => 'fas fa-music text-purple-500',
            'audio/flac' => 'fas fa-music text-purple-500',

            // Documents
            'application/pdf' => 'fas fa-file-pdf text-red-600',
            'text/plain' => 'fas fa-file-alt text-gray-500',

            // Office
            'application/msword' => 'fas fa-file-word text-blue-600',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'fas fa-file-word text-blue-600',
            'application/vnd.ms-excel' => 'fas fa-file-excel text-green-600',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'fas fa-file-excel text-green-600',
            'application/vnd.ms-powerpoint' => 'fas fa-file-powerpoint text-orange-600',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'fas fa-file-powerpoint text-orange-600',

            // Archives
            'application/zip' => 'fas fa-file-archive text-yellow-600',
            'application/x-rar-compressed' => 'fas fa-file-archive text-yellow-600',
            'application/x-7z-compressed' => 'fas fa-file-archive text-yellow-600',
            'application/gzip' => 'fas fa-file-archive text-yellow-600',
            'application/x-tar' => 'fas fa-file-archive text-yellow-600',

            // Code
            'text/html' => 'fas fa-code text-orange-500',
            'text/css' => 'fas fa-code text-blue-500',
            'text/javascript' => 'fas fa-code text-yellow-500',
            'application/json' => 'fas fa-code text-green-500',
            'application/xml' => 'fas fa-code text-red-500',
        ];

        return $icons[$mimeType] ?? 'fas fa-file text-gray-400';
    }
}

if (!function_exists('getFileTypeCategory')) {
    /**
     * Get file type category
     *
     * @param string $mimeType
     * @return string
     */
    function getFileTypeCategory($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        } elseif (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        } elseif (in_array($mimeType, ['application/pdf', 'text/plain'])) {
            return 'document';
        } elseif (in_array($mimeType, [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation'
        ])) {
            return 'office';
        } elseif (in_array($mimeType, [
            'application/zip',
            'application/x-rar-compressed',
            'application/x-7z-compressed',
            'application/gzip',
            'application/x-tar'
        ])) {
            return 'archive';
        } elseif (in_array($mimeType, [
            'text/html',
            'text/css',
            'text/javascript',
            'application/json',
            'application/xml'
        ])) {
            return 'code';
        }

        return 'other';
    }
}

if (!function_exists('available_locales')) {
    /**
     * Get available locales
     *
     * @return array
     */
    function available_locales()
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
     *
     * @return string
     */
    function current_locale()
    {
        return app()->getLocale();
    }
}

if (!function_exists('is_rtl')) {
    /**
     * Check if current locale is RTL
     *
     * @param string|null $locale
     * @return bool
     */
    function is_rtl($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        $locales = available_locales();

        return isset($locales[$locale]) && $locales[$locale]['direction'] === 'rtl';
    }
}

if (!function_exists('locale_url')) {
    /**
     * Generate URL with locale parameter
     *
     * @param string $locale
     * @return string
     */
    function locale_url($locale)
    {
        return url()->current() . '?' . http_build_query(array_merge(
            request()->query(),
            ['lang' => $locale]
        ));
    }
}

if (!function_exists('admin_user')) {
    /**
     * Get current authenticated admin user
     *
     * @return \App\Models\Admin|null
     */
    function admin_user()
    {
        return auth()->guard('admin')->user();
    }
}

if (!function_exists('format_date_for_humans')) {
    /**
     * Format date for humans in current locale
     *
     * @param \Carbon\Carbon $date
     * @return string
     */
    function format_date_for_humans($date)
    {
        if (is_rtl()) {
            // Arabic date formatting
            return $date->locale('ar')->diffForHumans();
        }

        return $date->diffForHumans();
    }
}

if (!function_exists('truncate_text')) {
    /**
     * Truncate text with proper RTL support
     *
     * @param string $text
     * @param int $limit
     * @param string $end
     * @return string
     */
    function truncate_text($text, $limit = 100, $end = '...')
    {
        if (mb_strlen($text) <= $limit) {
            return $text;
        }

        return mb_substr($text, 0, $limit) . $end;
    }
}

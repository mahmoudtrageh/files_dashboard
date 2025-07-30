<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class Setting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'key',
        'value',
    ];

    public static function get($key, $default = null)
    {
        $setting = static::getAll()->where('key', $key)->first();
        
        return $setting ? $setting->value : $default;
    }
 
    public static function set($key, $value)
    {
        $setting = static::firstOrNew(['key' => $key]);
        $setting->value = $value;
        $result = $setting->save();
        
        static::clearCache();
        
        return $result;
    }
    
    public static function has($key)
    {
        return static::getAll()->where('key', $key)->isNotEmpty();
    }
    
    public static function getAll()
    {
        return Cache::remember('settings.all', 86400, function () {
            return static::all();
        });
    }
    
    public static function clearCache()
    {
        Cache::forget('settings.all');
    }

    public function getValueAttribute($value)
    {
        $decoded = json_decode($value, true);
        
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }
        
        return $value;
    }
    
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = is_array($value) ? json_encode($value) : $value;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('dashboard_logo')
            ->singleFile();
            
        $this->addMediaCollection('favicon')
            ->singleFile();
    }
}
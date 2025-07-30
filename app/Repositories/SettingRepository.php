<?php

namespace App\Repositories;

use App\Models\Setting;
use App\Repositories\Interfaces\SettingRepositoryInterface;

class SettingRepository implements SettingRepositoryInterface
{
    protected $model;
    
    public function __construct(Setting $model)
    {
        $this->model = $model;
    }

    public function get(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }

    public function set(string $key, $value)
    {
        Setting::set($key, $value);
    }
    
    public function all()
    {
        return $this->model->pluck('value', 'key')->toArray();
    }
    
    public function updateMultiple(array $settings)
    {
        foreach ($settings as $key => $value) {
            $this->set($key, $value);
        }
    }
    
    public function handleMediaUploads($request, array $fields)
    {
        foreach ($fields as $field) {
            if ($request->hasFile($field) && $request->file($field)->isValid()) {
                $setting = $this->model->firstOrCreate(['key' => $field]);
                
                $setting->clearMediaCollection($field);
                
                $media = $setting->addMediaFromRequest($field)
                    ->usingName($field)
                    ->usingFileName($field . '_' . time() . '.' . $request->file($field)->getClientOriginalExtension())
                    ->toMediaCollection($field, 'public');
                
                $setting->value = $media->getUrl();
                $setting->save();
            }
        }
    }
    
    public function clearMedia(string $key, string $collection)
    {
        $setting = $this->model->where('key', $key)->first();
        
        if ($setting) {
            $setting->clearMediaCollection($collection);
        }
    }
}
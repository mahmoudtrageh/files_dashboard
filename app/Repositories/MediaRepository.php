<?php

namespace App\Repositories;

use App\Models\Setting;
use App\Repositories\Interfaces\MediaRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaRepository implements MediaRepositoryInterface
{
    protected $mediaModel;

    protected $settingModel;

    public function __construct(Media $mediaModel, Setting $settingModel)
    {
        $this->mediaModel = $mediaModel;
        $this->settingModel = $settingModel;
    }

    public function getPaginatedMedia(int $perPage = 15)
    {
        return $this->mediaModel->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function uploadMedia(Request $request, string $fileInputName, string $keyName)
    {
        try {
            $setting = $this->settingModel->firstOrCreate(['key' => $keyName]);
            
            $media = $setting->addMediaFromRequest($fileInputName)
                ->usingName($request->file($fileInputName)->getClientOriginalName())
                ->usingFileName(time() . '_' . $request->file($fileInputName)->getClientOriginalName())
                ->toMediaCollection($keyName, 'public');
            
            $setting->value = $media->getUrl();
            $setting->save();
            
            return $media;
        } catch (\Exception $e) {
            Log::error('Media upload failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteMedia(int $id)
    {
        try {
            $media = $this->findById($id);
            
            if (!$media) {
                return false;
            }
            
            $key = $media->collection_name;
            $media->delete();
            
            // Update setting if this was the last media in the collection
            $setting = $this->settingModel->where('key', $key)->first();
            if ($setting && !$setting->getFirstMedia($key)) {
                $setting->value = null;
                $setting->save();
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('Media delete failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function findById(int $id)
    {
        return $this->mediaModel->find($id);
    }
}

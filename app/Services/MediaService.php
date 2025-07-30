<?php

namespace App\Services;

use App\Repositories\Interfaces\MediaRepositoryInterface;
use Illuminate\Http\Request;

class MediaService
{
    protected $mediaRepository;

    public function __construct(MediaRepositoryInterface $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }

    public function getMediaList(int $perPage = 15)
    {
        return $this->mediaRepository->getPaginatedMedia($perPage);
    }

    public function handleMediaUpload(Request $request)
    {
        return $this->mediaRepository->uploadMedia(
            $request, 
            'file', 
            $request->input('key')
        );
    }

    public function handleMediaDeletion(int $id)
    {
        return $this->mediaRepository->deleteMedia($id);
    }

    public function getUploadValidationRules()
    {
        return [
            'file' => 'required|file|image|max:2048',
            'key' => 'required|string|max:255',
        ];
    }
}
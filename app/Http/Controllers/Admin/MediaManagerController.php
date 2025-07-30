<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MediaService;
use Illuminate\Http\Request;

class MediaManagerController extends Controller
{
    protected $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function mediaManager()
    {
        $media = $this->mediaService->getMediaList(4);
        return view('admin.pages.settings.media-manager', compact('media'));
    }

    public function uploadMedia(Request $request)
    {
        $request->validate($this->mediaService->getUploadValidationRules());
        
        try {
            $this->mediaService->handleMediaUpload($request);
            return redirect()->back()->with('success', 'تم رفع الملف بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'فشل رفع الملف: ' . $e->getMessage())->withInput();
        }
    }

    public function deleteMedia($id)
    {
        try {
            $this->mediaService->handleMediaDeletion($id);
            return redirect()->back()->with('success', 'تم حذف الملف بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'فشل حذف الملف: ' . $e->getMessage());
        }
    }
}
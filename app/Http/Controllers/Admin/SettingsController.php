<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\UpdateSettingsRequest;
use App\Models\Setting;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    protected $settingService;
    
    public function __construct(SettingsService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.pages.settings.index', compact('settings'));
    }

    public function update(UpdateSettingsRequest $request)
    {
        $section = $request->input('section', 'general');
                        
        $this->settingService->updateBySection($request, $section);
        
        $sectionHash = $this->settingService->getSectionHashId($section);
        
        return to_route('admin.settings.index', ['#' . $sectionHash])
            ->with('success', 'تم تحديث الإعدادات بنجاح.');
    }
}
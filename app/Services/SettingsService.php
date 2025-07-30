<?php

namespace App\Services;

use App\Repositories\Interfaces\SettingRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SettingsService
{

    protected $settingRepository;

    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }
    
    public function updateBySection(Request $request, string $section)
    {
        if ($section === 'dashboard-media') {
            $this->settingRepository->handleMediaUploads($request, ['dashboard_logo', 'favicon']);
        }
        
        $settings = $request->except(['_token', 'section', 'dashboard_logo', 'favicon']);
        
        foreach ($settings as $key => $value) {
            if ($request->has($key) && $value === '1' && $request->input($key) === null) {
                $settings[$key] = '0';
            }
        }
        
        $this->settingRepository->updateMultiple($settings);
    }
  
    public function getSectionHashId($section)
    {
        $hashMap = [
            'general' => 'general-settings',
            'dashboard-media' => 'dashboard-media-settings',
            'seo' => 'seo-settings',
            'contact' => 'contact-settings',
            'social' => 'social-settings',
            'advanced' => 'advanced-settings',
        ];
        
        return $hashMap[$section] ?? 'general-settings';
    }
 
    public function getValidationRules($section)
    {
        switch ($section) {
            case 'general':
                return [
                    'site_name' => 'required|string|max:255',
                    'panel_version' => 'required|numeric',
                ];
                
            case 'dashboard-media':
                return [
                    'dashboard_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
                    'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:512',
                ];
                
            case 'seo':
                return [
                    'meta_title' => 'nullable|string|max:255',
                    'meta_description' => 'nullable|string|max:500',
                    'meta_keywords' => 'nullable|string|max:500',
                ];
                
            case 'contact':
                return [
                    'contact_email' => 'nullable|email|max:255',
                    'phone_number' => 'nullable|string|max:20',
                    'address' => 'nullable|string|max:500',
                ];
                
            case 'social':
                return [
                    'facebook_url' => 'nullable|url|max:255',
                    'twitter_url' => 'nullable|url|max:255',
                    'instagram_url' => 'nullable|url|max:255',
                    'linkedin_url' => 'nullable|url|max:255',
                ];
                
            case 'advanced':
                return [
                    'cache_expiration' => 'required|numeric|min:0',
                    'google_analytics_id' => 'nullable|string|max:50',
                    'custom_header_scripts' => 'nullable|string',
                ];
                
            default:
                return [];
        }
    }
}
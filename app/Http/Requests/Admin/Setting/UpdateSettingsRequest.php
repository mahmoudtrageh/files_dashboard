<?php

namespace App\Http\Requests\Admin\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $section = $this->input('section', 'general');
        
        return match ($section) {
            'general' => [
                'site_name' => 'required|string|max:255',
                'panel_version' => 'required|numeric',
            ],
            'dashboard-media' => [
                'dashboard_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
                'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:512',
            ],
            'seo' => [
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string|max:500',
            ],
            'contact' => [
                'contact_email' => 'nullable|email|max:255',
                'phone_number' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
            ],
            'social' => [
                'facebook_url' => 'nullable|url|max:255',
                'twitter_url' => 'nullable|url|max:255',
                'instagram_url' => 'nullable|url|max:255',
                'linkedin_url' => 'nullable|url|max:255',
            ],
            'advanced' => [
                'cache_expiration' => 'required|numeric|min:0',
                'google_analytics_id' => 'nullable|string|max:50',
                'custom_header_scripts' => 'nullable|string',
            ],
            default => [],
        };
    }

    public function messages()
    {
        return [
            'site_name.required' => 'اسم الموقع مطلوب',
            'site_name.string' => 'اسم الموقع يجب أن يكون نصًا',
            'site_name.max' => 'اسم الموقع يجب ألا يتجاوز 255 حرفًا',
            'panel_version.required' => 'إصدار اللوحة مطلوب',
            'panel_version.numeric' => 'إصدار اللوحة يجب أن يكون رقمًا',
        ];
    }
}

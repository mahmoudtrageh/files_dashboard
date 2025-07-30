{{-- resources/views/admin/pages/settings/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'إعدادات النظام')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-900">إعدادات النظام</h1>
        </div>
        
        <!-- Alerts -->
        @include('admin.components.alerts')
        
        <!-- Setting Categories -->
        <div class="mt-6 bg-white shadow-md rounded-lg overflow-hidden">
            <form action="{{ route('admin.settings.update') }}" method="POST" id="settings-form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="section" id="active-section" value="general">
                
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8 space-x-reverse px-4 overflow-x-auto" aria-label="Tabs">
                        <button type="button" id="tab-general" class="tab-button border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-3 border-b-2 font-medium text-sm transition-colors duration-200 active" data-tab="general-settings" data-section="general">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                </svg>
                                إعدادات عامة
                            </div>
                        </button>
                        <button type="button" id="tab-dashboard-media" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-3 border-b-2 font-medium text-sm transition-colors duration-200" data-tab="dashboard-media-settings" data-section="dashboard-media">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                </svg>
                                وسائط لوحة التحكم
                            </div>
                        </button>
                        <button type="button" id="tab-seo" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-3 border-b-2 font-medium text-sm transition-colors duration-200" data-tab="seo-settings" data-section="seo">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                                </svg>
                                إعدادات SEO
                            </div>
                        </button>
                        <button type="button" id="tab-contact" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-3 border-b-2 font-medium text-sm transition-colors duration-200" data-tab="contact-settings" data-section="contact">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                </svg>
                                معلومات الاتصال
                            </div>
                        </button>
                        <button type="button" id="tab-social" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-3 border-b-2 font-medium text-sm transition-colors duration-200" data-tab="social-settings" data-section="social">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84" />
                                </svg>
                                وسائل التواصل الاجتماعي
                            </div>
                        </button>
                        <button type="button" id="tab-advanced" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-3 border-b-2 font-medium text-sm transition-colors duration-200" data-tab="advanced-settings" data-section="advanced">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                                </svg>
                                إعدادات متقدمة
                            </div>
                        </button>
                    </nav>
                </div>
                
                <!-- Forms -->
                <div class="px-6 py-6 sm:p-8">
                    <!-- General Setting -->
                    <div class="tab-content block" id="general-settings">
                        <div class="space-y-8">
                            <div>
                                <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">اسم الموقع</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="text" name="site_name" id="site_name" value="{{ $settings['site_name'] }}" 
                                        class="block w-full pr-10 py-3 border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                @error('site_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="panel_version" class="block text-sm font-medium text-gray-700 mb-1">اصدار اللوحة</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="number" name="panel_version" id="panel_version" value="{{ $settings['panel_version'] }}" 
                                        class="block w-full pr-10 py-3 border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 2c.76 0 1.51.14 2.2.41L10 8.62 7.8 4.41A5.99 5.99 0 0110 4zm-3.77 1.57L9 10.15V16.1c-1.84-.63-3.3-2.09-3.91-3.93L8.23 8.4 6.23 5.57zM11 16.1V10.15l2.77-4.82L15.77 8.4l-3.14 3.77c-.61 1.84-2.07 3.3-3.91 3.93H11z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                @error('panel_version')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="maintenance_mode" class="block text-sm font-medium text-gray-700 mb-1">وضع الصيانة</label>
                                <div class="mt-1">
                                    <label class="inline-flex items-center p-3 rounded-md bg-gray-50 hover:bg-gray-100 transition-colors duration-200 cursor-pointer">
                                        <input type="checkbox" name="maintenance_mode" id="maintenance_mode" value="1" 
                                            {{ $settings['maintenance_mode'] ? 'checked' : '' }} 
                                            class="h-5 w-5 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-colors duration-200">
                                        <span class="mr-3 text-sm text-gray-600">تفعيل وضع الصيانة يجعل الموقع غير متاح للزوار</span>
                                    </label>
                                </div>
                                @error('maintenance_mode')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dashboard Media Settings -->
                    <div class="tab-content hidden" id="dashboard-media-settings">
                        <div class="space-y-8">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">شعار لوحة التحكم</label>
                                <div class="mt-2">
                                    <div class="relative p-6 border-2 border-dashed border-gray-300 rounded-md bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                        <div class="text-center" id="logo-display-area">
                                            @if($settings['dashboard_logo'])
                                                <div id="existing-logo-container" class="mb-4">
                                                    <img src="{{ asset(getMediaUrl('dashboard_logo')) }}" alt="شعار لوحة التحكم" class="h-20 object-contain mx-auto">
                                                </div>
                                            @else
                                                <div id="logo-upload-icon">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div id="logo-preview-container" class="hidden mb-4">
                                                <img id="logo-preview-image" src="#" alt="معاينة الشعار" class="h-20 object-contain mx-auto">
                                            </div>
                                        </div>
                                        <div class="flex text-sm text-gray-600 justify-center mt-4">
                                            <label for="dashboard_logo" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 px-4 py-3">
                                                <span>{{ $settings['dashboard_logo'] ? 'تغيير الشعار' : 'رفع شعار' }}</span>
                                                <input id="dashboard_logo" name="dashboard_logo" type="file" class="sr-only" accept="image/*">
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500 text-center mt-2">PNG, JPG, GIF، SVG حتى 1MB</p>
                                    </div>
                                </div>
                                @error('dashboard_logo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">أيقونة لوحة التحكم (Favicon)</label>
                                <div class="mt-2">
                                    <div class="relative p-6 border-2 border-dashed border-gray-300 rounded-md bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                        <div class="text-center" id="favicon-display-area">
                                            @if($settings['favicon'])
                                                <div id="existing-favicon-container" class="mb-4">
                                                    <img src="{{ asset(getMediaUrl('favicon')) }}" alt="أيقونة لوحة التحكم" class="h-12 w-12 object-contain mx-auto">
                                                </div>
                                            @else
                                                <div id="favicon-upload-icon">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div id="favicon-preview-container" class="hidden mb-4">
                                                <img id="favicon-preview-image" src="#" alt="معاينة الأيقونة" class="h-12 w-12 object-contain mx-auto">
                                            </div>
                                        </div>
                                        
                                        <div class="flex text-sm text-gray-600 justify-center mt-4">
                                            <label for="favicon" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 px-4 py-3">
                                                <span>{{ $settings['favicon'] ? 'تغيير الأيقونة' : 'رفع أيقونة' }}</span>
                                                <input id="favicon" name="favicon" type="file" class="sr-only" accept="image/*">
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500 text-center mt-2">يفضل صورة مربعة بأبعاد 32×32 بكسل</p>
                                    </div>
                                </div>
                                @error('favicon')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- SEO Setting -->
                    <div class="tab-content hidden" id="seo-settings">
                        <div class="space-y-8">
                            <div>
                                <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">عنوان الموقع (Meta Title)</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="text" name="meta_title" id="meta_title" value="{{ $settings['meta_title'] }}" 
                                        class="block w-full pr-10 py-3 border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                @error('meta_title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">وصف الموقع (Meta Description)</label>
                                <div class="relative rounded-md shadow-sm">
                                    <textarea name="meta_description" id="meta_description" rows="3" 
                                        class="block w-full pr-10 py-3 border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm">{{ settings('meta_description', '') }}</textarea>
                                    <div class="absolute top-3 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                @error('meta_description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">الكلمات المفتاحية (Meta Keywords)</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="text" name="meta_keywords" id="meta_keywords" value="{{ $settings['meta_keywords'] }}" 
                                        class="block w-full pr-10 py-3 border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9.243 3.03a1 1 0 01.727 1.213L9.53 6h2.94l.56-2.243a1 1 0 111.94.486L14.53 6H17a1 1 0 110 2h-2.97l-1 4H15a1 1 0 110 2h-2.47l-.56 2.242a1 1 0 11-1.94-.485L10.47 14H7.53l-.56 2.242a1 1 0 11-1.94-.485L5.47 14H3a1 1 0 110-2h2.97l1-4H5a1 1 0 110-2h2.47l.56-2.243a1 1 0 011.213-.727zM9.03 8l-1 4h2.938l1-4H9.031z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">افصل بين الكلمات المفتاحية بفاصلة (،)</p>
                                @error('meta_keywords')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Setting -->
                    <div class="tab-content hidden" id="contact-settings">
                        <div class="space-y-8">
                            <div>
                                <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني للاتصال</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="email" name="contact_email" id="contact_email" value="{{ $settings['contact_email'] }}" 
                                        class="block w-full pr-10 py-3 border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                        </svg>
                                    </div>
                                </div>
                                @error('contact_email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="text" name="phone_number" id="phone_number" value="{{ $settings['phone_number'] }}" 
                                        class="block w-full pr-10 py-3 border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                        </svg>
                                    </div>
                                </div>
                                @error('phone_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">العنوان</label>
                                <div class="relative rounded-md shadow-sm">
                                    <textarea name="address" id="address" rows="3" 
                                        class="block w-full pr-10 py-3 border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm">{{ settings('address', '') }}</textarea>
                                    <div class="absolute top-3 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Social Media Setting -->
                    <div class="tab-content hidden" id="social-settings">
                        <div class="space-y-8">
                            <div>
                                <label for="facebook_url" class="block text-sm font-medium text-gray-700 mb-1">رابط فيسبوك</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-4 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                    <input type="text" name="facebook_url" id="facebook_url" value="{{ $settings['facebook_url'] }}" 
                                        class="flex-1 block w-full rounded-l-md border-gray-300 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm">
                                </div>
                                @error('facebook_url')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="twitter_url" class="block text-sm font-medium text-gray-700 mb-1">رابط تويتر</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-4 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                                        </svg>
                                    </span>
                                    <input type="text" name="twitter_url" id="twitter_url" value="{{ $settings['twitter_url'] }}" 
                                        class="flex-1 block w-full rounded-l-md border-gray-300 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm">
                                </div>
                                @error('twitter_url')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="instagram_url" class="block text-sm font-medium text-gray-700 mb-1">رابط انستجرام</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-4 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                    <input type="text" name="instagram_url" id="instagram_url" value="{{ $settings['instagram_url'] }}" 
                                        class="flex-1 block w-full rounded-l-md border-gray-300 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm">
                                </div>
                                @error('instagram_url')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="linkedin_url" class="block text-sm font-medium text-gray-700 mb-1">رابط لينكد إن</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-4 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                                        </svg>
                                    </span>
                                    <input type="text" name="linkedin_url" id="linkedin_url" value="{{ $settings['linkedin_url'] }}" 
                                        class="flex-1 block w-full rounded-l-md border-gray-300 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm">
                                </div>
                                @error('linkedin_url')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Advanced Setting -->
                    <div class="tab-content hidden" id="advanced-settings">
                        <div class="space-y-8">
                            <div>
                                <label for="cache_enabled" class="block text-sm font-medium text-gray-700 mb-1">تفعيل التخزين المؤقت</label>
                                <div class="mt-1">
                                    <label class="inline-flex items-center p-3 rounded-md bg-gray-50 hover:bg-gray-100 transition-colors duration-200 cursor-pointer">
                                        <input type="checkbox" name="cache_enabled" id="cache_enabled" value="1" 
                                            {{ $settings['cache_enabled'] ? 'checked' : '' }} 
                                            class="h-5 w-5 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-colors duration-200">
                                        <span class="mr-3 text-sm text-gray-600">تفعيل ذاكرة التخزين المؤقت لتحسين أداء الموقع</span>
                                    </label>
                                </div>
                                @error('cache_enabled')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="cache_expiration" class="block text-sm font-medium text-gray-700 mb-1">مدة التخزين المؤقت (بالثواني)</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="number" name="cache_expiration" id="cache_expiration" value="{{ $settings['cache_expiration'] }}" 
                                        class="block w-full pr-10 py-3 border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">86400 ثانية = 24 ساعة</p>
                                @error('cache_expiration')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="google_analytics_id" class="block text-sm font-medium text-gray-700 mb-1">معرف Google Analytics</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="text" name="google_analytics_id" id="google_analytics_id" value="{{ $settings['google_analytics_id'] }}" 
                                        class="block w-full pr-10 py-3 border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm" 
                                        placeholder="مثال: G-XXXXXXXXXX">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6.672 1.911a1 1 0 10-1.932.518l.259.966a1 1 0 001.932-.518l-.26-.966zM2.429 4.74a1 1 0 10-.517 1.932l.966.259a1 1 0 00.517-1.932l-.966-.26zm8.814-.569a1 1 0 00-1.415-1.414l-.707.707a1 1 0 101.415 1.415l.707-.708zm-7.071 7.072l.707-.707A1 1 0 003.465 9.12l-.708.707a1 1 0 001.415 1.415zm3.2-5.171a1 1 0 00-1.3 1.3l4 10a1 1 0 001.823.075l1.38-2.759 3.018 3.02a1 1 0 001.414-1.415l-3.019-3.02 2.76-1.379a1 1 0 00-.076-1.822l-10-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                @error('google_analytics_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="custom_header_scripts" class="block text-sm font-medium text-gray-700 mb-1">نصوص برمجية مخصصة في الهيدر</label>
                                <div class="relative rounded-md shadow-sm">
                                    <textarea name="custom_header_scripts" id="custom_header_scripts" rows="4" 
                                        class="block w-full pr-10 py-3 border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm font-mono">{{ $settings['custom_header_scripts'] }}</textarea>
                                    <div class="absolute top-3 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">سيتم إضافة هذه النصوص البرمجية في نهاية وسم &lt;head&gt;</p>
                                @error('custom_header_scripts')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Common Save Button for All Tabs -->
                    <div class="flex justify-end mt-8 pt-6 border-t border-gray-200">
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                            <svg class="-ml-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            حفظ الإعدادات
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Hide by default */
    .tab-content {
        display: none;
    }
    
    /* Show active tab */
    .tab-content.block {
        display: block;
    }
    
    /* Transition effects for tabs */
    .tab-button {
        position: relative;
        transition: all 0.2s ease-in-out;
    }
    
    .tab-button:after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        bottom: -2px;
        height: 2px;
        background-color: transparent;
        transition: all 0.2s ease-in-out;
    }
    
    .tab-button.active:after {
        background-color: currentColor;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');
        const activeSectionInput = document.getElementById('active-section');
        
        // Function to activate a tab
        function activateTab(tabId, sectionValue) {
            // Hide all tabs
            tabContents.forEach(content => {
                content.classList.add('hidden');
                content.classList.remove('block');
            });
            
            // Show selected tab
            const activeContent = document.getElementById(tabId);
            if (activeContent) {
                activeContent.classList.remove('hidden');
                activeContent.classList.add('block');
            }
            
            // Update tab button styles
            tabButtons.forEach(button => {
                button.classList.remove('border-indigo-500', 'text-indigo-600', 'active');
                button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });
            
            // Find the active button
            const activeButton = document.querySelector(`.tab-button[data-tab="${tabId}"]`);
            if (activeButton) {
                activeButton.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                activeButton.classList.add('border-indigo-500', 'text-indigo-600', 'active');
            }
            
            // Update the active section input for form submission
            if (sectionValue) {
                activeSectionInput.value = sectionValue;
            }
            
            // Save the active tab to localStorage
            localStorage.setItem('activeSettingsTab', tabId);
        }
        
        // Add click event listeners to all tab buttons
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                const sectionValue = this.getAttribute('data-section');
                activateTab(tabId, sectionValue);
            });
        });
        
        // Initialize - show the saved tab or first tab
        const savedTabId = localStorage.getItem('activeSettingsTab');
        if (savedTabId && document.getElementById(savedTabId)) {
            const tabButton = document.querySelector(`.tab-button[data-tab="${savedTabId}"]`);
            const sectionValue = tabButton ? tabButton.getAttribute('data-section') : 'general';
            activateTab(savedTabId, sectionValue);
        } else {
            const initialTabId = tabButtons[0]?.getAttribute('data-tab') || 'general-settings';
            const initialSectionValue = tabButtons[0]?.getAttribute('data-section') || 'general';
            activateTab(initialTabId, initialSectionValue);
        }
        
        // If URL has a hash, try to activate that tab
        if (window.location.hash) {
            const tabId = window.location.hash.substring(1);
            const tabExists = document.getElementById(tabId);
            if (tabExists) {
                const tabButton = document.querySelector(`.tab-button[data-tab="${tabId}"]`);
                const sectionValue = tabButton ? tabButton.getAttribute('data-section') : null;
                activateTab(tabId, sectionValue);
            }
        }
        
        // Update URL hash when tab changes
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                window.location.hash = tabId;
            });
        });

        // Handle image previews
        function setupImagePreview(inputId, previewImageId, previewContainerId, existingContainerId, uploadIconId) {
            const input = document.getElementById(inputId);
            
            if (input) {
                input.addEventListener('change', function() {
                    const previewContainer = document.getElementById(previewContainerId);
                    const previewImage = document.getElementById(previewImageId);
                    const uploadIcon = document.getElementById(uploadIconId);
                    const existingContainer = document.getElementById(existingContainerId);
                    
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        
                        reader.onload = function(e) {
                            // Update preview image
                            if (previewImage) {
                                previewImage.src = e.target.result;
                            }
                            
                            // Hide upload icon if visible
                            if (uploadIcon) {
                                uploadIcon.classList.add('hidden');
                            }
                            
                            // Hide existing image container if visible
                            if (existingContainer) {
                                existingContainer.classList.add('hidden');
                            }
                            
                            // Show the preview container
                            if (previewContainer) {
                                previewContainer.classList.remove('hidden');
                            }
                        };
                        
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }
        }
        
        // Setup image previews for both logo and favicon
        setupImagePreview(
            'dashboard_logo',
            'logo-preview-image',
            'logo-preview-container',
            'existing-logo-container',
            'logo-upload-icon'
        );
        
        setupImagePreview(
            'favicon',
            'favicon-preview-image',
            'favicon-preview-container',
            'existing-favicon-container',
            'favicon-upload-icon'
        );
    });
</script>
@endsection
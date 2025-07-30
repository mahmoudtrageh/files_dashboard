@extends('admin.layouts.app')

@section('content')
<div class="w-full px-4">
    <div class="flex flex-wrap">
        <div class="w-full">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-medium text-gray-800">إدارة الوسائط</h3>
                </div>
                <div class="p-6">
                    @include('admin.components.alerts')

                    <div class="mb-8">
                        <div class="w-full">
                            <form id="uploadForm" method="POST" enctype="multipart/form-data" action="{{ route('admin.media.upload') }}">
                                @csrf
                                <div class="space-y-6">
                                    <div>
                                        <label for="key" class="block text-sm font-medium text-gray-700 mb-2">مفتاح الإعداد</label>
                                        <div class="mt-1">
                                            <input type="text" name="key" id="key" value="{{ old('key') }}" 
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                        @error('key')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">رفع صورة جديدة</label>
                                        <div class="mt-1">
                                            <div class="relative p-6 border-2 border-dashed border-gray-300 rounded-md bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                                <div class="text-center" id="image-display-area">
                                                    <div id="image-upload-icon">
                                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </div>
                                                    <div id="image-preview-container" class="hidden mb-4">
                                                        <img id="image-preview" src="#" alt="معاينة الصورة" class="h-40 object-contain mx-auto">
                                                    </div>
                                                </div>
                                                
                                                <div class="flex text-sm text-gray-600 justify-center mt-4">
                                                    <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 px-4 py-3">
                                                        <span>اختر صورة</span>
                                                        <input id="file-upload" name="file" type="file" class="sr-only" accept="image/*" onchange="previewImage(this)">
                                                    </label>
                                                </div>
                                                <p class="text-xs text-gray-500 text-center mt-2">PNG, JPG, GIF، SVG حتى 2MB</p>
                                            </div>
                                            @error('file')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-end">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                            <svg class="-ml-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            رفع الصورة
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div>
                        <div class="w-full">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-lg font-medium">المعرض</h4>
                                <div id="mediaCount" class="text-sm text-gray-500">
                                    {{ count($media) }} ملف
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4 mt-3" id="mediaGallery">
                                @forelse($media as $item)
                                    <div class="media-item" data-id="{{ $item->id }}">
                                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                            <div class="img-container relative h-36 flex items-center justify-center bg-gray-100">
                                                <img src="{{ $item->getUrl() }}" class="max-h-full object-contain" alt="{{ $item->name }}">
                                                <div class="media-actions hidden absolute top-0 right-0 left-0 bottom-0 bg-black bg-opacity-40 flex items-center justify-center space-x-2">
                                                    <form action="{{ route('admin.media.delete', $item->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="delete-media bg-red-500 hover:bg-red-600 text-white p-2 rounded-full" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذه الوسائط؟');">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <p class="text-xs text-gray-500 truncate">{{ $item->name }}</p>
                                                <p class="text-xs text-gray-400">المفتاح: {{ $item->collection_name }}</p>
                                                <p class="text-xs text-gray-400 mb-1">{{ $item->human_readable_size }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full" id="emptyMessage">
                                        <p class="text-center text-gray-500 py-8">لا توجد وسائط متاحة</p>
                                    </div>
                                @endforelse
                            </div>
                            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                                {{ $media->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewImage(input) {
        const previewContainer = document.getElementById('image-preview-container');
        const preview = document.getElementById('image-preview');
        const uploadIcon = document.getElementById('image-upload-icon');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
                uploadIcon.classList.add('hidden');
            };
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const mediaItems = document.querySelectorAll('.media-item');
        if (mediaItems && mediaItems.length > 0) {
            mediaItems.forEach(item => {
                const imgContainer = item.querySelector('.img-container');
                const actionButtons = item.querySelector('.media-actions');
                
                if (imgContainer && actionButtons) {
                    imgContainer.addEventListener('mouseenter', () => {
                        actionButtons.classList.remove('hidden');
                    });
                    
                    imgContainer.addEventListener('mouseleave', () => {
                        actionButtons.classList.add('hidden');
                    });
                }
            });
        }
    });
</script>

<style>
    .media-item {
        transition: opacity 0.5s ease;
    }
    
    .media-item .img-container:hover img {
        opacity: 0.8;
    }
    
    .media-actions {
        transition: all 0.3s ease;
    }
</style>
@endsection
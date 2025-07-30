{{-- resources/views/admin/pages/files/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', trans('files.file_management'))

@push('breadcrumbs')
    @php
    $breadcrumbs = [
        ['name' => trans('files.file_management')]
    ];
    @endphp
@endpush

@section('content')
<div class="space-y-6">
    <!-- Enhanced Page Header -->
    <div class="bg-gradient-to-r from-white to-gray-50 rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-folder-open text-primary-600 text-xl"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ trans('files.file_management') }}</h1>
                        <p class="text-gray-600 mt-1">{{ trans('files.manage_organize_files') }}</p>
                    </div>
                </div>
            </div>

            <!-- Enhanced Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg border border-gray-200 p-4 text-center hover:shadow-md transition-shadow">
                    <div class="text-2xl font-bold text-primary-600">{{ $statistics['total_files'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500 mt-1">{{ trans('files.total_files') }}</div>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 p-4 text-center hover:shadow-md transition-shadow">
                    <div class="text-2xl font-bold text-blue-600">{{ formatBytes($statistics['total_size'] ?? 0) }}</div>
                    <div class="text-sm text-gray-500 mt-1">{{ trans('files.total_size') }}</div>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 p-4 text-center hover:shadow-md transition-shadow">
                    <div class="text-2xl font-bold text-green-600">{{ count($statistics['files_by_category'] ?? []) }}</div>
                    <div class="text-sm text-gray-500 mt-1">{{ trans('files.categories') }}</div>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 p-4 text-center hover:shadow-md transition-shadow">
                    <div class="text-2xl font-bold text-purple-600">{{ count($statistics['files_by_type'] ?? []) }}</div>
                    <div class="text-sm text-gray-500 mt-1">{{ trans('files.file_types') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced File Type Statistics -->
    @if(isset($statistics['files_by_type']) && !empty($statistics['files_by_type']))
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-chart-pie text-primary-600 {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                {{ trans('files.files_by_type') }}
            </h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($statistics['files_by_type'] as $type => $count)
                <div class="group relative bg-gray-50 hover:bg-gray-100 rounded-xl p-4 text-center transition-all duration-200 cursor-pointer border border-transparent hover:border-gray-200">
                    <div class="text-3xl mb-3 transition-transform group-hover:scale-110">
                        @switch($type)
                            @case('image')
                                <i class="fas fa-image text-green-500"></i>
                                @break
                            @case('video')
                                <i class="fas fa-video text-red-500"></i>
                                @break
                            @case('audio')
                                <i class="fas fa-music text-purple-500"></i>
                                @break
                            @case('document')
                                <i class="fas fa-file-pdf text-blue-500"></i>
                                @break
                            @case('office')
                                <i class="fas fa-file-word text-indigo-500"></i>
                                @break
                            @default
                                <i class="fas fa-file text-gray-500"></i>
                        @endswitch
                    </div>
                    <div class="font-bold text-lg text-gray-900">{{ $count }}</div>
                    <div class="text-sm text-gray-600 mt-1">{{ trans('files.' . $type) }}</div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Enhanced Recent Files Section -->
    @if(isset($statistics['recent_uploads']) && $statistics['recent_uploads']->isNotEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-clock text-primary-600 {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                {{ trans('files.recently_uploaded') }}
            </h2>
            <a href="{{ route('admin.files.index') }}"
               class="inline-flex items-center text-primary-600 hover:text-primary-700 text-sm font-medium transition-colors">
                {{ trans('files.view_all_files') }}
                <i class="fas fa-arrow-{{ is_rtl() ? 'left' : 'right' }} {{ is_rtl() ? 'mr-1' : 'ml-1' }}"></i>
            </a>
        </div>

        <div class="grid gap-4">
            @foreach($statistics['recent_uploads'] as $file)
                <div class="group flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all duration-200 border border-transparent hover:border-gray-200">
                    <div class="flex items-center space-x-4 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                        <div class="flex-shrink-0">
                            @if($file->is_image)
                                <div class="relative">
                                    <img src="{{ $file->file_url }}"
                                         alt="{{ $file->title }}"
                                         class="h-12 w-12 rounded-lg object-cover shadow-sm">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-lg transition-all"></div>
                                </div>
                            @else
                                <div class="h-12 w-12 rounded-lg bg-white flex items-center justify-center border border-gray-200 shadow-sm group-hover:shadow-md transition-shadow">
                                    <i class="{{ $file->file_icon }} text-gray-500 text-lg"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-900 group-hover:text-primary-600 transition-colors">
                                {{ $file->title }}
                            </div>
                            <div class="flex items-center space-x-4 text-sm text-gray-500 mt-1 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                                <span class="flex items-center">
                                    <i class="fas fa-weight-hanging {{ is_rtl() ? 'ml-1' : 'mr-1' }} text-xs"></i>
                                    {{ $file->human_readable_size }}
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-calendar {{ is_rtl() ? 'ml-1' : 'mr-1' }} text-xs"></i>
                                    {{ $file->created_at->diffForHumans() }}
                                </span>
                                @if($file->category)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                          style="background-color: {{ $file->category->color }}20; color: {{ $file->category->color }}">
                                        @if($file->category->icon)
                                            <i class="{{ $file->category->icon }} {{ is_rtl() ? 'ml-1' : 'mr-1' }}"></i>
                                        @endif
                                        {{ $file->category->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 {{ is_rtl() ? 'space-x-reverse' : '' }} opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ route('admin.files.download', $file) }}"
                           class="p-2 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-all"
                           title="{{ trans('files.download') }}">
                            <i class="fas fa-download"></i>
                        </a>
                        <a href="{{ route('admin.files.show', $file) }}"
                           class="p-2 text-gray-600 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-all"
                           title="{{ trans('files.view') }}">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Enhanced File Manager Component -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="p-6">
            @livewire('admin.file-manager')
        </div>
    </div>

    <!-- Quick Actions Panel -->
    <div class="bg-gradient-to-br from-primary-50 to-blue-50 rounded-xl border border-primary-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-bolt text-primary-600 {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
            {{ trans('files.quick_actions') }}
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.categories.create') }}"
               class="group flex items-center p-4 bg-white hover:bg-gray-50 border border-gray-200 hover:border-primary-200 rounded-xl transition-all">
                <div class="flex-shrink-0 w-10 h-10 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center transition-colors">
                    <i class="fas fa-plus text-green-600"></i>
                </div>
                <div class="{{ is_rtl() ? 'mr-3' : 'ml-3' }}">
                    <div class="font-medium text-gray-900 group-hover:text-primary-600 transition-colors">
                        {{ trans('files.create_category') }}
                    </div>
                    <div class="text-sm text-gray-500">{{ trans('files.add_new_category') }}</div>
                </div>
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="group flex items-center p-4 bg-white hover:bg-gray-50 border border-gray-200 hover:border-primary-200 rounded-xl transition-all">
                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center transition-colors">
                    <i class="fas fa-sitemap text-blue-600"></i>
                </div>
                <div class="{{ is_rtl() ? 'mr-3' : 'ml-3' }}">
                    <div class="font-medium text-gray-900 group-hover:text-primary-600 transition-colors">
                        {{ trans('files.manage_categories') }}
                    </div>
                    <div class="text-sm text-gray-500">{{ trans('files.organize_files') }}</div>
                </div>
            </a>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .file-type-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(249, 250, 251, 0.9) 100%);
        backdrop-filter: blur(10px);
    }

    .stats-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(249, 250, 251, 0.95) 100%);
        backdrop-filter: blur(5px);
    }

    .recent-file-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-up {
        animation: slideInUp 0.3s ease-out;
    }

    .glass-effect {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* RTL specific adjustments */
    @if(is_rtl())
    .rtl-space-adjust {
        margin-right: 0.75rem;
        margin-left: 0;
    }
    @else
    .ltr-space-adjust {
        margin-left: 0.75rem;
        margin-right: 0;
    }
    @endif
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add animation classes to elements
    const cards = document.querySelectorAll('.stats-card, .file-type-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('animate-slide-up');
        }, index * 100);
    });

    // Enhanced file upload progress
    const uploadButton = document.querySelector('[data-upload-trigger]');
    if (uploadButton) {
        uploadButton.addEventListener('click', function() {
            showUploadProgress();
        });
    }
});

function showUploadProgress() {
    // Implementation for upload progress
    console.log('Upload progress feature');
}

// Enhanced tooltip functionality
function initTooltips() {
    const tooltipElements = document.querySelectorAll('[title]');
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', showTooltip);
        element.addEventListener('mouseleave', hideTooltip);
    });
}

function showTooltip(event) {
    const element = event.target;
    const title = element.getAttribute('title');
    if (!title) return;

    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip fixed z-50 px-2 py-1 text-xs text-white bg-gray-900 rounded shadow-lg pointer-events-none';
    tooltip.textContent = title;

    document.body.appendChild(tooltip);

    const rect = element.getBoundingClientRect();
    tooltip.style.top = (rect.top - tooltip.offsetHeight - 5) + 'px';
    tooltip.style.{{ is_rtl() ? 'right' : 'left' }} = (rect.{{ is_rtl() ? 'right' : 'left' }} + rect.width / 2 - tooltip.offsetWidth / 2) + 'px';

    // Store reference for cleanup
    element._tooltip = tooltip;

    // Remove title to prevent browser tooltip
    element.setAttribute('data-original-title', title);
    element.removeAttribute('title');
}

function hideTooltip(event) {
    const element = event.target;
    if (element._tooltip) {
        element._tooltip.remove();
        element._tooltip = null;
    }

    // Restore title
    const originalTitle = element.getAttribute('data-original-title');
    if (originalTitle) {
        element.setAttribute('title', originalTitle);
        element.removeAttribute('data-original-title');
    }
}

// Initialize tooltips on page load
document.addEventListener('DOMContentLoaded', initTooltips);
</script>
@endpush

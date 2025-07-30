@extends('admin.layouts.app')

@section('title', __('File Management'))

@push('breadcrumbs')
    @php
    $breadcrumbs = [
        ['name' => __('File Management')]
    ];
    @endphp
@endpush

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('File Management') }}</h1>
                <p class="text-gray-600">{{ __('Manage and organize your files efficiently') }}</p>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary-600">{{ $statistics['total_files'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">{{ __('Total Files') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ formatBytes($statistics['total_size'] ?? 0) }}</div>
                    <div class="text-sm text-gray-500">{{ __('Total Size') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ count($statistics['files_by_category'] ?? []) }}</div>
                    <div class="text-sm text-gray-500">{{ __('Categories') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ count($statistics['files_by_type'] ?? []) }}</div>
                    <div class="text-sm text-gray-500">{{ __('File Types') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- File Statistics -->
    @if(isset($statistics['files_by_type']) && !empty($statistics['files_by_type']))
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Files by Type') }}</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($statistics['files_by_type'] as $type => $count)
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl mb-2">
                        @switch($type)
                            @case('image')
                                <i class="fas fa-image file-icon-image"></i>
                                @break
                            @case('video')
                                <i class="fas fa-video file-icon-video"></i>
                                @break
                            @case('audio')
                                <i class="fas fa-music file-icon-audio"></i>
                                @break
                            @case('document')
                                <i class="fas fa-file-pdf file-icon-document"></i>
                                @break
                            @case('office')
                                <i class="fas fa-file-word file-icon-office"></i>
                                @break
                            @default
                                <i class="fas fa-file file-icon-other"></i>
                        @endswitch
                    </div>
                    <div class="font-semibold text-gray-900">{{ $count }}</div>
                    <div class="text-sm text-gray-500">{{ ucfirst(__($type)) }}</div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Recent Files -->
    @if(isset($statistics['recent_uploads']) && $statistics['recent_uploads']->isNotEmpty())
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">{{ __('Recently Uploaded') }}</h2>
            <a href="{{ route('admin.files.index') }}" class="text-primary-600 hover:text-primary-700 text-sm">
                {{ __('View All') }}
            </a>
        </div>

        <div class="space-y-3">
            @foreach($statistics['recent_uploads'] as $file)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                        <div class="flex-shrink-0">
                            @if($file->is_image)
                                <img src="{{ $file->file_url }}" alt="{{ $file->title }}" class="h-10 w-10 rounded object-cover">
                            @else
                                <div class="h-10 w-10 rounded bg-white flex items-center justify-center border border-gray-200">
                                    <i class="{{ $file->file_icon }} text-gray-500"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">{{ $file->title }}</div>
                            <div class="text-sm text-gray-500">
                                {{ $file->human_readable_size }} • {{ $file->created_at->diffForHumans() }}
                                @if($file->category)
                                    • {{ $file->category->name }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                        <a href="{{ route('admin.files.download', $file) }}"
                           class="text-primary-600 hover:text-primary-700">
                            <i class="fas fa-download"></i>
                        </a>
                        <a href="{{ route('admin.files.show', $file) }}"
                           class="text-gray-600 hover:text-gray-700">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- File Manager Component -->
    <div class="bg-white rounded-lg shadow">
        @livewire('admin.file-manager')
    </div>
</div>


@endsection

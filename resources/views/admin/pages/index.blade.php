@extends('admin.layouts.app')

@section('title', trans('all.dashboard'))

@section('content')
    <div class="bg-white rounded-xl p-5 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between shadow-card border border-gray-100 hover:shadow-card-hover transition-all duration-300">
        <div class="flex items-center mb-4 sm:mb-0">
            @if(Auth::guard('admin')->user()->getFirstMediaUrl('profile_image'))
                <img src="{{ Auth::guard('admin')->user()->getFirstMediaUrl('profile_image') }}" alt="{{ trans('all.user_avatar') }}" class="w-12 h-12 rounded-full">
            @else
                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                    <span class="text-indigo-800 font-medium text-lg">{{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}</span>
                </div>
            @endif
            <div class="mr-3">
                <div class="flex items-center">
                    <h2 class="text-lg font-bold text-gray-900">{{ trans('all.welcome') }}, {{ Auth::guard('admin')->user()->name }}</h2>
                    <span class="mr-2 px-2 py-0.5 text-xs rounded-full bg-primary-100 text-primary-800">{{ Auth::guard('admin')->user()->getRoleNames()->first() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
         <div class="bg-white rounded-xl p-5 shadow-card border border-gray-100 hover:shadow-card-hover transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">{{ trans('all.categories') }}</h3>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ getCategoriesCount() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-list text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Revenue Card -->
        <div class="bg-white rounded-xl p-5 shadow-card border border-gray-100 hover:shadow-card-hover transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">{{ trans('all.admins') }}</h3>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ getAdminCount() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Customers Card -->
        <div class="bg-white rounded-xl p-5 shadow-card border border-gray-100 hover:shadow-card-hover transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">{{ trans('all.roles') }}</h3>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ getRoleCount() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fa-solid fa-users-gear"></i>
                </div>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="bg-white rounded-xl p-5 shadow-card border border-gray-100 hover:shadow-card-hover transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">{{ trans('all.permissions') }}</h3>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ getPermissionCount() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
            </div>
        </div>
    </div>
@endsection

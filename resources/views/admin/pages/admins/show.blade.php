@extends('admin.layouts.app')

@section('title', trans('all.view_admin') . ': ' . $admin->name)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">{{ trans('all.view_admin') }}: {{ $admin->name }}</h1>
            <div class="flex space-x-3 space-x-reverse">
                <a href="{{ route('admin.admins.edit', $admin) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    <svg class="-ml-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    {{ trans('all.edit') }}
                </a>
                <a href="{{ route('admin.admins.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg class="-ml-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    {{ trans('all.back_to_admins_list') }}
                </a>
            </div>
        </div>

        <!-- Admin Profile -->
        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ trans('all.profile_information') }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ trans('all.personal_and_account_details') }}</p>
                </div>
                <div>
                    @if($admin->status)
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            {{ trans('all.active') }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            {{ trans('all.inactive') }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="border-t border-gray-200">
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-20 w-20">
                            @if($admin->profile_image_url)
                                <img class="h-20 w-20 rounded-full object-cover" src="{{ $admin->profile_image_url }}" alt="{{ $admin->name }}">
                            @else
                                <div class="h-20 w-20 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-indigo-800 font-medium text-2xl">{{ strtoupper(substr($admin->name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="mr-5">
                            <h2 class="text-xl font-bold text-gray-900">{{ $admin->name }}</h2>
                            <p class="text-gray-500">{{ $admin->email }}</p>
                            <div class="mt-2 flex flex-wrap gap-1">
                                @foreach($admin->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ trans('all.full_name') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $admin->name }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ trans('all.email') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $admin->email }}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ trans('all.roles') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="flex flex-wrap gap-2">
                                @foreach($admin->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </div>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ trans('all.status') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if($admin->status)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ trans('all.active') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ trans('all.inactive') }}
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ trans('all.created_at') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $admin->created_at->format('Y/m/d H:i:s') }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ trans('all.updated_at') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $admin->updated_at->format('Y/m/d H:i:s') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Delete Admin Form -->
        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-red-600">{{ trans('all.danger_zone') }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ trans('all.danger_zone_warning') }}</p>
            </div>

            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" onsubmit="return confirm('{{ trans('all.delete_admin_confirmation') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="-ml-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ trans('all.delete_admin') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

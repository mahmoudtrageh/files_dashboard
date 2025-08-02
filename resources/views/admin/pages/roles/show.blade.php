@extends('admin.layouts.app')

@section('title', trans('all.view_role') . ': ' . $role->name)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">{{ trans('all.view_role') }}: {{ $role->name }}</h1>
            <div class="flex space-x-3 space-x-reverse">
                <a href="{{ route('admin.roles.edit', $role) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    <svg class="-ml-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    {{ trans('all.edit') }}
                </a>
                <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg class="-ml-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    {{ trans('all.back_to_roles_list') }}
                </a>
            </div>
        </div>

        <!-- Role Details -->
        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ trans('all.role_information') }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ trans('all.role_details_description') }}</p>
                </div>
                <div class="flex-shrink-0 h-16 w-16">
                    <div class="h-16 w-16 rounded-full bg-indigo-100 flex items-center justify-center">
                        <svg class="h-8 w-8 text-indigo-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ trans('all.role_name') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $role->name }}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ trans('all.users_count') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $users->total() }}
                            </span>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ trans('all.created_at') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $role->created_at->format('Y/m/d H:i:s') }}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ trans('all.updated_at') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $role->updated_at->format('Y/m/d H:i:s') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Role Permissions -->
        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ trans('all.role_permissions') }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ trans('all.role_permissions_description') }}</p>
            </div>

            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                @php
                    // Group permissions by their module/group if they follow a pattern like module.action
                    $groupedPermissions = $role->permissions->groupBy(function($permission) {
                        $parts = explode('.', $permission->name);
                        return count($parts) > 1 ? $parts[0] : 'general';
                    });
                @endphp

                @if($role->permissions->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($groupedPermissions as $group => $permissions)
                            <div class="bg-gray-50 p-4 rounded-md">
                                <h4 class="text-md font-medium text-gray-800 mb-2">{{ ucfirst($group) }}</h4>
                                <ul class="space-y-1">
                                    @foreach($permissions as $permission)
                                        <li class="text-sm text-gray-700 flex items-center">
                                            <svg class="h-4 w-4 text-green-500 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $permission->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-sm text-gray-500 text-center py-4">
                        {{ trans('all.no_permissions_assigned') }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Users with this Role -->
        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ trans('all.users_with_role') }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ trans('all.users_with_role_description') }}</p>
            </div>

            <div class="border-t border-gray-200">
                @if($users->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans('all.user') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans('all.email') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans('all.created_at') }}
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">{{ trans('all.view') }}</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($user->getFirstMediaUrl('profile_image'))
                                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $user->getFirstMediaUrl('profile_image') }}" alt="{{ $user->name }}">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                        <span class="text-indigo-800 font-medium text-lg">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="mr-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $user->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->created_at->format('Y/m/d') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                        @if(Route::has('admin.admins.show'))
                                            <a href="{{ route('admin.admins.show', $user) }}" class="text-indigo-600 hover:text-indigo-900">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                        {{ $users->links('pagination::tailwind') }}
                    </div>
                @else
                    <div class="text-sm text-gray-500 text-center py-8">
                        {{ trans('all.no_users_with_role') }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Delete Role Form -->
        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-red-600">{{ trans('all.danger_zone') }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ trans('all.danger_zone_warning') }}</p>
            </div>

            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('{{ trans('all.delete_role_confirmation') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="-ml-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ trans('all.delete_role') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

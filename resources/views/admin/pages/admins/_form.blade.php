@php
    $isEditMode = isset($admin);
    $hasProfileImage = $isEditMode && $admin->getFirstMediaUrl('profile_image') && !empty($admin->getFirstMediaUrl('profile_image'));
@endphp

<div class="px-4 py-5 bg-white sm:p-6 rounded-lg shadow">
    <div class="grid grid-cols-6 gap-6">
        <div class="col-span-6 sm:col-span-3">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ trans('all.name') }}</label>
            <div class="relative rounded-md shadow-sm">
                <input type="text" name="name" id="name" value="{{ old('name', $isEditMode ? $admin->name : '') }}"
                    class="block w-full pr-10 py-3 border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm"
                    placeholder="{{ trans('all.enter_full_name') }}" required>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-6 sm:col-span-3">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ trans('all.email') }}</label>
            <div class="relative rounded-md shadow-sm">
                <input type="email" name="email" id="email" value="{{ old('email', $isEditMode ? $admin->email : '') }}"
                    class="block w-full pr-10 py-3 border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm"
                    placeholder="{{ trans('all.example_email') }}" required>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                </div>
            </div>
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-6 sm:col-span-3">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ trans('all.password') }}{{ $isEditMode ? " (".trans('all.keep_it_empty_to_preserve_password').")" : '' }}</label>
            <div class="relative rounded-md shadow-sm">
                <input type="password" name="password" id="password"
                    class="block w-full pr-10 py-3 border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm"
                    placeholder="{{ trans('all.password_placeholder') }}" {{ $isEditMode ? '' : 'required' }}>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <button type="button" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-500 focus:outline-none toggle-password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            <div class="mt-3 {{ $isEditMode ? 'password-validation-container' : '' }}">
                <div class="password-strength-meter h-2 bg-gray-200 rounded-full overflow-hidden mt-2">
                    <div class="strength-bar h-full transition-all duration-300"></div>
                </div>

                <div class="password-requirements mt-2">
                    <p class="text-sm font-medium text-gray-700 mb-2">{{ trans('all.password_requirements') }}:</p>
                    <ul class="text-xs text-gray-600 space-y-1 mr-4">
                        <li id="req-length" class="flex items-center">
                            <i class="fas fa-times-circle text-red-500 ml-2"></i>
                            <span>{{ trans('all.min_10_chars') }}</span>
                        </li>
                        <li id="req-uppercase" class="flex items-center">
                            <i class="fas fa-times-circle text-red-500 ml-2"></i>
                            <span>{{ trans('all.one_uppercase') }}</span>
                        </li>
                        <li id="req-lowercase" class="flex items-center">
                            <i class="fas fa-times-circle text-red-500 ml-2"></i>
                            <span>{{ trans('all.one_lowercase') }}</span>
                        </li>
                        <li id="req-number" class="flex items-center">
                            <i class="fas fa-times-circle text-red-500 ml-2"></i>
                            <span>{{ trans('all.one_number') }}</span>
                        </li>
                        <li id="req-special" class="flex items-center">
                            <i class="fas fa-times-circle text-red-500 ml-2"></i>
                            <span>{{ trans('all.one_special_char') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-6 sm:col-span-3">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">{{ trans('all.confirm_password') }}</label>
            <div class="relative rounded-md shadow-sm">
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="block w-full pr-10 py-3 border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm"
                    placeholder="{{ trans('all.password_placeholder') }}" {{ $isEditMode ? '' : 'required' }}>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <button type="button" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-500 focus:outline-none toggle-password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <div class="mt-2">
                <div id="req-match" class="flex items-center {{ $isEditMode ? 'password-match-indicator' : '' }}">
                    <i class="fas fa-times-circle text-red-500 ml-2"></i>
                    <span class="text-xs text-gray-600">{{ trans('all.password_match') }}</span>
                </div>
            </div>
        </div>

        <div class="col-span-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ trans('all.roles') }}</label>
            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($roles as $role)
                    <div class="relative flex items-start p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-center h-5">
                            <input id="role-{{ $role->id }}" name="roles[]" type="checkbox" value="{{ $role->id }}"
                                {{ (old('roles') && in_array($role->id, old('roles'))) || ($isEditMode && $admin->roles->contains($role->id)) ? 'checked' : '' }}
                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 transition-colors duration-200 role-checkbox">
                        </div>
                        <div class="mr-3 text-sm">
                            <label for="role-{{ $role->id }}" class="font-medium text-gray-700">{{ $role->name }}</label>
                            @if($role->description)
                                <p class="text-gray-500">{{ $role->description }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div id="roles-validation-message" class="hidden mt-2 text-sm text-red-600">
                <div class="flex items-center">
                    <svg class="h-5 w-5 ml-2 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ trans('all.select_at_least_one_role') }}</span>
                </div>
            </div>
            @error('roles')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-6">
            <div class="relative flex items-start p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center h-5">
                    <input id="status" name="status" type="checkbox" value="1"
                        {{ old('status', $isEditMode && $admin->status ? 'checked' : '') }}
                        class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 transition-colors duration-200">
                </div>
                <div class="mr-3 text-sm">
                    <label for="status" class="font-medium text-gray-700">{{ trans('all.active') }}</label>
                    <p class="text-gray-500">{{ trans('all.active_admin_description') }}</p>
                </div>
            </div>
        </div>

        <div class="col-span-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ trans('all.profile_image') }}</label>
            <div class="mt-2">
                <div class="relative p-6 border-2 border-dashed border-gray-300 rounded-md bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                    <div class="text-center" id="image-display-area">
                        @if($hasProfileImage)
                            <div id="existing-image-container" class="mb-4">
                                <img src="{{ $admin->getFirstMediaUrl('profile_image') }}" alt="{{ $admin->name }}" class="h-24 w-24 rounded-full object-cover ring-2 ring-indigo-500 ring-offset-2 mx-auto">
                            </div>
                        @else
                            <div id="upload-icon">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        @endif
                        <div id="preview-container" class="hidden mb-4">
                            <img id="preview-image" src="#" alt="{{ trans('all.image_preview') }}" class="h-24 w-24 rounded-full object-cover ring-2 ring-indigo-500 ring-offset-2 mx-auto">
                        </div>
                    </div>

                    <div class="flex text-sm text-gray-600 justify-center mt-4">
                        <label for="profile_image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 px-4 py-3">
                            <span>{{ $hasProfileImage ? trans('all.change_image') : trans('all.upload_image') }}</span>
                            <input id="profile_image" name="profile_image" type="file" class="sr-only" accept="image/*">
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 text-center mt-2">{{ trans('all.image_requirements') }}</p>
                </div>
            </div>
            @error('profile_image')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

<div class="px-4 py-3 bg-gray-50 text-left sm:px-6 rounded-b-lg mt-3">
    <button type="submit" id="submit-button" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
        {{ $isEditMode ? trans('all.update_admin') : trans('all.add_admin') }}
    </button>
    <a href="{{ route('admin.admins.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2 transition-colors duration-200">
        {{ trans('all.cancel') }}
    </a>
</div>

@extends('admin.layouts.app')

@section('title', trans('all.Edit Profile'))

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Tabs Navigation with improved spacing -->
        <div class="bg-white rounded-xl p-5 mb-8 shadow-card border border-gray-100">
            <div class="flex flex-wrap border-b border-gray-100">
                <button class="profile-tab-btn px-8 py-4 text-sm font-medium text-primary-700 border-b-2 border-primary-600 hover:bg-gray-50 transition-all duration-200 focus:outline-none" data-tab="personal-info">
                    <i class="fas fa-user ml-2"></i>
                    {{ trans('all.Personal Information') }}
                </button>
                <button class="profile-tab-btn px-8 py-4 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:bg-gray-50 transition-all duration-200 focus:outline-none" data-tab="security">
                    <i class="fas fa-lock ml-2"></i>
                    {{ trans('all.Security') }}
                </button>
            </div>
        </div>

        <!-- Session Messages -->
        @if(session('success'))
            <div class="mb-6 rounded-lg bg-green-50 p-4 border-l-4 border-green-500">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-lg bg-red-50 p-4 border-l-4 border-red-500">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Personal Info Tab Content -->
        <div id="personal-info" class="profile-tab-content">
            <!-- Profile Information Card -->
            <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden mb-8">
                <div class="px-8 py-5 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">{{ trans('all.Personal Information') }}</h3>
                    <p class="text-sm text-gray-500 mt-2">{{ trans('all.Update your personal information and contact details') }}</p>
                </div>
                <div class="p-8">
                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="flex flex-col md:flex-row items-start gap-12 mb-10">
                            <!-- Profile Image Section -->
                            <div class="w-full md:w-1/3">
                                <label class="block text-sm font-medium text-gray-700 mb-3">{{ trans('all.Profile Image') }}</label>
                                <div class="mt-2">
                                    <div class="relative p-8 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                        <div class="text-center" id="image-display-area">
                                            @if(Auth::guard('admin')->user()->getFirstMediaUrl('profile_image'))
                                                <div id="existing-image-container" class="mb-6">
                                                    <img src="{{  Auth::guard('admin')->user()->getFirstMediaUrl('profile_image') }}" alt="{{ $admin->name }}" class="h-32 w-32 rounded-full object-cover ring-2 ring-indigo-500 ring-offset-2 mx-auto">
                                                </div>
                                            @else
                                                <div id="upload-icon" class="mb-6">
                                                    <svg class="mx-auto h-16 w-16 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div id="preview-container" class="hidden mb-6">
                                                <img id="preview-image" src="#" alt="{{ trans('all.image_preview') }}" class="h-32 w-32 rounded-full object-cover ring-2 ring-indigo-500 ring-offset-2 mx-auto">
                                            </div>
                                        </div>

                                        <div class="flex text-sm text-gray-600 justify-center mt-6">
                                            <label for="profile_image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 px-6 py-3">
                                                <span>{{ Auth::guard('admin')->user()->getFirstMediaUrl('profile_image') ? trans('all.change_image') : trans('all.upload_image') }}</span>
                                                <input id="profile_image" name="profile_image" type="file" class="sr-only" accept="image/*">
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500 text-center mt-4">{{ trans('all.PNG JPG GIF up to 2MB') }}</p>
                                    </div>
                                </div>
                                @error('profile_image')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Form Fields Section -->
                            <div class="w-full md:w-2/3">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div>
                                        <label for="firstName" class="block text-sm font-medium text-gray-700 mb-2">{{ trans('all.Name') }}</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <i class="fas fa-user text-gray-400"></i>
                                            </div>
                                            <input type="text" id="firstName" name="name" value="{{ Auth::guard('admin')->user()->name }}" class="w-full border border-gray-200 rounded-lg px-4 pr-10 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200" required>
                                        </div>
                                        @error('name')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">{{ trans('all.Email') }}</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <i class="fas fa-envelope text-gray-400"></i>
                                            </div>
                                            <input type="email" id="email" name="email" value="{{ Auth::guard('admin')->user()->email }}" class="w-full border border-gray-200 rounded-lg px-4 pr-10 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200" required>
                                        </div>
                                        @error('email')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-6 mt-6">
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200">
                                    <i class="fas fa-save ml-2"></i>
                                    {{ trans('all.Save Changes') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Security Tab Content (Hidden by default) -->
        <div id="security" class="profile-tab-content hidden">
            <!-- Change Password Card -->
            <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden mb-8">
                <div class="px-8 py-5 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">{{ trans('all.Change Password') }}</h3>
                    <p class="text-sm text-gray-500 mt-2">{{ trans('all.Update your password to ensure account security') }}</p>
                </div>
                <div class="p-8">
                    <form action="{{ route('admin.profile.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="max-w-lg space-y-6">
                            <div>
                                <label for="currentPassword" class="block text-sm font-medium text-gray-700 mb-2">{{ trans('all.Current Password') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input type="password" id="currentPassword" name="currentPassword" class="w-full border border-gray-200 rounded-lg px-4 pr-10 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                                    <button type="button" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-500 focus:outline-none toggle-password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('currentPassword')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="newPassword" class="block text-sm font-medium text-gray-700 mb-2">{{ trans('all.New Password') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input type="password" id="newPassword" name="newPassword" class="w-full border border-gray-200 rounded-lg px-4 pr-10 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                                    <button type="button" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-500 focus:outline-none toggle-password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="mt-3">
                                    <div class="password-strength-meter h-2 bg-gray-200 rounded-full overflow-hidden mt-2">
                                        <div class="strength-bar h-full transition-all duration-300"></div>
                                    </div>

                                    <div class="password-requirements mt-2">
                                        <p class="text-sm font-medium text-gray-700 mb-2">{{ trans('all.Password must contain') }}</p>
                                        <ul class="text-xs text-gray-600 space-y-1 mr-4">
                                            <li id="req-length" class="flex items-center">
                                                <i class="fas fa-times-circle text-red-500 ml-2"></i>
                                                <span>{{ trans('all.At least 8 characters') }}</span>
                                            </li>
                                            <li id="req-uppercase" class="flex items-center">
                                                <i class="fas fa-times-circle text-red-500 ml-2"></i>
                                                <span>{{ trans('all.At least one uppercase letter') }}</span>
                                            </li>
                                            <li id="req-lowercase" class="flex items-center">
                                                <i class="fas fa-times-circle text-red-500 ml-2"></i>
                                                <span>{{ trans('all.At least one lowercase letter') }}</span>
                                            </li>
                                            <li id="req-number" class="flex items-center">
                                                <i class="fas fa-times-circle text-red-500 ml-2"></i>
                                                <span>{{ trans('all.At least one number') }}</span>
                                            </li>
                                            <li id="req-special" class="flex items-center">
                                                <i class="fas fa-times-circle text-red-500 ml-2"></i>
                                                <span>{{ trans('all.At least one special character') }}</span>
                                            </li>
                                            <li id="req-match" class="flex items-center mt-2">
                                                <i class="fas fa-times-circle text-red-500 ml-2"></i>
                                                <span>{{ trans('all.Password matches confirmation') }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                @error('newPassword')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="newPassword_confirmation" class="block text-sm font-medium text-gray-700 mb-2">{{ trans('all.Confirm Password') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input type="password" id="newPassword_confirmation" name="newPassword_confirmation" class="w-full border border-gray-200 rounded-lg px-4 pr-10 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                                    <button type="button" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-500 focus:outline-none toggle-password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="pt-6">
                                <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200">
                                    <i class="fas fa-lock ml-2"></i>
                                    {{ trans('all.Update Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Profile Tab Functionality
            const tabButtons = document.querySelectorAll('.profile-tab-btn');
            const tabContents = document.querySelectorAll('.profile-tab-content');

            // Function to activate a specific tab
            function activateTab(tabId) {
                // Deactivate all tabs
                tabButtons.forEach(btn => {
                    btn.classList.remove('text-primary-700', 'border-primary-600');
                    btn.classList.add('text-gray-500', 'border-transparent');
                });

                // Hide all tab contents
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });

                // Activate the selected tab button
                const selectedButton = document.querySelector(`.profile-tab-btn[data-tab="${tabId}"]`);
                if (selectedButton) {
                    selectedButton.classList.remove('text-gray-500', 'border-transparent');
                    selectedButton.classList.add('text-primary-700', 'border-primary-600');
                }

                // Show the selected tab content
                const selectedContent = document.getElementById(tabId);
                if (selectedContent) {
                    selectedContent.classList.remove('hidden');
                }

                // Save the active tab to localStorage
                localStorage.setItem('activeProfileTab', tabId);
            }

            // Add click event listeners to tab buttons
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');
                    activateTab(targetTab);
                });
            });

            // Check if there's a stored active tab and activate it
            const storedTab = localStorage.getItem('activeProfileTab');
            if (storedTab) {
                activateTab(storedTab);
            } else {
                // Default to the first tab if no stored tab
                activateTab('personal-info');
            }

            // Toggle Password Visibility
            const togglePasswordButtons = document.querySelectorAll('.toggle-password');

            togglePasswordButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const passwordInput = this.parentElement.querySelector('input');
                    const icon = this.querySelector('i');

                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });

            // Password validation and button toggle
            const newPasswordInput = document.getElementById('newPassword');
            const confirmPasswordInput = document.getElementById('newPassword_confirmation');
            const securityTab = document.getElementById('security');
            const submitButton = securityTab ? securityTab.querySelector('button[type="submit"]') : null;
            const strengthBar = document.querySelector('.strength-bar');
            let passwordMeetsRequirements = false;

            // Disable submit button initially if we're on the security tab
            if (securityTab && !securityTab.classList.contains('hidden') && submitButton) {
                submitButton.disabled = true;
                submitButton.classList.add('opacity-50', 'cursor-not-allowed');
            }

            function checkPasswordRequirements(password) {
                // Check all password criteria
                const minLength = password.length >= 8;
                const hasUppercase = /[A-Z]/.test(password);
                const hasLowercase = /[a-z]/.test(password);
                const hasNumbers = /[0-9]/.test(password);
                const hasSpecial = /[^A-Za-z0-9]/.test(password);

                // Return true only if all criteria are met
                return minLength && hasUppercase && hasLowercase && hasNumbers && hasSpecial;
            }

            function updateSubmitButton() {
                if (!submitButton) return;

                const password = newPasswordInput ? newPasswordInput.value : '';
                const confirmPassword = confirmPasswordInput ? confirmPasswordInput.value : '';
                const passwordsMatch = password === confirmPassword;
                const currentPasswordInput = document.getElementById('currentPassword');
                const currentPasswordFilled = currentPasswordInput ? currentPasswordInput.value.length > 0 : false;

                // Only enable button when all criteria are met AND passwords match
                const shouldEnable = passwordMeetsRequirements && passwordsMatch &&
                                    currentPasswordFilled && confirmPassword.length > 0;

                submitButton.disabled = !shouldEnable;

                if (shouldEnable) {
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }

            if (newPasswordInput && strengthBar) {
                newPasswordInput.addEventListener('input', function() {
                    const password = this.value;
                    const confirmPassword = confirmPasswordInput ? confirmPasswordInput.value : '';
                    let strength = 0;

                    // Check password criteria for visual strength meter
                    if (password.length >= 8) strength++;
                    if (password.match(/[A-Z]/)) strength++;
                    if (password.match(/[a-z]/)) strength++;
                    if (password.match(/[0-9]/)) strength++;
                    if (password.match(/[^A-Za-z0-9]/)) strength++;

                    // Calculate percentage and update bar width
                    const percentage = (strength / 5) * 100;
                    strengthBar.style.width = `${percentage}%`;

                    // Update bar color based on strength
                    if (strength <= 1) {
                        strengthBar.className = 'strength-bar h-full bg-red-500';
                    } else if (strength <= 3) {
                        strengthBar.className = 'strength-bar h-full bg-yellow-500';
                    } else if (strength === 4) {
                        strengthBar.className = 'strength-bar h-full bg-blue-500';
                    } else {
                        strengthBar.className = 'strength-bar h-full bg-green-500';
                    }

                    // Check all requirements for enable/disable
                    passwordMeetsRequirements = checkPasswordRequirements(password);
                    updateSubmitButton();

                    // Update requirements checklist
                    document.querySelector('#req-length i').className =
                        password.length >= 8 ? 'fas fa-check-circle text-green-500 ml-2' : 'fas fa-times-circle text-red-500 ml-2';

                    document.querySelector('#req-uppercase i').className =
                        /[A-Z]/.test(password) ? 'fas fa-check-circle text-green-500 ml-2' : 'fas fa-times-circle text-red-500 ml-2';

                    document.querySelector('#req-lowercase i').className =
                        /[a-z]/.test(password) ? 'fas fa-check-circle text-green-500 ml-2' : 'fas fa-times-circle text-red-500 ml-2';

                    document.querySelector('#req-number i').className =
                        /[0-9]/.test(password) ? 'fas fa-check-circle text-green-500 ml-2' : 'fas fa-times-circle text-red-500 ml-2';

                    document.querySelector('#req-special i').className =
                        /[^A-Za-z0-9]/.test(password) ? 'fas fa-check-circle text-green-500 ml-2' : 'fas fa-times-circle text-red-500 ml-2';

                    document.querySelector('#req-match i').className =
                        (password === confirmPassword && password !== '') ? 'fas fa-check-circle text-green-500 ml-2' : 'fas fa-times-circle text-red-500 ml-2';
                });
            }

            // Also check when confirmation password changes
            if (confirmPasswordInput) {
                confirmPasswordInput.addEventListener('input', function() {
                    const password = newPasswordInput ? newPasswordInput.value : '';
                    const confirmPassword = this.value;

                    document.querySelector('#req-match i').className =
                        (password === confirmPassword && password !== '') ? 'fas fa-check-circle text-green-500 ml-2' : 'fas fa-times-circle text-red-500 ml-2';

                    updateSubmitButton();
                });
            }

            // And check when current password changes
            const currentPasswordInput = document.getElementById('currentPassword');
            if (currentPasswordInput) {
                currentPasswordInput.addEventListener('input', updateSubmitButton);
            }

            // Update button state when switching tabs
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');
                    if (targetTab === 'security') {
                        // When switching to security tab, check password requirements
                        updateSubmitButton();
                    } else {
                        // When on other tabs, enable the submit button
                        const otherTabSubmitButton = document.querySelector('#' + targetTab + ' button[type="submit"]');
                        if (otherTabSubmitButton) {
                            otherTabSubmitButton.disabled = false;
                            otherTabSubmitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    }
                });
            });

            // Image handling
            const fileInput = document.getElementById('profile_image');

            // Add change event listener for the file input if it exists
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    const previewContainer = document.getElementById('preview-container');
                    const previewImage = document.getElementById('preview-image');
                    const uploadIcon = document.getElementById('upload-icon');
                    const existingImageContainer = document.getElementById('existing-image-container');

                    if (this.files && this.files[0]) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            // Check if we have preview elements before manipulating them
                            if (previewImage) {
                                previewImage.src = e.target.result;
                            }

                            // Hide both the upload icon and existing image if present
                            if (uploadIcon) {
                                uploadIcon.classList.add('hidden');
                            }

                            if (existingImageContainer) {
                                existingImageContainer.classList.add('hidden');
                            }

                            if (previewContainer) {
                                previewContainer.classList.remove('hidden');
                            }

                            // Reset remove flag
                            if (removePhotoInput) {
                                removePhotoInput.value = '0';
                            }
                        };

                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }
        });
    </script>
@endsection

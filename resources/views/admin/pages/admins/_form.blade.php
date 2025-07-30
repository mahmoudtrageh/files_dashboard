@php
    $isEditMode = isset($admin);
    $hasProfileImage = $isEditMode && $admin->getFirstMediaUrl('profile_image') && !empty($admin->getFirstMediaUrl('profile_image'));
@endphp

<div class="px-4 py-5 bg-white sm:p-6 rounded-lg shadow">
    <div class="grid grid-cols-6 gap-6">
        <div class="col-span-6 sm:col-span-3">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
            <div class="relative rounded-md shadow-sm">
                <input type="text" name="name" id="name" value="{{ old('name', $isEditMode ? $admin->name : '') }}" 
                    class="block w-full pr-10 py-3 border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm" 
                    placeholder="أدخل الاسم الكامل" required>
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
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
            <div class="relative rounded-md shadow-sm">
                <input type="email" name="email" id="email" value="{{ old('email', $isEditMode ? $admin->email : '') }}" 
                    class="block w-full pr-10 py-3 border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm" 
                    placeholder="example@domain.com" required>
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
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور {{ $isEditMode ? '(اتركها فارغة للاحتفاظ بنفس كلمة المرور)' : '' }}</label>
            <div class="relative rounded-md shadow-sm">
                <input type="password" name="password" id="password" 
                    class="block w-full pr-10 py-3 border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm" 
                    placeholder="●●●●●●●●" {{ $isEditMode ? '' : 'required' }}>
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
                    <p class="text-sm font-medium text-gray-700 mb-2">كلمة المرور يجب أن تحتوي على:</p>
                    <ul class="text-xs text-gray-600 space-y-1 mr-4">
                        <li id="req-length" class="flex items-center">
                            <i class="fas fa-times-circle text-red-500 ml-2"></i>
                            <span>١٠ أحرف على الأقل</span>
                        </li>
                        <li id="req-uppercase" class="flex items-center">
                            <i class="fas fa-times-circle text-red-500 ml-2"></i>
                            <span>حرف كبير واحد على الأقل</span>
                        </li>
                        <li id="req-lowercase" class="flex items-center">
                            <i class="fas fa-times-circle text-red-500 ml-2"></i>
                            <span>حرف صغير واحد على الأقل</span>
                        </li>
                        <li id="req-number" class="flex items-center">
                            <i class="fas fa-times-circle text-red-500 ml-2"></i>
                            <span>رقم واحد على الأقل</span>
                        </li>
                        <li id="req-special" class="flex items-center">
                            <i class="fas fa-times-circle text-red-500 ml-2"></i>
                            <span>رمز خاص واحد على الأقل (@$!%*#?&)</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-6 sm:col-span-3">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">تأكيد كلمة المرور</label>
            <div class="relative rounded-md shadow-sm">
                <input type="password" name="password_confirmation" id="password_confirmation" 
                    class="block w-full pr-10 py-3 border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 sm:text-sm" 
                    placeholder="●●●●●●●●" {{ $isEditMode ? '' : 'required' }}>
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
                    <span class="text-xs text-gray-600">تطابق كلمة المرور مع التأكيد</span>
                </div>
            </div>
        </div>

        <div class="col-span-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">الأدوار</label>
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
                    <span>يجب تحديد دور واحد على الأقل</span>
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
                    <label for="status" class="font-medium text-gray-700">نشط</label>
                    <p class="text-gray-500">فعّل هذا الخيار للسماح للمشرف بتسجيل الدخول واستخدام النظام.</p>
                </div>
            </div>
        </div>

        <div class="col-span-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">صورة الملف الشخصي</label>
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
                            <img id="preview-image" src="#" alt="معاينة الصورة" class="h-24 w-24 rounded-full object-cover ring-2 ring-indigo-500 ring-offset-2 mx-auto">
                        </div>
                    </div>
                    
                    <div class="flex text-sm text-gray-600 justify-center mt-4">
                        <label for="profile_image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 px-4 py-3">
                            <span>{{ $hasProfileImage ? 'تغيير الصورة' : 'رفع صورة' }}</span>
                            <input id="profile_image" name="profile_image" type="file" class="sr-only" accept="image/*">
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 text-center mt-2">PNG, JPG, GIF حتى 2MB</p>
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
        {{ $isEditMode ? 'تحديث المشرف' : 'إضافة المشرف' }}
    </button>
    <a href="{{ route('admin.admins.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2 transition-colors duration-200">
        إلغاء
    </a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get file input element
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
                };
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    // Password toggle functionality
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
    
    // Password strength and validation
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const submitButton = document.getElementById('submit-button');
    const strengthBar = document.querySelector('.strength-bar');
    const isEditMode = {{ $isEditMode ? 'true' : 'false' }};
    let passwordMeetsRequirements = isEditMode; // In edit mode, don't require password initially
    
    // Role validation variables
    const roleCheckboxes = document.querySelectorAll('.role-checkbox');
    const rolesValidationMessage = document.getElementById('roles-validation-message');
    
    function checkPasswordRequirements(password) {
        // If in edit mode and password is empty, we don't need to validate
        if (isEditMode && !password) {
            return true;
        }
        
        // Check all password criteria
        const minLength = password.length >= 10;
        const hasUppercase = /[A-Z]/.test(password);
        const hasLowercase = /[a-z]/.test(password);
        const hasNumbers = /[0-9]/.test(password);
        const hasSpecial = /[^A-Za-z0-9]/.test(password);
        
        // Return true only if all criteria are met or it's edit mode with empty password
        return (isEditMode && !password) || (minLength && hasUppercase && hasLowercase && hasNumbers && hasSpecial);
    }
    
    function checkRoles() {
        let isAtLeastOneChecked = false;
        
        roleCheckboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                isAtLeastOneChecked = true;
            }
        });
        
        // Toggle validation message and button state
        if (!isAtLeastOneChecked) {
            rolesValidationMessage.classList.remove('hidden');
        } else {
            rolesValidationMessage.classList.add('hidden');
        }
        
        return isAtLeastOneChecked;
    }
    
    function updateSubmitButton() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const passwordsMatch = password === confirmPassword;
        const rolesValid = checkRoles();
        
        // For edit mode, if password is empty we don't validate password or match
        const passwordValidation = isEditMode && !password ? true : (passwordMeetsRequirements && passwordsMatch);
        
        // Only enable button when all criteria are met
        const shouldEnable = (passwordValidation && rolesValid);
        
        submitButton.disabled = !shouldEnable;
        
        if (shouldEnable) {
            submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
        }
        
        // Update match indicator
        const matchElem = document.getElementById('req-match');
        if (matchElem) {
            const icon = matchElem.querySelector('i');
            if (isEditMode && !password) {
                // Don't show match indicator if no password is provided in edit mode
                if (!matchElem.classList.contains('password-match-indicator')) {
                    matchElem.classList.add('hidden');
                }
            } else {
                matchElem.classList.remove('hidden');
                icon.className = (password === confirmPassword && password !== '') ? 
                    'fas fa-check-circle text-green-500 ml-2' : 
                    'fas fa-times-circle text-red-500 ml-2';
            }
        }
    }
    
    // If we have the password field
    if (passwordInput && strengthBar) {
        // Also handle the password validation display for edit mode
        if (isEditMode) {
            const validationContainer = document.querySelector('.password-validation-container');
            const matchIndicator = document.querySelector('.password-match-indicator');
            
            // Initially hide validation elements in edit mode
            if (validationContainer) validationContainer.classList.add('hidden');
            if (matchIndicator) matchIndicator.classList.add('hidden');
            
            // Show validation when user starts typing
            passwordInput.addEventListener('input', function() {
                if (this.value) {
                    if (validationContainer) validationContainer.classList.remove('hidden');
                    if (matchIndicator) matchIndicator.classList.remove('hidden');
                } else {
                    if (validationContainer) validationContainer.classList.add('hidden');
                    if (matchIndicator) matchIndicator.classList.add('hidden');
                }
            });
        }
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            // If in edit mode and password is empty, we don't show validation
            if (isEditMode && !password) {
                passwordMeetsRequirements = true;
                updateSubmitButton();
                return;
            }
            
            // Check password criteria for visual strength meter
            if (password.length >= 10) strength++;
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
                password.length >= 10 ? 'fas fa-check-circle text-green-500 ml-2' : 'fas fa-times-circle text-red-500 ml-2';
                
            document.querySelector('#req-uppercase i').className = 
                /[A-Z]/.test(password) ? 'fas fa-check-circle text-green-500 ml-2' : 'fas fa-times-circle text-red-500 ml-2';
                
            document.querySelector('#req-lowercase i').className = 
                /[a-z]/.test(password) ? 'fas fa-check-circle text-green-500 ml-2' : 'fas fa-times-circle text-red-500 ml-2';
                
            document.querySelector('#req-number i').className = 
                /[0-9]/.test(password) ? 'fas fa-check-circle text-green-500 ml-2' : 'fas fa-times-circle text-red-500 ml-2';
                
            document.querySelector('#req-special i').className = 
                /[^A-Za-z0-9]/.test(password) ? 'fas fa-check-circle text-green-500 ml-2' : 'fas fa-times-circle text-red-500 ml-2';
        });
    }
    
    // Also check when confirmation password changes
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', updateSubmitButton);
    }
    
    // Add event listeners to all role checkboxes
    roleCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', updateSubmitButton);
    });
    
    // Initial check on page load
    updateSubmitButton();
    checkRoles();
    
    // Form submission validation
    const form = submitButton.closest('form');
    if (form) {
        form.addEventListener('submit', function(event) {
            const password = passwordInput.value;
            
            // In edit mode, if password is empty we skip password validation
            const passwordValid = isEditMode && !password ? true : checkPasswordRequirements(password);
            const rolesValid = checkRoles();
            
            if (!passwordValid || !rolesValid) {
                event.preventDefault();
                
                // Scroll to the first error
                if (!passwordValid) {
                    passwordInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else if (!rolesValid) {
                    rolesValidationMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }
});
</script>
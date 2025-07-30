@extends('admin.auth.layouts.app')

@section('title', 'Login')

@section('styles')

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f9fafb;
        }
        
        /* Fix RTL input styling */
        .rtl-input {
            text-align: right;
            direction: rtl;
        }
        
        .ltr-content {
            direction: ltr;
            text-align: left;
        }
        
        /* Password visibility toggle */
        .password-toggle {
            cursor: pointer;
        }
        
        /* Custom checkbox */
        .custom-checkbox {
            border-radius: 4px;
            transition: all 0.2s ease;
        }
        
        .custom-checkbox:checked {
            background-color: #f97316;
            border-color: #f97316;
        }
        
        /* Form focus effects */
        .form-input:focus {
            box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.2);
        }
        
        /* Page transition */
        .fade-in {
            animation: fadeIn 0.3s ease-in-out forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

@endsection

@section('content')

    <!-- Logo and branding -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-500 text-white mb-4">
            <i class="fas fa-fire text-2xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">فيلامنت</h1>
        <p class="text-gray-600 mt-1">لوحة تحكم الإدارة</p>
    </div>
    
    <!-- Login card -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-8">
        <!-- Login form -->
        <form class="space-y-5" action="{{ route('admin.login') }}" method="POST">
            @csrf
            <!-- Email/Username field -->
            <div>
                <div class="relative">
                    <input id="username" name="email" type="text" class="rtl-input form-input w-full py-2.5 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-0 transition-colors duration-200" placeholder="أدخل اسم المستخدم أو البريد الإلكتروني" required>
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                </div>
                @error('email')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <!-- Password field -->
            <div>
                <div class="relative">
                    <input id="password" name="password" type="password" class="rtl-input form-input w-full py-2.5 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-0 transition-colors duration-200" placeholder="أدخل كلمة المرور" required>
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i id="togglePassword" class="fas fa-eye-slash text-gray-400 password-toggle"></i>
                    </div>
                </div>
                @error('password')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <!-- Remember me -->
            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox" class="custom-checkbox h-4 w-4 border border-gray-300 focus:ring-0 focus:ring-offset-0">
                <label for="remember" class="mr-2 block text-sm text-gray-700">تذكرني</label>
            </div>
            
            <!-- Login button -->
            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200">
                تسجيل الدخول
            </button>
        </form>
    </div>

@endsection

@section('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            togglePassword.addEventListener('click', function() {
                // Toggle password visibility
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle eye icon
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });                       
        });
    </script>

@endsection
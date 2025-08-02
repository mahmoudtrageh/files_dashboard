{{-- resources/views/admin/layouts/header.blade.php --}}
<header class="bg-white shadow-sm sticky top-0 z-30 border-b border-gray-200">
    <div class="px-4 lg:px-6 flex items-center justify-between h-16">
        <!-- Left Side -->
        <div class="flex items-center space-x-4 {{ is_rtl() ? 'space-x-reverse' : '' }}">
            <!-- Mobile Menu Button -->
            <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-gray-700 focus:outline-none transition-all duration-200">
                <i class="fas fa-bars text-lg"></i>
            </button>

            <!-- Page Title -->
            <div class="flex flex-col">
                <h1 class="text-xl font-bold text-gray-800 leading-tight">
                    @yield('page-title', trans('all.dashboard'))
                </h1>
                @hasSection('page-subtitle')
                    <p class="text-sm text-gray-500 leading-tight">
                        @yield('page-subtitle')
                    </p>
                @endif
            </div>
        </div>

        <!-- Right Side -->
        <div class="flex items-center space-x-3 {{ is_rtl() ? 'space-x-reverse' : '' }}">
            <!-- Language Switcher -->
            <div class="dropdown-container relative">
                <button id="languageBtn" class="flex items-center space-x-2 {{ is_rtl() ? 'space-x-reverse' : '' }} p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-all duration-200 focus:outline-none">
                    <i class="fas fa-globe text-lg"></i>
                    <span class="hidden sm:block text-sm font-medium">
                        {{ is_rtl() ? 'العربية' : 'English' }}
                    </span>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200"></i>
                </button>

                <div id="languageDropdown" class="dropdown-menu bg-white mt-2 rounded-xl shadow-xl border border-gray-100 w-64 {{ is_rtl() ? 'dropdown-rtl' : 'dropdown-ltr' }}">
                    <!-- Header -->
                    <div class="px-4 py-3 border-b border-gray-100 bg-gray-50 rounded-t-xl">
                        <h3 class="font-semibold text-gray-900 text-sm">{{ trans('all.select_language') }}</h3>
                    </div>

                    <!-- Language Options -->
                    <div class="py-2">
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'ar']) }}"
                           class="flex items-center justify-between w-full px-4 py-3 text-sm rounded-lg mx-2 transition-all duration-200 group {{ app()->getLocale() === 'ar' ? 'bg-primary-50 text-primary-700 border border-primary-200' : 'text-gray-700 hover:bg-gray-100' }}">
                            <div class="flex items-center space-x-3 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-r from-green-500 to-red-500 flex-shrink-0 shadow-sm"></div>
                                <div>
                                    <div class="font-medium">العربية</div>
                                    <div class="text-xs text-gray-500">Arabic</div>
                                </div>
                            </div>
                            @if(app()->getLocale() === 'ar')
                                <i class="fas fa-check text-primary-600"></i>
                            @else
                                <i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }} text-xs text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            @endif
                        </a>

                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}"
                           class="flex items-center justify-between w-full px-4 py-3 text-sm rounded-lg mx-2 transition-all duration-200 group {{ app()->getLocale() === 'en' ? 'bg-primary-50 text-primary-700 border border-primary-200' : 'text-gray-700 hover:bg-gray-100' }}">
                            <div class="flex items-center space-x-3 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-r from-blue-500 to-red-500 flex-shrink-0 shadow-sm"></div>
                                <div>
                                    <div class="font-medium">English</div>
                                    <div class="text-xs text-gray-500">إنجليزي</div>
                                </div>
                            </div>
                            @if(app()->getLocale() === 'en')
                                <i class="fas fa-check text-primary-600"></i>
                            @else
                                <i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }} text-xs text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            @endif
                        </a>
                    </div>
                </div>
            </div>

            <!-- Search Button (Mobile) -->
            <button class="md:hidden p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-all duration-200 focus:outline-none">
                <i class="fas fa-search text-lg"></i>
            </button>

            <!-- Profile Dropdown -->
            <div class="dropdown-container relative">
                <button id="profileBtn" class="flex items-center space-x-2 {{ is_rtl() ? 'space-x-reverse' : '' }} p-1 rounded-lg hover:bg-gray-100 transition-all duration-200 focus:outline-none">
                    <div class="relative">
                        @if(Auth::guard('admin')->user()->getFirstMediaUrl('profile_image'))
                            <img src="{{ Auth::guard('admin')->user()->getFirstMediaUrl('profile_image') }}"
                                 alt="{{ trans('all.user_avatar') }}"
                                 class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-sm">
                        @else
                            <div class="w-9 h-9 rounded-full bg-primary-600 text-white flex items-center justify-center text-sm font-medium shadow-sm">
                                {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="hidden lg:block text-{{ is_rtl() ? 'right' : 'left' }}">
                        <div class="text-sm font-medium text-gray-900">{{ Auth::guard('admin')->user()->name }}</div>
                        <div class="text-xs text-gray-500">{{ trans('all.administrator') }}</div>
                    </div>
                    <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200"></i>
                </button>

                <div id="profileDropdown" class="dropdown-menu bg-white mt-2 rounded-xl shadow-xl border border-gray-100 w-72 {{ is_rtl() ? 'dropdown-rtl' : 'dropdown-ltr' }}">
                    <!-- Profile Header -->
                    <div class="px-4 py-4 border-b border-gray-100 bg-gradient-to-r from-primary-50 to-primary-100 rounded-t-xl">
                        <div class="flex items-center space-x-3 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                            @if(Auth::guard('admin')->user()->getFirstMediaUrl('profile_image'))
                                <img src="{{ Auth::guard('admin')->user()->getFirstMediaUrl('profile_image') }}"
                                     alt="{{ trans('all.user_avatar') }}"
                                     class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">
                            @else
                                <div class="w-12 h-12 rounded-full bg-primary-600 text-white flex items-center justify-center text-lg font-medium shadow-sm">
                                    {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 truncate">{{ Auth::guard('admin')->user()->name }}</h3>
                                <p class="text-sm text-gray-600 truncate">{{ Auth::guard('admin')->user()->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Items -->
                    <div class="py-2">
                        <a href="{{ route('admin.profile.edit') }}"
                           class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-all duration-200 rounded-lg mx-2 group">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center {{ is_rtl() ? 'ml-3' : 'mr-3' }} group-hover:bg-blue-200 transition-colors">
                                <i class="fas fa-user text-blue-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium">{{ trans('all.profile') }}</div>
                                <div class="text-xs text-gray-500">{{ trans('all.Manage Your Profile') }}</div>
                            </div>
                            <i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }} text-xs text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </a>

                        <!-- Divider -->
                        <div class="my-2 border-t border-gray-100 mx-2"></div>

                        <form action="{{ route('admin.logout') }}" method="POST" class="block">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-all duration-200 rounded-lg mx-2 group">
                                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center {{ is_rtl() ? 'ml-3' : 'mr-3' }} group-hover:bg-red-200 transition-colors">
                                    <i class="fas fa-sign-out-alt text-red-600 text-sm"></i>
                                </div>
                                <div class="flex-1 text-{{ is_rtl() ? 'right' : 'left' }}">
                                    <div class="font-medium">{{ trans('all.logout') }}</div>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
/* Basic dropdown positioning - Works with existing JavaScript */
.dropdown-container {
    position: relative;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    z-index: 50;
    transform: translateY(-10px) scale(0.95);
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* RTL positioning - dropdown goes to left */
.dropdown-rtl {
    left: 0;
    right: auto;
}

/* LTR positioning - dropdown goes to right */
.dropdown-ltr {
    right: 0;
    left: auto;
}

.dropdown-menu.show {
    transform: translateY(0) scale(1);
    opacity: 1;
    visibility: visible;
}

/* Enhanced hover effects */
.dropdown-menu a:hover,
.dropdown-menu button:hover {
    transform: translateX({{ is_rtl() ? '-2px' : '2px' }});
}

/* Animated chevrons */
.dropdown-container:hover .fa-chevron-down {
    transform: rotate(180deg);
}

/* Group hover effects */
.group:hover .opacity-0 {
    opacity: 1;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .dropdown-menu {
        width: calc(100vw - 2rem) !important;
        max-width: 320px;
        left: 50% !important;
        right: auto !important;
        transform: translateX(-50%) translateY(-10px) scale(0.95);
    }

    .dropdown-menu.show {
        transform: translateX(-50%) translateY(0) scale(1);
    }
}

/* Small mobile screens */
@media (max-width: 640px) {
    .dropdown-menu {
        width: calc(100vw - 1rem) !important;
        max-width: 300px;
    }
}

/* Prevent dropdowns from going off-screen on desktop */
@media (min-width: 769px) {
    /* Language dropdown positioning */
    .dropdown-container:nth-last-child(3) .dropdown-ltr {
        right: -2rem;
    }

    .dropdown-container:nth-last-child(3) .dropdown-rtl {
        left: -2rem;
    }

    /* Profile dropdown positioning - last dropdown */
    .dropdown-container:last-child .dropdown-ltr {
        right: 0;
    }

    .dropdown-container:last-child .dropdown-rtl {
        left: 0;
    }
}

/* Enhanced focus states */
.dropdown-container button:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
    border-radius: 0.5rem;
}

/* Smooth transitions */
.dropdown-menu a,
.dropdown-menu button,
.dropdown-menu .group > div {
    transition: all 0.2s ease;
}

/* Pulse animation for status indicators */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>

<header class="bg-white shadow-sm sticky top-0 z-10">
    <div class="px-4 flex items-center justify-between h-16">
        <!-- Left Side -->
        <div class="flex items-center">
            <button id="openSidebar" class="{{ is_rtl() ? 'mr-4' : 'ml-4' }} md:hidden p-2 rounded-full hover:bg-gray-100 text-gray-500 focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="text-xl font-bold text-gray-800">
                @yield('page-title', is_rtl() ? 'لوحة التحكم' : 'Dashboard')
            </h1>
        </div>

        <!-- Right Side -->
        <div class="flex items-center space-x-reverse space-x-3">
            <!-- Language Switcher -->
            <div class="dropdown-container relative">
                <button id="languageBtn" class="flex items-center space-x-2 {{ is_rtl() ? 'space-x-reverse' : '' }} p-2 rounded-full text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-all duration-200 focus:outline-none">
                    <i class="fas fa-globe text-lg"></i>
                    <span class="hidden sm:block text-sm font-medium">
                        {{ is_rtl() ? 'العربية' : 'English' }}
                    </span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>

                <div id="languageDropdown" class="language-dropdown dropdown-menu bg-white mt-2 rounded-lg shadow-lg border border-gray-100">
                    <div class="p-2">
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'ar']) }}"
                           class="flex items-center justify-between w-full px-3 py-2 text-sm rounded-md transition-all duration-200 {{ app()->getLocale() === 'ar' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                            <div class="flex items-center space-x-3 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                                <span class="font-medium">العربية</span>
                                <span class="text-xs text-gray-500">Arabic</span>
                            </div>
                            @if(app()->getLocale() === 'ar')
                                <i class="fas fa-check text-primary-600"></i>
                            @endif
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}"
                           class="flex items-center justify-between w-full px-3 py-2 text-sm rounded-md transition-all duration-200 {{ app()->getLocale() === 'en' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                            <div class="flex items-center space-x-3 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                                <span class="font-medium">English</span>
                                <span class="text-xs text-gray-500">إنجليزي</span>
                            </div>
                            @if(app()->getLocale() === 'en')
                                <i class="fas fa-check text-primary-600"></i>
                            @endif
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="dropdown-container relative">
                <button id="profileBtn" class="flex items-center justify-center w-9 h-9 rounded-full bg-primary-600 text-white text-sm hover:bg-primary-700 transition-all duration-200 focus:outline-none">
                    @if(auth()->guard('admin')->user()->getFirstMediaUrl('profile_image'))
                        <img src="{{ auth()->guard('admin')->user()->getFirstMediaUrl('profile_image') }}" alt="User Avatar" class="w-8 h-8 rounded-full">
                    @else
                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="text-indigo-800 font-medium text-lg">{{ strtoupper(substr(auth()->guard('admin')->user()->name, 0, 1)) }}</span>
                        </div>
                    @endif
                </button>
                <div id="profileDropdown" class="dropdown-menu bg-white mt-2 rounded-lg shadow-lg border border-gray-100">
                    <div class="p-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-900">{{ auth()->guard('admin')->user()->name }}</h3>
                        <p class="text-sm text-gray-500">{{ auth()->guard('admin')->user()->email }}</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.profile.edit') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-all duration-200">
                            <i class="fas fa-user-circle {{ is_rtl() ? 'ml-2' : 'mr-2' }} text-gray-400"></i>
                            {{ __('Profile') }}
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-all duration-200">
                            <i class="fas fa-cog {{ is_rtl() ? 'ml-2' : 'mr-2' }} text-gray-400"></i>
                            {{ __('Settings') }}
                        </a>
                        <form action="{{ route('admin.logout') }}" method="POST" class="block">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-all duration-200">
                                <i class="fas fa-sign-out-alt {{ is_rtl() ? 'ml-2' : 'mr-2' }} text-gray-400"></i>
                                {{ __('Logout') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

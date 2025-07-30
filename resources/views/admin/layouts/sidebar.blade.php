<div id="sidebar" class="sidebar-mobile md:relative w-72 bg-white shadow-lg flex-shrink-0 md:flex md:flex-col z-40 transition-all duration-300 ease-in-out h-full">
    <div class="flex items-center justify-between px-6 h-16 border-b border-gray-100">
        <div class="flex items-center">
            <img src="{{ asset(getMediaUrl('dashboard_logo')) }}" alt="Logo" class="w-8 h-8 rounded">
            <span class="text-xl font-bold text-gray-800 mr-3">{{ settings('site_name') }}</span>
        </div>
        <button id="closeSidebar" class="md:hidden p-2 rounded-full hover:bg-gray-100 text-gray-400 focus:outline-none">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto clean-scrollbar py-4">
        <div class="px-4">
            <!-- User Profile -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-primary-600 text-white flex items-center justify-center text-sm font-medium">
                        @if(Auth::guard('admin')->user()->getFirstMediaUrl('profile_image'))
                            <img src="{{ Auth::guard('admin')->user()->getFirstMediaUrl('profile_image') }}" alt="User Avatar" class="rounded-full">
                        @else
                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-800 font-medium text-lg">{{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="mr-3">
                        <p class="text-sm font-medium text-gray-900">{{ Auth::guard('admin')->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::guard('admin')->user()->email }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-1">
                <a href="{{ route('admin.dashboard')}}" class="flex items-center px-4 py-3 text-sm rounded-lg text-primary-700 bg-primary-50 font-medium hover:bg-primary-100 transition-all duration-200">
                    <i class="fas fa-home ml-3 w-5 text-center"></i>
                    <span>لوحة التحكم</span>
                </a>

                <div class="mb-1">
                    <button class="dropdown-toggle w-full flex items-center justify-between px-4 py-3 text-sm rounded-lg text-gray-700 hover:bg-gray-100 font-medium transition-all duration-200" data-target="shopDropdown">
                        <span class="flex items-center">
                            <i class="fas fa-users ml-3 w-5 text-center"></i>
                            <span>المشرفين</span>
                        </span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"></i>
                    </button>
                    <div id="shopDropdown" class="hidden pr-8 mt-1 space-y-1">
                        <a href="{{ route('admin.admins.index') }}" class="flex items-center px-4 py-2 text-sm rounded-lg text-gray-700 hover:bg-gray-100 transition-all duration-200">
                            <i class="fas fa-users ml-2 w-5 text-center text-gray-400"></i>
                            الجميع
                        </a>
                        <a href="{{ route('admin.admins.create') }}" class="flex items-center px-4 py-2 text-sm rounded-lg text-gray-700 hover:bg-gray-100 transition-all duration-200">
                            <i class="fa-regular fa-square-plus ml-2 w-5 text-center text-gray-400"></i>
                            إضافة
                        </a>
                    </div>
                </div>

                <div class="mb-1">
                    <button class="dropdown-toggle w-full flex items-center justify-between px-4 py-3 text-sm rounded-lg text-gray-700 hover:bg-gray-100 font-medium transition-all duration-200" data-target="blogDropdown">
                        <span class="flex items-center">
                            <i class="fa-solid fa-users-gear ml-3 w-5 text-center"></i>
                            <span>الأدوار</span>
                        </span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"></i>
                    </button>
                    <div id="blogDropdown" class="hidden pr-8 mt-1 space-y-1">
                        <a href="{{ route('admin.roles.index') }}" class="flex items-center px-4 py-2 text-sm rounded-lg text-gray-700 hover:bg-gray-100 transition-all duration-200">
                            <i class="fa-solid fa-users-gear ml-2 w-5 text-center text-gray-400"></i>
                            الجميع
                        </a>
                        <a href="{{ route('admin.roles.create') }}" class="flex items-center px-4 py-2 text-sm rounded-lg text-gray-700 hover:bg-gray-100 transition-all duration-200">
                            <i class="fa-regular fa-square-plus ml-2 w-5 text-center text-gray-400"></i>
                            إضافة
                        </a>
                    </div>
                </div>

                  <div class="mb-1">
                    <button class="dropdown-toggle w-full flex items-center justify-between px-4 py-3 text-sm rounded-lg text-gray-700 hover:bg-gray-100 font-medium transition-all duration-200" data-target="filesDropdown">
                        <span class="flex items-center">
                            <i class="fa-solid fa-users-gear ml-3 w-5 text-center"></i>
                            <span>الأرشيف</span>
                        </span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"></i>
                    </button>
                    <div id="filesDropdown" class="hidden pr-8 mt-1 space-y-1">
                        <a href="{{ route('admin.files.index') }}" class="flex items-center px-4 py-2 text-sm rounded-lg text-gray-700 hover:bg-gray-100 transition-all duration-200">
                            <i class="fa-solid fa-users-gear ml-2 w-5 text-center text-gray-400"></i>
                            الجميع
                        </a>
                    </div>
                </div>

                  <div class="mb-1">
                    <button class="dropdown-toggle w-full flex items-center justify-between px-4 py-3 text-sm rounded-lg text-gray-700 hover:bg-gray-100 font-medium transition-all duration-200" data-target="categoriesDropdown">
                        <span class="flex items-center">
                            <i class="fa-solid fa-users-gear ml-3 w-5 text-center"></i>
                            <span>الأقسام</span>
                        </span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"></i>
                    </button>
                    <div id="categoriesDropdown" class="hidden pr-8 mt-1 space-y-1">
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-2 text-sm rounded-lg text-gray-700 hover:bg-gray-100 transition-all duration-200">
                            <i class="fa-solid fa-users-gear ml-2 w-5 text-center text-gray-400"></i>
                            الجميع
                        </a>
                    </div>
                </div>

                 <div class="mb-1">
                    <button class="dropdown-toggle w-full flex items-center justify-between px-4 py-3 text-sm rounded-lg text-gray-700 hover:bg-gray-100 font-medium transition-all duration-200" data-target="settingDropdown">
                        <span class="flex items-center">
                            <i class="fas fa-cog ml-3 w-5 text-center"></i>
                            <span>الاعدادات</span>
                        </span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"></i>
                    </button>
                    <div id="settingDropdown" class="hidden pr-8 mt-1 space-y-1">
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-2 text-sm rounded-lg text-gray-700 hover:bg-gray-100 transition-all duration-200">
                            <i class="fas fa-cog ml-2 w-5 text-center text-gray-400"></i>
                            الاعدادات
                        </a>
                        {{-- <a href="{{ route('admin.media.index') }}" class="flex items-center px-4 py-2 text-sm rounded-lg text-gray-700 hover:bg-gray-100 transition-all duration-200">
                            <i class="fa-regular fa-square-plus ml-2 w-5 text-center text-gray-400"></i>
                            مدير الملفات
                        </a> --}}
                    </div>
                </div>

                {{-- <a href="{{ route('admin.settings.index')}}" class="flex items-center px-4 py-3 text-sm rounded-lg text-gray-700 hover:bg-gray-100 font-medium transition-all duration-200">
                    <i class="fas fa-cog ml-3 w-5 text-center"></i>
                    <span>الإعدادات</span>
                </a> --}}
            </div>
        </div>
    </div>

   <!-- Sidebar Footer with Enhanced Design -->
    <div class="p-5 border-t border-gray-200 bg-gray-50 rounded-b-lg shadow-inner">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2 space-x-reverse">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-xs font-medium text-gray-600">v {{ settings('panel_version', 1) }}</span>
            </div>

            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="flex items-center px-3 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700 transition-colors duration-200 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V7.414l-5-5H3zm6.293 5.293a1 1 0 011.414 0L12 9.586V5a1 1 0 012 0v4.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                تسجيل الخروج
            </a>

            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>

        <div class="mt-4 text-center">
            <p class="text-xs text-gray-500">{{ now()->format('Y') }} © جميع الحقوق محفوظة</p>
        </div>
    </div>
</div>

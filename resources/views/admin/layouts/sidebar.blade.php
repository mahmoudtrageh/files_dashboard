{{-- resources/views/admin/layouts/sidebar.blade.php --}}
<div id="sidebar" class="sidebar-mobile md:relative w-72 bg-white shadow-xl flex-shrink-0 md:flex md:flex-col z-40 transition-all duration-300 ease-in-out h-full">
    {{-- Header Section --}}
    <div class="flex items-center justify-between px-6 h-16 border-b border-gray-100 bg-gradient-to-r from-primary-50 to-primary-100">
        <div class="flex items-center {{ is_rtl() ? 'space-x-reverse' : '' }} space-x-3">
            <div class="w-10 h-10 rounded-xl bg-primary-600 flex items-center justify-center shadow-lg">
                @if(settings('dashboard_logo'))
                    <img src="{{ asset(getMediaUrl('dashboard_logo')) }}" alt="{{ trans('all.logo') }}" class="w-8 h-8 rounded-lg">
                @else
                    <i class="fas fa-cube text-white text-lg"></i>
                @endif
            </div>
            <div>
                <span class="text-xl font-bold text-gray-800">{{ settings('site_name', 'AdminPanel') }}</span>
            </div>
        </div>
        <button id="closeSidebar" class="md:hidden p-2 rounded-full hover:bg-white/50 text-gray-400 focus:outline-none transition-colors duration-200">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- Scrollable Content --}}
    <div class="flex-1 overflow-y-auto clean-scrollbar py-6">
        <div class="px-4 space-y-2">
            {{-- User Profile Card --}}
            <div class="mb-8 p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200">
                <div class="flex items-center {{ is_rtl() ? 'space-x-reverse' : '' }} space-x-3">
                    <div class="relative">
                        @if(Auth::guard('admin')->user()->getFirstMediaUrl('profile_image'))
                            <img src="{{ Auth::guard('admin')->user()->getFirstMediaUrl('profile_image') }}"
                                 alt="{{ trans('all.user_avatar') }}"
                                 class="w-12 h-12 rounded-full object-cover shadow-lg">
                        @else
                            <div class="w-12 h-12 rounded-full bg-primary-600 text-white flex items-center justify-center text-sm font-medium shadow-lg">
                                {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::guard('admin')->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::guard('admin')->user()->email }}</p>
                    </div>
                </div>
            </div>

            {{-- Navigation Menu --}}
            <nav class="space-y-1">
                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center px-4 py-3 text-sm rounded-xl font-medium transition-all duration-200">
                    <i class="fas fa-home menu-icon w-5 text-center {{ is_rtl() ? 'ml-3' : 'mr-3' }}"></i>
                    <span>{{ trans('all.dashboard') }}</span>
                </a>

                {{-- Administrators Dropdown --}}
                <div class="space-y-1">
                    <button class="dropdown-toggle menu-item w-full flex items-center justify-between px-4 py-3 text-sm rounded-xl text-gray-700 hover:bg-gray-100 font-medium transition-all duration-200"
                            data-target="adminsDropdown">
                        <span class="flex items-center">
                            <i class="fas fa-users menu-icon w-5 text-center {{ is_rtl() ? 'ml-3' : 'mr-3' }}"></i>
                            <span>{{ trans('all.administrators') }}</span>
                        </span>
                        <i class="fas fa-chevron-down dropdown-toggle-icon text-xs transition-transform duration-200"></i>
                    </button>
                    <div id="adminsDropdown" class="dropdown-content {{ is_rtl() ? 'pr-6' : 'pl-6' }} space-y-1">
                        <a href="{{ route('admin.admins.index') }}"
                           class="submenu-item flex items-center px-4 py-2.5 text-sm rounded-lg text-gray-600 hover:text-gray-900 transition-all duration-200">
                            <i class="fas fa-users submenu-icon w-4 text-center text-gray-400 {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                            <span>{{ trans('all.all') }}</span>
                        </a>
                    </div>
                </div>

                {{-- Roles Dropdown --}}
                <div class="space-y-1">
                    <button class="dropdown-toggle menu-item w-full flex items-center justify-between px-4 py-3 text-sm rounded-xl text-gray-700 hover:bg-gray-100 font-medium transition-all duration-200"
                            data-target="rolesDropdown">
                        <span class="flex items-center">
                            <i class="fa-solid fa-users-gear menu-icon w-5 text-center {{ is_rtl() ? 'ml-3' : 'mr-3' }}"></i>
                            <span>{{ trans('all.roles') }}</span>
                        </span>
                        <i class="fas fa-chevron-down dropdown-toggle-icon text-xs transition-transform duration-200"></i>
                    </button>
                    <div id="rolesDropdown" class="dropdown-content {{ is_rtl() ? 'pr-6' : 'pl-6' }} space-y-1">
                        <a href="{{ route('admin.roles.index') }}"
                           class="submenu-item flex items-center px-4 py-2.5 text-sm rounded-lg text-gray-600 hover:text-gray-900 transition-all duration-200">
                            <i class="fa-solid fa-users-gear submenu-icon w-4 text-center text-gray-400 {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                            <span>{{ trans('all.all') }}</span>
                        </a>
                    </div>
                </div>

                {{-- Categories Dropdown --}}
                <div class="space-y-1">
                    <button class="dropdown-toggle menu-item w-full flex items-center justify-between px-4 py-3 text-sm rounded-xl text-gray-700 hover:bg-gray-100 font-medium transition-all duration-200"
                            data-target="categoriesDropdown">
                        <span class="flex items-center">
                            <i class="fa-solid fa-tags menu-icon w-5 text-center {{ is_rtl() ? 'ml-3' : 'mr-3' }}"></i>
                            <span>{{ trans('all.categories') }}</span>
                        </span>
                        <i class="fas fa-chevron-down dropdown-toggle-icon text-xs transition-transform duration-200"></i>
                    </button>
                    <div id="categoriesDropdown" class="dropdown-content {{ is_rtl() ? 'pr-6' : 'pl-6' }} space-y-1">
                        <a href="{{ route('admin.categories.index') }}"
                           class="submenu-item flex items-center px-4 py-2.5 text-sm rounded-lg text-gray-600 hover:text-gray-900 transition-all duration-200">
                            <i class="fa-solid fa-tags submenu-icon w-4 text-center text-gray-400 {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                            <span>{{ trans('all.all_categories') }}</span>
                        </a>

                    </div>
                </div>

                 <div class="space-y-1">
                    <button class="dropdown-toggle menu-item w-full flex items-center justify-between px-4 py-3 text-sm rounded-xl text-gray-700 hover:bg-gray-100 font-medium transition-all duration-200"
                            data-target="archivesDropdown">
                        <span class="flex items-center">
                            <i class="fa-solid fa-tags menu-icon w-5 text-center {{ is_rtl() ? 'ml-3' : 'mr-3' }}"></i>
                            <span>{{ trans('all.archives') }}</span>
                        </span>
                        <i class="fas fa-chevron-down dropdown-toggle-icon text-xs transition-transform duration-200"></i>
                    </button>
                    <div id="archivesDropdown" class="dropdown-content {{ is_rtl() ? 'pr-6' : 'pl-6' }} space-y-1">
                        <a href="{{ route('admin.archives.index') }}"
                           class="submenu-item flex items-center px-4 py-2.5 text-sm rounded-lg text-gray-600 hover:text-gray-900 transition-all duration-200">
                            <i class="fa-solid fa-tags submenu-icon w-4 text-center text-gray-400 {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                            <span>{{ trans('all.all_archives') }}</span>
                        </a>

                    </div>
                </div>

                {{-- Settings Dropdown --}}
                <div class="space-y-1">
                    <button class="dropdown-toggle menu-item w-full flex items-center justify-between px-4 py-3 text-sm rounded-xl text-gray-700 hover:bg-gray-100 font-medium transition-all duration-200"
                            data-target="settingsDropdown">
                        <span class="flex items-center">
                            <i class="fas fa-cog menu-icon w-5 text-center {{ is_rtl() ? 'ml-3' : 'mr-3' }}"></i>
                            <span>{{ trans('all.settings') }}</span>
                        </span>
                        <i class="fas fa-chevron-down dropdown-toggle-icon text-xs transition-transform duration-200"></i>
                    </button>
                    <div id="settingsDropdown" class="dropdown-content {{ is_rtl() ? 'pr-6' : 'pl-6' }} space-y-1">
                        <a href="{{ route('admin.settings.index') }}"
                           class="submenu-item flex items-center px-4 py-2.5 text-sm rounded-lg text-gray-600 hover:text-gray-900 transition-all duration-200">
                            <i class="fas fa-cog submenu-icon w-4 text-center text-gray-400 {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                            <span>{{ trans('all.general_settings') }}</span>
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    {{-- Enhanced Footer --}}
    <div class="sidebar-footer p-5 bg-gradient-to-br from-gray-50 to-gray-100 border-t border-gray-200 rounded-b-xl shadow-inner">
        <div class="mb-3">
            <div class="text-center">
                <p class="text-xs text-gray-500">{{ now()->format('Y') }} © {{ trans('all.all_rights_reserved') }}</p>
            </div>
        </div>

        <div>
            <a href="#"
            onclick="event.preventDefault(); confirmLogout();"
            class="w-full inline-block text-center px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-primary-600 to-primary-700 rounded-lg hover:from-primary-700 hover:to-primary-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4 {{ is_rtl() ? 'ml-1.5' : 'mr-1.5' }}" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                </svg>
                <span>{{ trans('all.logout') }}</span>
            </a>

            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </div>
</div>

<style>
/* Enhanced sidebar styles */
.menu-item {
    transition: all 0.2s ease;
    position: relative;
}

.menu-item:hover {
    transform: translateX({{ is_rtl() ? '-2px' : '2px' }});
    background-color: #f1f5f9;
}

.menu-item.active {
    background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
}

.menu-item.active:hover {
    transform: translateX(0);
    background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
}

.submenu-item {
    transition: all 0.2s ease;
    position: relative;
}

.submenu-item:hover {
    background-color: #e2e8f0;
    {{ is_rtl() ? 'padding-right: 20px;' : 'padding-left: 20px;' }}
}

.dropdown-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out, opacity 0.2s ease-out;
    opacity: 0;
}

.dropdown-content.show {
    max-height: 400px;
    opacity: 1;
}

.dropdown-toggle-icon {
    transition: transform 0.3s ease;
}

.dropdown-toggle-icon.rotated {
    transform: rotate(180deg);
}

.status-indicator {
    animation: pulse-soft 2s infinite;
}

@keyframes pulse-soft {
    0%, 100% { opacity: 0.75; }
    50% { opacity: 1; }
}

/* Mobile sidebar improvements */
@media (max-width: 767px) {
    .sidebar-mobile {
        position: fixed;
        {{ is_rtl() ? 'right: -100%;' : 'left: -100%;' }}
        top: 0;
        bottom: 0;
        height: 100vh;
        transition: {{ is_rtl() ? 'right' : 'left' }} 0.3s ease;
        z-index: 50;
    }

    .sidebar-mobile.open {
        {{ is_rtl() ? 'right: 0;' : 'left: 0;' }}
    }
}

/* Enhanced clean scrollbar */
.clean-scrollbar::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

.clean-scrollbar::-webkit-scrollbar-track {
    background: #f8fafc;
    border-radius: 10px;
}

.clean-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
    transition: background 0.2s ease;
}

.clean-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Enhanced sidebar footer styles */
.sidebar-footer {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-top: 1px solid #e5e7eb;
    border-radius: 0 0 1rem 1rem;
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1);
}

/* Animated status indicator */
.sidebar-footer .animate-ping {
    animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;
}

@keyframes ping {
    75%, 100% {
        transform: scale(2);
        opacity: 0;
    }
}

/* Enhanced button hover effects */
.sidebar-footer a:hover {
    transform: translateY(-1px) scale(1.05);
}

.sidebar-footer a:active {
    transform: translateY(0) scale(0.95);
}

/* Smooth transitions */
.sidebar-footer a {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Version badge enhancement */
.sidebar-footer .bg-gray-200 {
    background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
    border: 1px solid #d1d5db;
}

/* Status indicator glow effect */
.sidebar-footer .bg-green-500 {
    box-shadow: 0 0 8px rgba(34, 197, 94, 0.4);
}
</style>

<script>
// Enhanced logout confirmation
function confirmLogout() {
    const isRTL = document.dir === 'rtl' || document.documentElement.dir === 'rtl';
    const message = isRTL ?
        'هل أنت متأكد من تسجيل الخروج؟' :
        'Are you sure you want to logout?';

    if (confirm(message)) {
        // Add loading state
        const logoutBtn = event.target.closest('a');
        const originalContent = logoutBtn.innerHTML;

        logoutBtn.innerHTML = `
            <i class="fas fa-spinner fa-spin inline ${isRTL ? 'ml-2' : 'mr-2'}"></i>
            <span>${isRTL ? 'جاري تسجيل الخروج...' : 'Signing out...'}</span>
        `;
        logoutBtn.classList.add('opacity-75', 'cursor-not-allowed', 'pointer-events-none');

        // Submit form after short delay for visual feedback
        setTimeout(() => {
            document.getElementById('logout-form').submit();
        }, 500);
    }
}
</script>

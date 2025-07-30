<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', is_rtl() ? 'لوحة التحكم' : 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Fonts -->
    @if(is_rtl())
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @else
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @endif

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        },
                        gray: {
                            50: '#f9fafb',
                            100: '#f3f4f6',
                            200: '#e5e7eb',
                            300: '#d1d5db',
                            400: '#9ca3af',
                            500: '#6b7280',
                            600: '#4b5563',
                            700: '#374151',
                            800: '#1f2937',
                            900: '#111827',
                        }
                    },
                    fontFamily: {
                        'sans': {{ is_rtl() ? "['Tajawal', 'ui-sans-serif', 'system-ui']" : "['Inter', 'ui-sans-serif', 'system-ui']" }},
                    },
                    boxShadow: {
                        'soft': '0 2px 15px 0 rgba(0, 0, 0, 0.1)',
                        'card': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
                        'card-hover': '0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                    },
                    spacing: {
                        '18': '4.5rem',
                        '88': '22rem',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: {{ is_rtl() ? "'Tajawal', ui-sans-serif, system-ui" : "'Inter', ui-sans-serif, system-ui" }};
        }

        /* RTL Support - Enhanced */
        @if(is_rtl())
        .space-x-reverse > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 1;
        }

        .divide-x-reverse > :not([hidden]) ~ :not([hidden]) {
            --tw-divide-x-reverse: 1;
        }

        /* RTL Specific Spacing */
        .rtl-pr-4 { padding-right: 1rem; }
        .rtl-pl-4 { padding-left: 1rem; }
        .rtl-mr-4 { margin-right: 1rem; }
        .rtl-ml-4 { margin-left: 1rem; }
        .rtl-pr-6 { padding-right: 1.5rem; }
        .rtl-pl-6 { padding-left: 1.5rem; }

        /* RTL Text Alignment */
        .rtl-text-right { text-align: right; }
        .rtl-text-left { text-align: left; }
        @else
        /* LTR Specific Spacing */
        .ltr-pl-4 { padding-left: 1rem; }
        .ltr-pr-4 { padding-right: 1rem; }
        .ltr-ml-4 { margin-left: 1rem; }
        .ltr-mr-4 { margin-right: 1rem; }
        .ltr-pl-6 { padding-left: 1.5rem; }
        .ltr-pr-6 { padding-right: 1.5rem; }

        /* LTR Text Alignment */
        .ltr-text-left { text-align: left; }
        .ltr-text-right { text-align: right; }
        @endif

        /* Enhanced Dropdown Styles */
        .dropdown-container {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            {{ is_rtl() ? 'left: 0;' : 'right: 0;' }}
            min-width: 200px;
            z-index: 50;
            transform: translateY(10px) scale(0.95);
            visibility: hidden;
            opacity: 0;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dropdown-menu.show {
            transform: translateY(0) scale(1);
            visibility: visible;
            opacity: 1;
        }

        /* Language Dropdown */
        .language-dropdown {
            {{ is_rtl() ? 'left: 0;' : 'right: 0;' }}
            width: 240px;
        }

        /* Enhanced Scrollbar */
        .clean-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .clean-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 6px;
        }

        .clean-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 6px;
            transition: background 0.2s ease;
        }

        .clean-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Enhanced Mobile Sidebar */
        @media (max-width: 767px) {
            .sidebar-mobile {
                position: fixed;
                {{ is_rtl() ? 'right: -100%;' : 'left: -100%;' }}
                top: 0;
                bottom: 0;
                height: 100vh;
                width: 280px;
                transition: {{ is_rtl() ? 'right' : 'left' }} 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 40;
                box-shadow: {{ is_rtl() ? '-10px 0 25px' : '10px 0 25px' }} rgba(0, 0, 0, 0.1);
            }

            .sidebar-mobile.open {
                {{ is_rtl() ? 'right: 0;' : 'left: 0;' }}
            }

            /* Mobile Header Adjustments */
            .mobile-header-padding {
                {{ is_rtl() ? 'padding-right: 1rem; padding-left: 0.5rem;' : 'padding-left: 1rem; padding-right: 0.5rem;' }}
            }
        }

        /* Enhanced Focus Styles */
        .focus-ring:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.3);
            border-color: rgba(249, 115, 22, 0.5);
        }

        /* Enhanced Animations */
        .fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }

        .slide-in {
            animation: slideIn 0.3s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                transform: translateX({{ is_rtl() ? '20px' : '-20px' }});
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Enhanced Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1;
        }

        .status-active {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-inactive {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #d97706;
        }

        .status-processing {
            background-color: #dbeafe;
            color: #2563eb;
        }

        /* Enhanced Button Styles */
        .btn-primary {
            background-color: #ea580c;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #c2410c;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Enhanced Card Styles */
        .card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        /* Language Switcher Enhancement */
        .language-button {
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .language-button:hover {
            background-color: #f3f4f6;
            border-color: #d1d5db;
        }

        .language-button.active {
            background-color: #fff7ed;
            border-color: #ea580c;
            color: #ea580c;
        }

        /* Notification Enhancements */
        .notification-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        /* Print Styles */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
            }
            .sidebar-mobile {
                display: none !important;
            }
        }

        /* Dark mode support (optional) */
        @media (prefers-color-scheme: dark) {
            .dark-mode-auto {
                background-color: #1f2937;
                color: #f9fafb;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50 fade-in">
    <!-- Page Loading Indicator -->
    <div id="page-loader" class="fixed inset-0 bg-white z-50 flex items-center justify-center hidden">
        <div class="flex flex-col items-center">
            <div class="animate-spin rounded-full h-8 w-8 border-4 border-primary-200 border-t-primary-600"></div>
            <p class="mt-2 text-sm text-gray-600">{{ __('Loading...') }}</p>
        </div>
    </div>

    <div class="flex h-screen overflow-hidden">
        <!-- Mobile Sidebar Overlay -->
        <div id="sidebarOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-30 transition-opacity duration-300"></div>

        <!-- Sidebar -->
        @include('admin.layouts.sidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Header -->
            @include('admin.layouts.header')

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto clean-scrollbar bg-gray-50">
                <!-- Content Wrapper with Enhanced Padding -->
                <div class="{{ is_rtl() ? 'pr-4 pl-4 md:pr-6 md:pl-6' : 'pl-4 pr-4 md:pl-6 md:pr-6' }} py-4 md:py-6">
                    <!-- Alerts -->
                    @include('admin.components.alerts')

                    <!-- Page Content -->
                    <div class="slide-in">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Enhanced JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeAdminPanel();
        });

        function initializeAdminPanel() {
            // Initialize components
            initMobileSidebar();
            initDropdowns();
            initNotifications();
            initPageLoader();
            initLanguageSwitcher();

            // Set CSRF token for AJAX requests
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                window.Laravel = { csrfToken: token.getAttribute('content') };
            }
        }

        function initMobileSidebar() {
            const openBtn = document.getElementById('openSidebar');
            const closeBtn = document.getElementById('closeSidebar');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (openBtn && sidebar && overlay) {
                openBtn.addEventListener('click', openSidebar);
                closeBtn?.addEventListener('click', closeSidebar);
                overlay.addEventListener('click', closeSidebar);

                // Close on escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && sidebar.classList.contains('open')) {
                        closeSidebar();
                    }
                });
            }

            function openSidebar() {
                sidebar.classList.add('open');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');

                // Add animation class
                setTimeout(() => {
                    sidebar.style.transform = 'translateX(0)';
                }, 10);
            }

            function closeSidebar() {
                sidebar.classList.remove('open');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        function initDropdowns() {
            // Sidebar dropdown menus
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-target');
                    const target = document.getElementById(targetId);
                    const icon = this.querySelector('.fa-chevron-down, .fa-chevron-up');

                    // Close other sidebar dropdowns
                    document.querySelectorAll('[id$="Dropdown"]:not(.dropdown-menu)').forEach(menu => {
                        if (menu.id !== targetId && !menu.classList.contains('hidden')) {
                            menu.classList.add('hidden');
                            const otherIcon = document.querySelector(`[data-target="${menu.id}"] .fa-chevron-down, [data-target="${menu.id}"] .fa-chevron-up`);
                            if (otherIcon) {
                                otherIcon.className = otherIcon.className.replace('fa-chevron-up', 'fa-chevron-down');
                            }
                        }
                    });

                    // Toggle current dropdown with animation
                    if (target) {
                        if (target.classList.contains('hidden')) {
                            target.classList.remove('hidden');
                            setTimeout(() => target.style.opacity = '1', 10);
                        } else {
                            target.style.opacity = '0';
                            setTimeout(() => target.classList.add('hidden'), 150);
                        }

                        if (icon) {
                            icon.classList.toggle('fa-chevron-down');
                            icon.classList.toggle('fa-chevron-up');
                        }
                    }
                });
            });

            // Header dropdowns
            setupHeaderDropdown('notificationBtn', 'notificationDropdown');
            setupHeaderDropdown('profileBtn', 'profileDropdown');
            setupHeaderDropdown('languageBtn', 'languageDropdown');

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown-container')) {
                    document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                        menu.classList.remove('show');
                    });
                }
            });
        }

        function setupHeaderDropdown(buttonId, dropdownId) {
            const button = document.getElementById(buttonId);
            const dropdown = document.getElementById(dropdownId);

            if (!button || !dropdown) return;

            button.addEventListener('click', function(e) {
                e.stopPropagation();

                // Close other header dropdowns
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    if (menu.id !== dropdownId) {
                        menu.classList.remove('show');
                    }
                });

                // Toggle current dropdown with enhanced animation
                dropdown.classList.toggle('show');
            });

            // Prevent dropdown from closing when clicking inside
            dropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        function initNotifications() {
            // Enhanced notification function
            window.showNotification = function(message, type = 'success', duration = 5000) {
                // Remove existing notifications
                document.querySelectorAll('.notification-toast').forEach(toast => toast.remove());

                // Create notification with enhanced styling
                const toast = document.createElement('div');
                toast.className = `notification-toast fixed top-4 {{ is_rtl() ? 'left-4' : 'right-4' }} max-w-sm bg-white rounded-lg shadow-lg border-l-4 p-4 z-50 transform transition-all duration-300 translate-x-full`;

                const colors = {
                    success: 'border-green-500',
                    error: 'border-red-500',
                    warning: 'border-yellow-500',
                    info: 'border-blue-500'
                };

                const icons = {
                    success: 'fas fa-check-circle text-green-500',
                    error: 'fas fa-exclamation-circle text-red-500',
                    warning: 'fas fa-exclamation-triangle text-yellow-500',
                    info: 'fas fa-info-circle text-blue-500'
                };

                toast.classList.add(colors[type] || colors.info);

                toast.innerHTML = `
                    <div class="flex items-center">
                        <i class="${icons[type] || icons.info} {{ is_rtl() ? 'ml-3' : 'mr-3' }}"></i>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">${message}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="{{ is_rtl() ? 'mr-3' : 'ml-3' }} text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;

                document.body.appendChild(toast);

                // Show toast with animation
                setTimeout(() => toast.classList.remove('translate-x-full'), 100);

                // Auto remove
                if (duration > 0) {
                    setTimeout(() => {
                        toast.classList.add('translate-x-full');
                        setTimeout(() => toast.remove(), 300);
                    }, duration);
                }
            };
        }

        function initPageLoader() {
            window.showPageLoader = function() {
                document.getElementById('page-loader').classList.remove('hidden');
            };

            window.hidePageLoader = function() {
                document.getElementById('page-loader').classList.add('hidden');
            };

            // Hide loader when page is loaded
            window.addEventListener('load', hidePageLoader);
        }

        function initLanguageSwitcher() {
            // Enhanced language switching with page reload
            const languageLinks = document.querySelectorAll('#languageDropdown a');
            languageLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    showPageLoader();
                    // Allow the default action (navigation) to proceed
                });
            });
        }

        // Enhanced Utility Functions
        function confirmDelete(event, message = '{{ __("Are you sure you want to delete this item?") }}') {
            if (!confirm(message)) {
                event.preventDefault();
                return false;
            }
            return true;
        }

        function previewImage(input, previewId) {
            const previewElement = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewElement.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                showNotification('{{ __("Copied to clipboard") }}', 'success', 2000);
            }).catch(() => {
                showNotification('{{ __("Failed to copy") }}', 'error', 2000);
            });
        }

        // Livewire Integration
        document.addEventListener('livewire:init', function() {
            Livewire.on('notification', function(data) {
                showNotification(data.message, data.type || 'success');
            });

            Livewire.on('redirect', function(url) {
                showPageLoader();
                window.location.href = url;
            });
        });
    </script>

    @stack('scripts')
    @yield('scripts')
</body>
</html>

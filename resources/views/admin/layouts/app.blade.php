{{-- resources/views/admin/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', trans('all.dashboard')) - {{ settings('site_name', 'AdminPanel') }}</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    {{-- Fonts --}}
    @if(is_rtl())
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @else
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @endif

    {{-- Tailwind Configuration --}}
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
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                        'tajawal': ['Tajawal', 'sans-serif'],
                    },
                    boxShadow: {
                        'card': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
                        'card-hover': '0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                        'soft': '0 2px 15px 0 rgba(0, 0, 0, 0.1)',
                        'soft-xl': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-in-out forwards',
                        'slide-in': 'slideIn 0.3s ease-out forwards',
                        'pulse-soft': 'pulseSoft 2s infinite',
                    }
                }
            }
        }
    </script>

    {{-- Enhanced Styles --}}
    <style>
        /* Dynamic font family based on direction */
        [dir="rtl"] {
            font-family: 'Tajawal', sans-serif;
        }

        [dir="ltr"] {
            font-family: 'Inter', sans-serif;
        }

        /* Utility for tracking body scroll state */
        body.overflow-hidden {
            overflow: hidden;
        }

        /* Enhanced status badges */
        .status-badge {
            @apply inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full;
            transition: all 0.2s ease;
        }

        .status-badge:hover {
            transform: translateY(-1px);
        }

        .status-active {
            @apply bg-green-100 text-green-800 border border-green-200;
        }

        .status-inactive {
            @apply bg-red-100 text-red-800 border border-red-200;
        }

        .status-new {
            @apply bg-blue-100 text-blue-800 border border-blue-200;
        }

        .status-processing {
            @apply bg-yellow-100 text-yellow-800 border border-yellow-200;
        }

        .status-shipped {
            @apply bg-indigo-100 text-indigo-800 border border-indigo-200;
        }

        .status-delivered {
            @apply bg-green-100 text-green-800 border border-green-200;
        }

        .status-cancelled {
            @apply bg-red-100 text-red-800 border border-red-200;
        }

        .status-refunded {
            @apply bg-orange-100 text-orange-800 border border-orange-200;
        }

        /* Enhanced clean scrollbar */
        .clean-scrollbar::-webkit-scrollbar {
            height: 6px;
            width: 6px;
        }

        .clean-scrollbar::-webkit-scrollbar-track {
            background: #f8fafc;
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

        /* Enhanced dropdown menu positioning */
        .dropdown-container {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            {{ is_rtl() ? 'right: 0;' : 'left: 0;' }}
            width: 280px;
            z-index: 50;
            transform: translateY(10px);
            visibility: hidden;
            opacity: 0;
            transition: all 0.25s ease;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
        }

        .dropdown-menu.show {
            transform: translateY(0);
            visibility: visible;
            opacity: 1;
        }

        /* Enhanced responsive sidebar */
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

            .sidebar-overlay {
                position: fixed;
                inset: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 40;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }

            .sidebar-overlay.show {
                opacity: 1;
                visibility: visible;
            }
        }

        /* Enhanced focus styles */
        .focus-ring:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.4);
            border-color: #f97316;
        }

        /* Enhanced animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX({{ is_rtl() ? '30px' : '-30px' }}); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes pulseSoft {
            0%, 100% { opacity: 0.75; }
            50% { opacity: 1; }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out forwards;
        }

        .slide-in {
            animation: slideIn 0.3s ease-out forwards;
        }

        /* Enhanced card styles */
        .card {
            @apply bg-white rounded-xl shadow-card border border-gray-100;
            transition: all 0.2s ease;
        }

        .card:hover {
            @apply shadow-card-hover;
            transform: translateY(-2px);
        }

        .card-header {
            @apply px-6 py-4 border-b border-gray-100 bg-gray-50 rounded-t-xl;
        }

        .card-body {
            @apply p-6;
        }

        .card-footer {
            @apply px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-xl;
        }

        /* Enhanced button styles */
        .btn {
            @apply inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2;
        }

        .btn:disabled {
            @apply opacity-50 cursor-not-allowed;
        }

        .btn-primary {
            @apply bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500;
            box-shadow: 0 4px 14px 0 rgba(249, 115, 22, 0.39);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px 0 rgba(249, 115, 22, 0.39);
        }

        .btn-secondary {
            @apply bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-gray-500;
        }

        .btn-success {
            @apply bg-green-600 text-white hover:bg-green-700 focus:ring-green-500;
        }

        .btn-danger {
            @apply bg-red-600 text-white hover:bg-red-700 focus:ring-red-500;
        }

        .btn-warning {
            @apply bg-yellow-600 text-white hover:bg-yellow-700 focus:ring-yellow-500;
        }

        .btn-info {
            @apply bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500;
        }

        /* Enhanced form styles */
        .form-input {
            @apply block w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200;
        }

        .form-input:focus {
            @apply bg-white;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        }

        .form-label {
            @apply block text-sm font-semibold text-gray-700 mb-2;
        }

        .form-error {
            @apply text-red-600 text-sm mt-1;
        }

        .form-help {
            @apply text-gray-500 text-sm mt-1;
        }

        /* Enhanced table styles */
        .table {
            @apply w-full text-sm text-left text-gray-500;
        }

        .table thead {
            @apply text-xs text-gray-700 uppercase bg-gray-50;
        }

        .table th {
            @apply px-6 py-4 font-semibold;
        }

        .table td {
            @apply px-6 py-4 whitespace-nowrap;
        }

        .table tbody tr {
            @apply bg-white border-b hover:bg-gray-50 transition-colors duration-200;
        }

        /* Loading spinner */
        .spinner {
            @apply inline-block w-4 h-4 border-2 border-gray-300 border-t-primary-600 rounded-full;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Toast notifications */
        .toast {
            @apply fixed top-4 right-4 bg-white border border-gray-200 rounded-lg shadow-soft-xl p-4 z-50;
            min-width: 300px;
            animation: slideIn 0.3s ease-out forwards;
        }

        .toast.success {
            @apply border-green-200 bg-green-50;
        }

        .toast.error {
            @apply border-red-200 bg-red-50;
        }

        .toast.warning {
            @apply border-yellow-200 bg-yellow-50;
        }

        .toast.info {
            @apply border-blue-200 bg-blue-50;
        }

        /* Enhanced mobile responsiveness */
        @media (max-width: 640px) {
            .card {
                @apply rounded-lg;
                margin: 0 -1rem;
            }

            .card-header,
            .card-body,
            .card-footer {
                @apply px-4;
            }

            .btn {
                @apply w-full justify-center;
            }

            .table-responsive {
                @apply overflow-x-auto;
            }

            .dropdown-menu {
                width: calc(100vw - 2rem);
                {{ is_rtl() ? 'right: 1rem;' : 'left: 1rem;' }}
            }
        }

        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }

            .sidebar-mobile {
                display: none !important;
            }

            .card {
                @apply shadow-none border;
            }
        }
    </style>

    {{-- Additional head content --}}
    @stack('styles')
</head>
<body class="bg-gray-50 fade-in {{ is_rtl() ? 'font-tajawal' : 'font-inter' }}">
    {{-- Loading Screen --}}
    <div id="loadingScreen" class="fixed inset-0 bg-white z-50 flex items-center justify-center">
        <div class="text-center">
            <div class="spinner-large mb-4"></div>
            <p class="text-gray-600">{{ trans('all.loading') }}</p>
        </div>
    </div>

    <div class="flex h-screen overflow-hidden">
        {{-- Mobile Sidebar Overlay --}}
        <div id="sidebarOverlay" class="sidebar-overlay no-print"></div>

        {{-- Sidebar --}}
        @include('admin.layouts.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            {{-- Top Navbar --}}
            @include('admin.layouts.header')

            {{-- Page Content --}}
            <main class="flex-1 p-6 overflow-y-auto clean-scrollbar bg-gray-50">
                {{-- Breadcrumbs --}}
                @if(!empty($breadcrumbs) && is_array($breadcrumbs) && count($breadcrumbs) > 0)
                    <nav class="flex mb-6" aria-label="{{ trans('all.breadcrumb') }}">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3 {{ is_rtl() ? 'space-x-reverse' : '' }}">
                            <li class="inline-flex items-center">
                                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">
                                    <i class="fas fa-home w-4 h-4 {{ is_rtl() ? 'ml-2' : 'mr-2' }}"></i>
                                    {{ trans('all.dashboard') }}
                                </a>
                            </li>
                            @foreach($breadcrumbs as $breadcrumb)
                                @if(is_array($breadcrumb))
                                    <li>
                                        <div class="flex items-center">
                                            <i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }} w-4 h-4 text-gray-400"></i>
                                            @if(isset($breadcrumb['url']) && !empty($breadcrumb['url']))
                                                <a href="{{ $breadcrumb['url'] }}" class="{{ is_rtl() ? 'mr-1 md:mr-3' : 'ml-1 md:ml-3' }} text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">
                                                    {{ $breadcrumb['title'] ?? $breadcrumb['name'] ?? trans('all.untitled') }}
                                                </a>
                                            @else
                                                <span class="{{ is_rtl() ? 'mr-1 md:mr-3' : 'ml-1 md:ml-3' }} text-sm font-medium text-gray-500">
                                                    {{ $breadcrumb['title'] ?? $breadcrumb['name'] ?? trans('all.untitled') }}
                                                </span>
                                            @endif
                                        </div>
                                    </li>
                                @elseif(is_string($breadcrumb))
                                    <li>
                                        <div class="flex items-center">
                                            <i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }} w-4 h-4 text-gray-400"></i>
                                            <span class="{{ is_rtl() ? 'mr-1 md:mr-3' : 'ml-1 md:ml-3' }} text-sm font-medium text-gray-500">
                                                {{ $breadcrumb }}
                                            </span>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ol>
                    </nav>
                @endif

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="alert alert-success mb-6 slide-in">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 {{ is_rtl() ? 'ml-3' : 'mr-3' }}"></i>
                            <div>
                                <p class="font-medium text-green-800">{{ trans('all.operation_successful') }}</p>
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error mb-6 slide-in">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 {{ is_rtl() ? 'ml-3' : 'mr-3' }}"></i>
                            <div>
                                <p class="font-medium text-red-800">{{ trans('all.operation_failed') }}</p>
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning mb-6 slide-in">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-yellow-500 {{ is_rtl() ? 'ml-3' : 'mr-3' }}"></i>
                            <div>
                                <p class="font-medium text-yellow-800">{{ trans('all.warning') }}</p>
                                <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info mb-6 slide-in">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-500 {{ is_rtl() ? 'ml-3' : 'mr-3' }}"></i>
                            <div>
                                <p class="font-medium text-blue-800">{{ trans('all.information') }}</p>
                                <p class="text-sm text-blue-700">{{ session('info') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="alert alert-error mb-6 slide-in">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle text-red-500 {{ is_rtl() ? 'ml-3' : 'mr-3' }} mt-0.5"></i>
                            <div class="flex-1">
                                <p class="font-medium text-red-800 mb-2">{{ trans('all.validation_errors') }}</p>
                                <ul class="text-sm text-red-700 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Main Content --}}
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Enhanced JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hide loading screen
            const loadingScreen = document.getElementById('loadingScreen');
            if (loadingScreen) {
                setTimeout(() => {
                    loadingScreen.style.opacity = '0';
                    setTimeout(() => {
                        loadingScreen.style.display = 'none';
                    }, 300);
                }, 500);
            }

            // Enhanced Menu Dropdowns
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-target');
                    const target = document.getElementById(targetId);
                    const icon = this.querySelector('.dropdown-toggle-icon');

                    // Close all other dropdown menus in the sidebar
                    document.querySelectorAll('[id$="Dropdown"]:not(.dropdown-menu)').forEach(menu => {
                        if (menu.id !== targetId && menu.classList.contains('show')) {
                            menu.classList.remove('show');

                            // Reset other dropdown icons
                            const otherToggles = document.querySelectorAll(`.dropdown-toggle[data-target="${menu.id}"]`);
                            otherToggles.forEach(otherToggle => {
                                const otherIcon = otherToggle.querySelector('.dropdown-toggle-icon');
                                if (otherIcon) {
                                    otherIcon.classList.remove('rotated');
                                }
                            });
                        }
                    });

                    // Toggle current dropdown
                    target.classList.toggle('show');
                    icon.classList.toggle('rotated');
                });
            });

            // Enhanced Mobile Sidebar Functionality
            const openSidebar = document.getElementById('openSidebar');
            const closeSidebar = document.getElementById('closeSidebar');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            function openSidebarMenu() {
                sidebar.classList.add('open');
                sidebarOverlay.classList.add('show');
                document.body.classList.add('overflow-hidden');
            }

            function closeSidebarMenu() {
                sidebar.classList.remove('open');
                sidebarOverlay.classList.remove('show');
                document.body.classList.remove('overflow-hidden');
            }

            if (openSidebar) openSidebar.addEventListener('click', openSidebarMenu);
            if (closeSidebar) closeSidebar.addEventListener('click', closeSidebarMenu);
            if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebarMenu);

            // Enhanced dropdown functionality for header
            function setupDropdown(buttonId, dropdownId) {
                const button = document.getElementById(buttonId);
                const dropdown = document.getElementById(dropdownId);

                if (!button || !dropdown) return;

                button.addEventListener('click', function(e) {
                    e.stopPropagation();

                    // Check if this dropdown is already open
                    const isOpen = dropdown.classList.contains('show');

                    // Close all dropdowns first
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        menu.classList.remove('show');
                    });

                    // If this dropdown wasn't open, open it
                    if (!isOpen) {
                        dropdown.classList.add('show');
                    }
                });

                // Stop propagation for dropdown content
                dropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            // Setup header dropdowns
            setupDropdown('languageBtn', 'languageDropdown');
            setupDropdown('profileBtn', 'profileDropdown');
            setupDropdown('notificationBtn', 'notificationDropdown');

            // Close dropdowns when clicking outside
            document.addEventListener('click', function() {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
            });

            // Handle responsive sidebar
            function handleSidebarResponsive() {
                if (window.innerWidth >= 768) { // md breakpoint in Tailwind
                    if (sidebar) {
                        sidebar.classList.remove('open');
                        if (sidebarOverlay) sidebarOverlay.classList.remove('show');
                        document.body.classList.remove('overflow-hidden');
                    }
                }
            }

            window.addEventListener('resize', handleSidebarResponsive);
            handleSidebarResponsive(); // Run on initial load

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateX({{ is_rtl() ? "-" : "" }}100%)';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });

            // Enhanced keyboard navigation
            document.addEventListener('keydown', function(e) {
                // Close dropdowns with Escape key
                if (e.key === 'Escape') {
                    document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                        menu.classList.remove('show');
                    });
                    closeSidebarMenu();
                }

                // Toggle sidebar with Ctrl/Cmd + B
                if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                    e.preventDefault();
                    if (window.innerWidth < 768) {
                        if (sidebar.classList.contains('open')) {
                            closeSidebarMenu();
                        } else {
                            openSidebarMenu();
                        }
                    }
                }
            });
        });

        // Enhanced utility functions
        function confirmDelete(event, message = '{{ trans("files.delete_confirm") }}') {
            if (!confirm(message)) {
                event.preventDefault();
                return false;
            }

            // Add loading state to button
            const button = event.target.closest('button, a');
            if (button) {
                button.disabled = true;
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin {{ is_rtl() ? "ml-2" : "mr-2" }}"></i> {{ trans("files.processing") }}';

                // Restore button after 5 seconds (fallback)
                setTimeout(() => {
                    button.disabled = false;
                    button.innerHTML = originalText;
                }, 5000);
            }

            return true;
        }

        function previewImage(input, previewId) {
            const previewElement = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewElement.src = e.target.result;
                    previewElement.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function handlePerPageChange(select) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', select.value);
            url.searchParams.delete('page'); // Reset to first page
            window.location.href = url.toString();
        }

        function toggleAllCheckboxes(master, selector) {
            const checkboxes = document.querySelectorAll(selector);
            checkboxes.forEach(checkbox => {
                checkbox.checked = master.checked;
            });

            // Trigger change event for each checkbox
            checkboxes.forEach(checkbox => {
                checkbox.dispatchEvent(new Event('change'));
            });
        }

        // Enhanced notification system
        function showNotification(message, type = 'info', duration = 5000) {
            const notification = document.createElement('div');
            notification.className = `toast ${type}`;

            const icon = type === 'success' ? 'check-circle' :
                        type === 'error' ? 'exclamation-circle' :
                        type === 'warning' ? 'exclamation-triangle' : 'info-circle';

            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${icon} {{ is_rtl() ? 'ml-3' : 'mr-3' }}"></i>
                    <p class="flex-1">${message}</p>
                    <button onclick="this.parentElement.parentElement.remove()" class="{{ is_rtl() ? 'mr-2' : 'ml-2' }} text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            document.body.appendChild(notification);

            // Auto remove after duration
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX({{ is_rtl() ? "-" : "" }}100%)';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, duration);
        }

        // Form validation enhancement
        function validateForm(formSelector) {
            const form = document.querySelector(formSelector);
            if (!form) return true;

            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                field.classList.remove('border-red-500');

                if (!field.value.trim()) {
                    field.classList.add('border-red-500');
                    isValid = false;
                }
            });

            return isValid;
        }

        // Loading state management
        function setLoadingState(element, loading = true) {
            if (loading) {
                element.disabled = true;
                element.dataset.originalText = element.innerHTML;
                element.innerHTML = '<i class="fas fa-spinner fa-spin {{ is_rtl() ? "ml-2" : "mr-2" }}"></i> {{ trans("files.loading") }}';
            } else {
                element.disabled = false;
                element.innerHTML = element.dataset.originalText || element.innerHTML;
            }
        }

        // Export utilities to global scope
        window.adminUtils = {
            confirmDelete,
            previewImage,
            handlePerPageChange,
            toggleAllCheckboxes,
            showNotification,
            validateForm,
            setLoadingState
        };
    </script>

    {{-- Additional scripts --}}
    @stack('scripts')
    @yield('scripts')

    {{-- Additional styles for alerts --}}
    <style>
        .alert {
            @apply p-4 rounded-lg border mb-4 transition-all duration-300;
        }

        .alert-success {
            @apply bg-green-50 border-green-200 text-green-800;
        }

        .alert-error {
            @apply bg-red-50 border-red-200 text-red-800;
        }

        .alert-warning {
            @apply bg-yellow-50 border-yellow-200 text-yellow-800;
        }

        .alert-info {
            @apply bg-blue-50 border-blue-200 text-blue-800;
        }

        .spinner-large {
            @apply w-8 h-8 border-4 border-gray-300 border-t-primary-600 rounded-full;
            animation: spin 1s linear infinite;
        }
    </style>
</body>
</html>

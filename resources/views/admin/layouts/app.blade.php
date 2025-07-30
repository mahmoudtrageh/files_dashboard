<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', trans('admin.dashboard'))</title>
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
                    },
                    fontFamily: {
                        'tajawal': ['Tajawal', 'sans-serif'],
                    },
                    boxShadow: {
                        'card': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
                        'card-hover': '0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
        }

        /* Utility for tracking body scroll state */
        body.overflow-hidden {
            overflow: hidden;
        }

        /* Status badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-badge i {
            margin-left: 0.5rem;
        }

        .status-new {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-delivered {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-processing {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-shipped {
            background-color: #ccfbf1;
            color: #0f766e;
        }

        .status-cancelled {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        /* Clean scrollbar */
        .clean-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .clean-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .clean-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        .clean-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }

        /* Fixed RTL header dropdowns */
        .dropdown-container {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0; /* RTL positioning */
            width: 260px;
            z-index: 50;
            transform: translateY(10px);
            visibility: hidden;
            opacity: 0;
            transition: all 0.25s ease;
        }

        .dropdown-menu.show {
            transform: translateY(0);
            visibility: visible;
            opacity: 1;
        }

        /* Chart placeholder styling */
        .chart-line {
            stroke-dasharray: 1000;
            stroke-dashoffset: 1000;
            animation: dash 3s ease-in-out forwards;
        }

        @keyframes dash {
            to {
                stroke-dashoffset: 0;
            }
        }

        /* Responsive sidebar */
        @media (max-width: 767px) {
            .sidebar-mobile {
                position: fixed;
                right: -100%;
                top: 0;
                bottom: 0;
                height: 100vh;
                transition: right 0.3s ease;
            }

            .sidebar-mobile.open {
                right: 0;
            }
        }

        /* Focus styles */
        .focus-ring:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.4);
        }

        /* For smooth page load */
        .fade-in {
            animation: fadeIn 0.3s ease-in-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* For mobile fullscreen sidebar */
        @media (max-width: 767px) {
            #sidebar {
                height: 100vh;
                max-height: 100vh;
                overflow-y: auto;
            }
        }

        /* roles management */

        /* Status Badges */
        .status-badge {
            @apply inline-flex items-center px-2 py-1 text-xs font-medium rounded-full;
        }

        .status-active {
            @apply bg-green-100 text-green-800;
        }

        .status-inactive {
            @apply bg-red-100 text-red-800;
        }

        .status-new {
            @apply bg-blue-100 text-blue-800;
        }

        .status-processing {
            @apply bg-purple-100 text-purple-800;
        }

        .status-shipped {
            @apply bg-indigo-100 text-indigo-800;
        }

        .status-delivered {
            @apply bg-green-100 text-green-800;
        }

        .status-cancelled {
            @apply bg-red-100 text-red-800;
        }

        .status-refunded {
            @apply bg-orange-100 text-orange-800;
        }

        /* Clean Scrollbar */
        .clean-scrollbar::-webkit-scrollbar {
            height: 6px;
            width: 6px;
        }

        .clean-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .clean-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .clean-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }

    </style>
</head>
<body class="bg-gray-50 fade-in">

    <div class="flex h-screen overflow-hidden">
        <!-- Mobile Sidebar Overlay -->
        <div id="sidebarOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-30"></div>

        <!-- Sidebar -->
        @include('admin.layouts.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Top Navbar -->
            @include('admin.layouts.header')

            <!-- Page Content -->
            <main class="flex-1 p-4 overflow-y-auto clean-scrollbar">

                @yield('content')

            </main>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Menu Dropdowns
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const target = document.getElementById(targetId);
                    const icon = this.querySelector('.fa-chevron-down, .fa-chevron-up');

                    // Close all other dropdown menus in the sidebar
                    document.querySelectorAll('[id$="Dropdown"]:not(.dropdown-menu)').forEach(menu => {
                        if (menu.id !== targetId && !menu.classList.contains('hidden')) {
                            menu.classList.add('hidden');

                            // Reset other dropdown icons
                            const otherToggles = document.querySelectorAll(`.dropdown-toggle[data-target="${menu.id}"]`);
                            otherToggles.forEach(otherToggle => {
                                const otherIcon = otherToggle.querySelector('.fa-chevron-down, .fa-chevron-up');
                                if (otherIcon) {
                                    otherIcon.classList.add('fa-chevron-down');
                                    otherIcon.classList.remove('fa-chevron-up');
                                    otherIcon.style.transform = 'rotate(0deg)';
                                }
                            });
                        }
                    });

                    if (target.classList.contains('hidden')) {
                        target.classList.remove('hidden');
                        if (icon) {
                            icon.classList.add('fa-chevron-up');
                            icon.classList.remove('fa-chevron-down');
                            icon.style.transform = 'rotate(180deg)';
                        }
                    } else {
                        target.classList.add('hidden');
                        if (icon) {
                            icon.classList.add('fa-chevron-down');
                            icon.classList.remove('fa-chevron-up');
                            icon.style.transform = 'rotate(0deg)';
                        }
                    }
                });
            });

            // Mobile Sidebar Functionality
            const openSidebar = document.getElementById('openSidebar');
            const closeSidebar = document.getElementById('closeSidebar');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            function openSidebarMenu() {
                sidebar.classList.add('open');
                sidebarOverlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeSidebarMenu() {
                sidebar.classList.remove('open');
                sidebarOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            openSidebar.addEventListener('click', openSidebarMenu);
            closeSidebar.addEventListener('click', closeSidebarMenu);
            sidebarOverlay.addEventListener('click', closeSidebarMenu);

            // Improved dropdown functionality
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

            setupDropdown('languageBtn', 'languageDropdown');
            setupDropdown('profileBtn', 'profileDropdown');

            // Set up your dropdowns
            document.addEventListener('DOMContentLoaded', function() {
                setupDropdown('languageBtn', 'languageDropdown');
                setupDropdown('profileBtn', 'profileDropdown');

                // Close dropdowns when clicking outside
                document.addEventListener('click', function() {
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        menu.classList.remove('show');
                    });
                });
            });

            // Handle responsive sidebar
            function handleSidebarResponsive() {
                if (window.innerWidth >= 768) { // md breakpoint in Tailwind
                    sidebar.classList.remove('open');
                    sidebarOverlay.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            }

            window.addEventListener('resize', handleSidebarResponsive);
            handleSidebarResponsive(); // Run on initial load
        });

        function confirmDelete(event, message = 'هل أنت متأكد من حذف هذا العنصر؟') {
            if (!confirm(message)) {
                event.preventDefault();
                return false;
            }
            return true;
        }

        /**
         * Preview uploaded image
         * @param {HTMLInputElement} input - The file input element
         * @param {string} previewId - The ID of the preview image element
         */
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

        /**
         * Handle per-page change for pagination
         * @param {HTMLSelectElement} select - The per-page select element
         */
        function handlePerPageChange(select) {
            let url = new URL(window.location.href);
            url.searchParams.set('per_page', select.value);
            window.location.href = url.toString();
        }

        /**
         * Toggle all checkboxes in a form
         * @param {HTMLInputElement} master - The master checkbox
         * @param {string} selector - Selector for the checkboxes to toggle
         */
        function toggleAllCheckboxes(master, selector) {
            const checkboxes = document.querySelectorAll(selector);
            checkboxes.forEach(checkbox => {
                checkbox.checked = master.checked;
            });
        }

        // Export utilities if using module system
        if (typeof module !== 'undefined' && module.exports) {
            module.exports = {
                confirmDelete,
                previewImage,
                handlePerPageChange,
                toggleAllCheckboxes
            };
        }
    </script>

    @yield('scripts')
</body>
</html>

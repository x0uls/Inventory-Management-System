<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Inventory Management System')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <!-- Sidebar Header -->
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <div class="sidebar-logo-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div class="sidebar-logo-text">
                        <h1>INVENTORY</h1>
                        <p>Management System</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- User Management Dropdown -->
                <!-- User Management Dropdown -->
                @if(auth()->user()->roles !== 'staff')
                <div class="nav-group">
                    <button id="user-management-toggle" class="nav-item" style="width: 100%; background: none; border: none; cursor: pointer;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span style="flex: 1; text-align: left;">User Management</span>
                        <svg id="user-management-chevron" class="chevron-icon {{ request()->routeIs('users.*') || request()->routeIs('groups.*') ? 'rotate-180' : '' }}" style="width: 16px; height: 16px; transition: transform 0.2s;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="user-management-dropdown" class="sidebar-dropdown" style="{{ request()->routeIs('users.*') || request()->routeIs('groups.*') ? 'display: block;' : '' }}">
                        <a href="{{ route('groups.index') }}" class="nav-item {{ request()->routeIs('groups.*') ? 'active' : '' }}">
                            <span>Manage User Groups</span>
                        </a>
                        <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <span>Manage Users</span>
                        </a>
                    </div>
                </div>
                @endif

                <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <span>Categories</span>
                </a>

                <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span>Products</span>
                </a>

                <a href="{{ route('suppliers.index') }}" class="nav-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Suppliers</span>
                </a>

                <a href="{{ route('stock.index') }}" class="nav-item {{ request()->routeIs('stock.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span>Stock</span>
                </a>

                <a href="/sales" class="nav-item {{ request()->is('sales') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Sales</span>
                </a>

                <a href="/reports" class="nav-item {{ request()->is('reports') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Reports</span>
                </a>
            </nav>

            <!-- Logout Button -->
            <div class="sidebar-footer">
                <div class="sidebar-time" id="current-time">
                    {{ now()->format('F d, Y, g:i a') }}
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-item" style="width: 100%; background: none; border: none; cursor: pointer;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Mobile Overlay -->
        <div class="mobile-overlay" id="mobile-overlay"></div>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <button class="mobile-menu-btn" id="mobile-menu-toggle" aria-label="Toggle menu">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="top-bar-actions">
                    <x-notification-bell :count="$lowStockCount ?? 0" />
                </div>
            </div>

            <!-- Content Area -->
            <main class="content-area">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                @if(isset($errors) && $errors->any())
                    <div class="alert alert-error">
                        <ul style="margin: 0; padding-left: 1.25rem;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @stack('scripts')
    <script>
        // Update time every second
        function updateTime() {
            const now = new Date();
            const options = { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            };
            const timeString = now.toLocaleString('en-US', options);
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                timeElement.textContent = timeString;
            }
        }

        updateTime();
        setInterval(updateTime, 1000);

        // Mobile sidebar toggle functionality
        $(document).ready(function() {
            const $sidebar = $('.sidebar');
            const $overlay = $('#mobile-overlay');
            const $menuToggle = $('#mobile-menu-toggle');
            const $navItems = $('.nav-item');

            // Toggle sidebar when hamburger is clicked
            $menuToggle.on('click', function() {
                $sidebar.toggleClass('open');
                $overlay.toggleClass('show');
            });

            // Close sidebar when overlay is clicked
            $overlay.on('click', function() {
                $sidebar.removeClass('open');
                $overlay.removeClass('show');
            });

            // Close sidebar when any nav link is clicked
            $navItems.on('click', function() {
                if ($(window).width() < 768) {
                    // Don't close if it's the dropdown toggle
                    if (!$(this).is('#user-management-toggle')) {
                        $sidebar.removeClass('open');
                        $overlay.removeClass('show');
                    }
                }
            });

            // User Management Dropdown
            $('#user-management-toggle').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Prevent bubbling to document
                $('#user-management-dropdown').slideToggle(200);
                $('#user-management-chevron').toggleClass('rotate-180');
            });
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - @yield('title', 'HomeNest Furniture')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Custom Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'admin-blue': '#1e3a8a',
                        'admin-light-blue': '#3b82f6',
                        'admin-white': '#ffffff',
                        'admin-gray': '#f3f4f6',
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: #1e3a8a;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #60a5fa;
            border-radius: 10px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: #93c5fd;
        }

        /* Mobile sidebar transition */
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            transition: opacity 0.3s ease-in-out;
        }

        /* Prevent body scroll when mobile sidebar is open */
        body.sidebar-open {
            overflow: hidden;
        }

        /* Dropdown animation */
        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease-in-out;
        }

        .dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        /* Sidebar submenu styles */
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .submenu.open {
            max-height: 500px;
            transition: max-height 0.3s ease-in;
        }

        .rotate-icon {
            transition: transform 0.3s ease;
        }

        .rotate-icon.rotated {
            transform: rotate(90deg);
        }
    </style>
</head>

<body class="bg-admin-gray font-sans antialiased">
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden sidebar-overlay"
        onclick="closeSidebar()"></div>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar - Desktop: fixed, Mobile: absolute -->
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-30 w-72 bg-admin-blue text-white shadow-xl transform -translate-x-full md:relative md:translate-x-0 sidebar-transition flex flex-col">
            <!-- Close button for mobile -->
            <div class="absolute top-4 right-4 md:hidden">
                <button onclick="closeSidebar()" class="text-white hover:text-blue-200">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Logo Area -->
            <div class="p-6 border-b border-blue-800">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-couch text-2xl text-admin-light-blue"></i>
                    <div>
                        <h1 class="text-xl font-bold">HomeNest</h1>
                        <p class="text-xs text-blue-300">Furniture Admin</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Links with Submenus -->
            <nav class="flex-1 py-6 overflow-y-auto sidebar-scroll">
                <ul class="space-y-2 px-4">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-admin-light-blue text-white shadow-lg' : 'hover:bg-blue-800 text-blue-100' }}">
                            <i class="fas fa-tachometer-alt w-5"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- Products Management with Submenu -->
                    <li>
                        <div class="flex items-center justify-between px-4 py-3 rounded-lg cursor-pointer hover:bg-blue-800 text-blue-100 transition-all duration-200"
                            onclick="toggleSubmenu('productsSubmenu')">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-boxes w-5"></i>
                                <span>Products</span>
                            </div>
                            <i class="fas fa-chevron-right rotate-icon text-sm" id="productsIcon"></i>
                        </div>
                        <ul id="productsSubmenu" class="submenu ml-6 mt-1 space-y-1">
                            <li>
                                <a href="{{ route('admin.products.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-list w-4"></i>
                                    <span>All Products</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.products.create') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-plus w-4"></i>
                                    <span>Add Product</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-warehouse w-4"></i>
                                    <span>Stock Management</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Product Types -->
                    <li>
                        <div class="flex items-center justify-between px-4 py-3 rounded-lg cursor-pointer hover:bg-blue-800 text-blue-100 transition-all duration-200"
                            onclick="toggleSubmenu('typesSubmenu')">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-tags w-5"></i>
                                <span>Product Types</span>
                            </div>
                            <i class="fas fa-chevron-right rotate-icon text-sm" id="typesIcon"></i>
                        </div>
                        <ul id="typesSubmenu" class="submenu ml-6 mt-1 space-y-1">
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-list w-4"></i>
                                    <span>All Types</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-plus w-4"></i>
                                    <span>Add Type</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Brands -->
                    <li>
                        <div class="flex items-center justify-between px-4 py-3 rounded-lg cursor-pointer hover:bg-blue-800 text-blue-100 transition-all duration-200"
                            onclick="toggleSubmenu('brandsSubmenu')">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-trademark w-5"></i>
                                <span>Brands</span>
                            </div>
                            <i class="fas fa-chevron-right rotate-icon text-sm" id="brandsIcon"></i>
                        </div>
                        <ul id="brandsSubmenu" class="submenu ml-6 mt-1 space-y-1">
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-list w-4"></i>
                                    <span>All Brands</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-plus w-4"></i>
                                    <span>Add Brand</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Orders -->
                    <li>
                        <div class="flex items-center justify-between px-4 py-3 rounded-lg cursor-pointer hover:bg-blue-800 text-blue-100 transition-all duration-200" onclick="toggleSubmenu('ordersSubmenu')">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-shopping-cart w-5"></i>
                                <span>Orders</span>
                            </div>
                            <i class="fas fa-chevron-right rotate-icon text-sm" id="ordersIcon"></i>
                        </div>
                        <ul id="ordersSubmenu" class="submenu ml-6 mt-1 space-y-1">
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-list w-4"></i>
                                    <span>All Orders</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-clock w-4"></i>
                                    <span>Pending Orders</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-check-circle w-4"></i>
                                    <span>Completed Orders</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Customers -->
                    <li>
                        <div class="flex items-center justify-between px-4 py-3 rounded-lg cursor-pointer hover:bg-blue-800 text-blue-100 transition-all duration-200" onclick="toggleSubmenu('customersSubmenu')">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-users w-5"></i>
                                <span>Customers</span>
                            </div>
                            <i class="fas fa-chevron-right rotate-icon text-sm" id="customersIcon"></i>
                        </div>
                        <ul id="customersSubmenu" class="submenu ml-6 mt-1 space-y-1">
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-list w-4"></i>
                                    <span>All Customers</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-user-plus w-4"></i>
                                    <span>Add Customer</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Promotions -->
                    <li>
                        <div class="flex items-center justify-between px-4 py-3 rounded-lg cursor-pointer hover:bg-blue-800 text-blue-100 transition-all duration-200" onclick="toggleSubmenu('promotionsSubmenu')">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-gift w-5"></i>
                                <span>Promotions</span>
                            </div>
                            <i class="fas fa-chevron-right rotate-icon text-sm" id="promotionsIcon"></i>
                        </div>
                        <ul id="promotionsSubmenu" class="submenu ml-6 mt-1 space-y-1">
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-list w-4"></i>
                                    <span>All Promotions</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-plus w-4"></i>
                                    <span>Add Promotion</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-fire w-4"></i>
                                    <span>Active Promotions</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Feedbacks -->
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.feedbacks*') ? 'bg-admin-light-blue text-white shadow-lg' : 'hover:bg-blue-800 text-blue-100' }}">
                            <i class="fas fa-star w-5"></i>
                            <span>Feedbacks</span>
                            @php
                                $unreadCount = \App\Models\Feedback::where(
                                    'created_at',
                                    '>=',
                                    now()->subDays(7),
                                )->count();
                            @endphp
                            @if ($unreadCount > 0)
                                <span
                                    class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    </li>

                    <!-- Deliveries -->
                    <li>
                        <div class="flex items-center justify-between px-4 py-3 rounded-lg cursor-pointer hover:bg-blue-800 text-blue-100 transition-all duration-200"
                            onclick="toggleSubmenu('deliveriesSubmenu')">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-truck w-5"></i>
                                <span>Deliveries</span>
                            </div>
                            <i class="fas fa-chevron-right rotate-icon text-sm" id="deliveriesIcon"></i>
                        </div>
                        <ul id="deliveriesSubmenu" class="submenu ml-6 mt-1 space-y-1">
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-list w-4"></i>
                                    <span>All Deliveries</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-hourglass-half w-4"></i>
                                    <span>Pending Deliveries</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-spinner w-4"></i>
                                    <span>In Progress</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-check-circle w-4"></i>
                                    <span>Delivered</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Payments -->
                    <li>
                        <div class="flex items-center justify-between px-4 py-3 rounded-lg cursor-pointer hover:bg-blue-800 text-blue-100 transition-all duration-200" onclick="toggleSubmenu('paymentsSubmenu')">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-credit-card w-5"></i>
                                <span>Payments</span>
                            </div>
                            <i class="fas fa-chevron-right rotate-icon text-sm" id="paymentsIcon"></i>
                        </div>
                        <ul id="paymentsSubmenu" class="submenu ml-6 mt-1 space-y-1">
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-list w-4"></i>
                                    <span>All Payments</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-clock w-4"></i>
                                    <span>Pending Payments</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-check-circle w-4"></i>
                                    <span>Completed Payments</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-exchange-alt w-4"></i>
                                    <span>Transactions</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Reports -->
                    <li>
                        <div class="flex items-center justify-between px-4 py-3 rounded-lg cursor-pointer hover:bg-blue-800 text-blue-100 transition-all duration-200"
                            onclick="toggleSubmenu('reportsSubmenu')">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-chart-line w-5"></i>
                                <span>Reports</span>
                            </div>
                            <i class="fas fa-chevron-right rotate-icon text-sm" id="reportsIcon"></i>
                        </div>
                        <ul id="reportsSubmenu" class="submenu ml-6 mt-1 space-y-1">
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-chart-bar w-4"></i>
                                    <span>Sales Report</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-boxes w-4"></i>
                                    <span>Products Report</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-users w-4"></i>
                                    <span>Customers Report</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-dollar-sign w-4"></i>
                                    <span>Revenue Report</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-warehouse w-4"></i>
                                    <span>Inventory Report</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-truck w-4"></i>
                                    <span>Delivery Report</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Settings -->
                    <li class="pt-4 mt-2 border-t border-blue-800">
                        <div class="flex items-center justify-between px-4 py-3 rounded-lg cursor-pointer hover:bg-blue-800 text-blue-100 transition-all duration-200"
                            onclick="toggleSubmenu('settingsSubmenu')">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-cog w-5"></i>
                                <span>Settings</span>
                            </div>
                            <i class="fas fa-chevron-right rotate-icon text-sm" id="settingsIcon"></i>
                        </div>
                        <ul id="settingsSubmenu" class="submenu ml-6 mt-1 space-y-1">
                            <li>
                                <a href="#"
                                    class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-globe w-4"></i>
                                    <span>General Settings</span>
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-shipping-fast w-4"></i>
                                    <span>Shipping Settings</span>
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm hover:bg-blue-800 text-blue-100 transition-all duration-200">
                                    <i class="fas fa-credit-card w-4"></i>
                                    <span>Payment Settings</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>

            <!-- Sidebar Footer / User Info -->
            <div class="p-4 border-t border-blue-800">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 rounded-full bg-admin-light-blue flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ Auth::user()->name ?? 'Admin User' }}</p>
                        <p class="text-xs text-blue-300 truncate">{{ Auth::user()->email ?? 'admin@homenest.com' }}
                        </p>
                    </div>
                    <form method="POST" action="#" class="inline">
                        @csrf
                        <button type="submit" class="text-blue-300 hover:text-white flex-shrink-0">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 w-full md:ml-0 overflow-y-auto">
            <!-- Top Navigation Bar -->
            <header class="bg-admin-white shadow-sm sticky top-0 z-20">
                <div class="flex justify-between items-center px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
                    <!-- Mobile Menu Button -->
                    <button onclick="openSidebar()"
                        class="md:hidden text-admin-blue hover:text-admin-light-blue focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>

                    <div class="flex-1 md:flex-none">
                        <h2 class="text-lg sm:text-xl font-semibold text-admin-blue">@yield('header', 'Dashboard')</h2>
                        <p class="text-xs sm:text-sm text-gray-500 hidden sm:block">Welcome back, {{ Auth::user()->name ?? 'Admin' }}!</p>
                    </div>

                    <div class="flex items-center space-x-3 sm:space-x-4">
                        <!-- Notifications -->
                        <button class="relative text-gray-500 hover:text-admin-blue">
                            <i class="fas fa-bell text-lg sm:text-xl"></i>
                            <span
                                class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">3</span>
                        </button>

                        <!-- Messages -->
                        <button class="text-gray-500 hover:text-admin-blue hidden sm:block">
                            <i class="fas fa-envelope text-xl"></i>
                        </button>

                        <div class="h-6 sm:h-8 w-px bg-gray-300 hidden sm:block"></div>

                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <div id="profileDropdownBtn"
                                class="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 rounded-lg px-2 py-1 transition-colors">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-admin-light-blue flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm sm:text-base"></i>
                                </div>
                                <span
                                    class="text-sm text-gray-700 hidden sm:inline">{{ Auth::user()->name ?? 'Admin' }}</span>
                                <i class="fas fa-chevron-down text-gray-500 text-xs sm:text-sm hidden sm:block transition-transform duration-200"
                                    id="dropdownArrow"></i>
                            </div>

                            <!-- Dropdown Menu -->
                            <div id="profileDropdown"
                                class="dropdown-menu absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-100 overflow-hidden z-50">
                                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name ?? 'Admin User' }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email ?? 'admin@homenest.com' }}</p>
                                </div>
                                <div class="py-2">
                                    <a href="#"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-admin-blue transition-colors">
                                        <i class="fas fa-user-circle w-5 mr-3 text-gray-400"></i>
                                        <span>My Profile</span>
                                    </a>
                                    <a href="#"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-admin-blue transition-colors">
                                        <i class="fas fa-edit w-5 mr-3 text-gray-400"></i>
                                        <span>Edit Profile</span>
                                    </a>
                                    <a href="#"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-admin-blue transition-colors">
                                        <i class="fas fa-cog w-5 mr-3 text-gray-400"></i>
                                        <span>Account Settings</span>
                                    </a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <form method="POST" action="#">
                                        @csrf
                                        <button type="submit"
                                            class="w-full flex items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            <i class="fas fa-sign-out-alt w-5 mr-3 text-red-400"></i>
                                            <span>Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Toggle submenu function
        function toggleSubmenu(submenuId) {
            const submenu = document.getElementById(submenuId);
            const icon = document.getElementById(submenuId.replace('Submenu', 'Icon'));

            if (submenu.classList.contains('open')) {
                submenu.classList.remove('open');
                if (icon) icon.classList.remove('rotated');
            } else {
                submenu.classList.add('open');
                if (icon) icon.classList.add('rotated');
            }
        }

        // Open submenu based on current route
        function openSubmenuForCurrentRoute() {
            const currentUrl = window.location.href;

            // Check each submenu and open if current route matches
            const submenus = ['productsSubmenu', 'typesSubmenu', 'brandsSubmenu', 'ordersSubmenu', 'customersSubmenu', 'promotionsSubmenu', 'deliveriesSubmenu', 'paymentsSubmenu', 'reportsSubmenu', 'settingsSubmenu'];
            
            submenus.forEach(submenuId => {
                const submenu = document.getElementById(submenuId);
                if (submenu) {
                    const links = submenu.querySelectorAll('a');
                    for (let link of links) {
                        if (currentUrl.includes(link.getAttribute('href'))) {
                            submenu.classList.add('open');
                            const icon = document.getElementById(submenuId.replace('Submenu', 'Icon'));
                            if (icon) icon.classList.add('rotated');
                            break;
                        }
                    }
                }
            });
        }

        // Dropdown functionality
        const dropdownBtn = document.getElementById('profileDropdownBtn');
        const dropdownMenu = document.getElementById('profileDropdown');
        const dropdownArrow = document.getElementById('dropdownArrow');

        function toggleDropdown(event) {
            if (event) event.stopPropagation();
            if (dropdownMenu) {
                dropdownMenu.classList.toggle('show');
                if (dropdownArrow) {
                    dropdownArrow.style.transform = dropdownMenu.classList.contains('show') ? 'rotate(180deg)' :
                    'rotate(0)';
                }
            }
        }

        function closeDropdown() {
            if (dropdownMenu) {
                dropdownMenu.classList.remove('show');
                if (dropdownArrow) {
                    dropdownArrow.style.transform = 'rotate(0)';
                }
            }
        }

        if (dropdownBtn) {
            dropdownBtn.addEventListener('click', toggleDropdown);
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function (event) {
            if (dropdownBtn && !dropdownBtn.contains(event.target)) {
                closeDropdown();
            }
        });

        // Close dropdown on escape key
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeDropdown();
            }
        });

        // Sidebar functions
        function openSidebar() {
            document.getElementById('sidebar').classList.remove('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.remove('hidden');
            document.body.classList.add('sidebar-open');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.add('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.add('hidden');
            document.body.classList.remove('sidebar-open');
        }

        // Close sidebar on window resize if screen becomes desktop
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 768) {
                document.getElementById('sidebar').classList.remove('-translate-x-full');
                document.getElementById('sidebarOverlay').classList.add('hidden');
                document.body.classList.remove('sidebar-open');
            }
        });

        // Open submenu for current route on page load
        document.addEventListener('DOMContentLoaded', function () {
            openSubmenuForCurrentRoute();
        });

        // Function to export report as image
        function exportAsImage(elementId, filename) {
            const element = document.getElementById(elementId);
            if (!element) {
                console.error('Element not found:', elementId);
                return;
            }

            // Show loading indicator
            const exportBtn = event.target;
            const originalText = exportBtn.innerHTML;
            exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Exporting...';
            exportBtn.disabled = true;

            html2canvas(element, {
                scale: 2,
                backgroundColor: '#ffffff',
                logging: false,
                useCORS: true
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = `${filename}.png`;
                link.href = canvas.toDataURL();
                link.click();

                // Reset button
                exportBtn.innerHTML = originalText;
                exportBtn.disabled = false;
            }).catch(error => {
                console.error('Error exporting image:', error);
                exportBtn.innerHTML = originalText;
                exportBtn.disabled = false;
                alert('Error exporting image. Please try again.');
            });
        }

        // Function to export chart as image
        function exportChartAsImage(chartId, filename) {
            const canvas = document.getElementById(chartId);
            if (!canvas) {
                console.error('Chart not found:', chartId);
                return;
            }

            const link = document.createElement('a');
            link.download = `${filename}.png`;
            link.href = canvas.toDataURL();
            link.click();
        }
    </script>
</body>

</html>

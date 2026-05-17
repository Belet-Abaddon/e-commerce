<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - @yield('title', 'HomeNest Furniture')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome Icons (Optional but recommended) -->
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
    </style>
</head>
<body class="bg-admin-gray font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-72 bg-admin-blue text-white shadow-xl flex flex-col fixed h-full z-10 transition-all duration-300">
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
            
            <!-- Navigation Links -->
            <nav class="flex-1 py-6 overflow-y-auto sidebar-scroll">
                <ul class="space-y-2 px-4">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-admin-light-blue text-white shadow-lg' : 'hover:bg-blue-800 text-blue-100' }}">
                            <i class="fas fa-tachometer-alt w-5"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-blue-800 text-blue-100">
                            <i class="fas fa-boxes w-5"></i>
                            <span>Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-blue-800 text-blue-100">
                            <i class="fas fa-tags w-5"></i>
                            <span>Categories</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-blue-800 text-blue-100">
                            <i class="fas fa-shopping-cart w-5"></i>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-blue-800 text-blue-100">
                            <i class="fas fa-users w-5"></i>
                            <span>Customers</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-blue-800 text-blue-100">
                            <i class="fas fa-chart-line w-5"></i>
                            <span>Reports</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-blue-800 text-blue-100">
                            <i class="fas fa-cog w-5"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <!-- Sidebar Footer / User Info -->
            <div class="p-4 border-t border-blue-800">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-admin-light-blue flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium">Admin User</p>
                        <p class="text-xs text-blue-300">admin@homenest.com</p>
                    </div>
                    <a href="#" class="text-blue-300 hover:text-white">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </aside>
        
        <!-- Main Content Area -->
        <div class="flex-1 ml-72 overflow-y-auto">
            <!-- Top Navigation Bar -->
            <header class="bg-admin-white shadow-sm sticky top-0 z-20">
                <div class="flex justify-between items-center px-8 py-4">
                    <div>
                        <h2 class="text-xl font-semibold text-admin-blue">@yield('header', 'Dashboard')</h2>
                        <p class="text-sm text-gray-500">Welcome back, Admin!</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="relative text-gray-500 hover:text-admin-blue">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">3</span>
                        </button>
                        <button class="text-gray-500 hover:text-admin-blue">
                            <i class="fas fa-envelope text-xl"></i>
                        </button>
                        <div class="h-8 w-px bg-gray-300"></div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-700">Admin</span>
                            <i class="fas fa-chevron-down text-gray-500 text-sm"></i>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
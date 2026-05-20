<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HomeNest Furniture - @yield('title', 'Products')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#1e3a8a',
                        'primary-light': '#3b82f6',
                    }
                }
            }
        }
    </script>

    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        }
        .feature-card:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Navigation -->
    <nav class="bg-white shadow-md fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2">
                        <i class="fas fa-couch text-2xl text-blue-600"></i>
                        <span class="text-xl font-bold text-blue-900">HomeNest</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ url('/') }}#home" class="text-gray-700 hover:text-blue-600 transition">Home</a>
                    <a href="{{ route('public.products.index') }}" class="text-gray-700 hover:text-blue-600 transition">Products</a>
                    <a href="{{ url('/') }}#categories" class="text-gray-700 hover:text-blue-600 transition">Categories</a>
                    <a href="{{ url('/') }}#brands" class="text-gray-700 hover:text-blue-600 transition">Brands</a>
                    <a href="{{ url('/') }}#about" class="text-gray-700 hover:text-blue-600 transition">About</a>
                    <a href="{{ url('/') }}#contact" class="text-gray-700 hover:text-blue-600 transition">Contact</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                    Dashboard
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition">Login</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Register
                            </a>
                        @endauth
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobileMenuButton" class="md:hidden text-gray-700">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t">
            <div class="px-4 py-2 space-y-2">
                <a href="{{ url('/') }}#home" class="block py-2 text-gray-700 hover:text-blue-600">Home</a>
                <a href="{{ route('public.products.index') }}" class="block py-2 text-gray-700 hover:text-blue-600">Products</a>
                <a href="{{ url('/') }}#categories" class="block py-2 text-gray-700 hover:text-blue-600">Categories</a>
                <a href="{{ url('/') }}#brands" class="block py-2 text-gray-700 hover:text-blue-600">Brands</a>
                <a href="{{ url('/') }}#about" class="block py-2 text-gray-700 hover:text-blue-600">About</a>
                <a href="{{ url('/') }}#contact" class="block py-2 text-gray-700 hover:text-blue-600">Contact</a>
            </div>
        </div>
    </nav>

    <!-- Add spacing to account for fixed navbar -->
    <div class="pt-16"></div>

    <!-- Page Header -->
    @hasSection('header')
    <div class="hero-gradient">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-white">@yield('header')</h1>
        </div>
    </div>
    @endif

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <i class="fas fa-couch text-2xl text-blue-400"></i>
                        <span class="text-xl font-bold">HomeNest</span>
                    </div>
                    <p class="text-gray-400">Quality furniture for your home since 2020.</p>
                </div>
                <div>
                    <h3 class="font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ url('/') }}#home" class="hover:text-white transition">Home</a></li>
                        <li><a href="{{ route('public.products.index') }}" class="hover:text-white transition">Products</a></li>
                        <li><a href="{{ url('/') }}#about" class="hover:text-white transition">About</a></li>
                        <li><a href="{{ url('/') }}#contact" class="hover:text-white transition">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold mb-4">Categories</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ url('/') }}#categories" class="hover:text-white transition">Living Room</a></li>
                        <li><a href="{{ url('/') }}#categories" class="hover:text-white transition">Bedroom</a></li>
                        <li><a href="{{ url('/') }}#categories" class="hover:text-white transition">Dining</a></li>
                        <li><a href="{{ url('/') }}#categories" class="hover:text-white transition">Office</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold mb-4">Follow Us</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white text-xl"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white text-xl"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white text-xl"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white text-xl"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} HomeNest Furniture. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        
        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                    if (mobileMenu) mobileMenu.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>
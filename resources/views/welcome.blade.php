<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HomeNest Furniture - Quality Furniture for Your Home</title>

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
                    <a href="#home" class="text-gray-700 hover:text-blue-600 transition">Home</a>
                    <a href="#products" class="text-gray-700 hover:text-blue-600 transition">Products</a>
                    <a href="#categories" class="text-gray-700 hover:text-blue-600 transition">Categories</a>
                    <a href="#about" class="text-gray-700 hover:text-blue-600 transition">About</a>
                    <a href="#contact" class="text-gray-700 hover:text-blue-600 transition">Contact</a>
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
                <a href="#home" class="block py-2 text-gray-700 hover:text-blue-600">Home</a>
                <a href="#products" class="block py-2 text-gray-700 hover:text-blue-600">Products</a>
                <a href="#categories" class="block py-2 text-gray-700 hover:text-blue-600">Categories</a>
                <a href="#about" class="block py-2 text-gray-700 hover:text-blue-600">About</a>
                <a href="#contact" class="block py-2 text-gray-700 hover:text-blue-600">Contact</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-gradient pt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="text-white">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">
                        Welcome to HomeNest
                    </h1>
                    <p class="text-lg md:text-xl mb-6 text-blue-100">
                        Discover quality furniture that transforms your house into a home. 
                        Modern designs, comfortable materials, and affordable prices.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#products" class="px-6 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-gray-100 transition">
                            Shop Now
                        </a>
                        <a href="#about" class="px-6 py-3 border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-blue-600 transition">
                            Learn More
                        </a>
                    </div>
                </div>
                <div class="hidden md:block">
                    <img src="https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=600&h=500&fit=crop" 
                         alt="Modern Living Room" 
                         class="rounded-lg shadow-xl">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Why Choose HomeNest?</h2>
                <p class="text-gray-600">We provide the best quality furniture with excellent service</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center feature-card transition-all">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-truck text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Free Delivery</h3>
                    <p class="text-gray-600">Free shipping on orders over $500</p>
                </div>
                <div class="text-center feature-card transition-all">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">2 Year Warranty</h3>
                    <p class="text-gray-600">All products come with warranty</p>
                </div>
                <div class="text-center feature-card transition-all">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">24/7 Support</h3>
                    <p class="text-gray-600">Customer support always ready to help</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Our Best Sellers</h2>
                <p class="text-gray-600">Discover our most popular furniture pieces</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Product 1 -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition">
                    <img src="https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=300&h=200&fit=crop" 
                         alt="Modern Sofa" 
                         class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800">Modern Fabric Sofa</h3>
                        <p class="text-sm text-gray-500 mb-2">Comfortable and stylish</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-blue-600">$499</span>
                            <button class="text-blue-600 hover:text-blue-700">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition">
                    <img src="https://images.unsplash.com/photo-1616486029423-aaa4789e8c9a?w=300&h=200&fit=crop" 
                         alt="Wooden Dining Table" 
                         class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800">Wooden Dining Table</h3>
                        <p class="text-sm text-gray-500 mb-2">6-seater family table</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-blue-600">$699</span>
                            <button class="text-blue-600 hover:text-blue-700">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition">
                    <img src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=300&h=200&fit=crop" 
                         alt="King Size Bed" 
                         class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800">King Size Bed</h3>
                        <p class="text-sm text-gray-500 mb-2">With storage drawers</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-blue-600">$899</span>
                            <button class="text-blue-600 hover:text-blue-700">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product 4 -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition">
                    <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=300&h=200&fit=crop" 
                         alt="Office Chair" 
                         class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800">Ergonomic Chair</h3>
                        <p class="text-sm text-gray-500 mb-2">For home office</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-blue-600">$249</span>
                            <button class="text-blue-600 hover:text-blue-700">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-8">
                <a href="#" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    View All Products
                </a>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section id="categories" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Shop by Category</h2>
                <p class="text-gray-600">Find exactly what you're looking for</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="relative overflow-hidden rounded-xl group cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=300&h=250&fit=crop" 
                         alt="Living Room" 
                         class="w-full h-64 object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <h3 class="text-2xl font-bold text-white">Living Room</h3>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-xl group cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=300&h=250&fit=crop" 
                         alt="Bedroom" 
                         class="w-full h-64 object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <h3 class="text-2xl font-bold text-white">Bedroom</h3>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-xl group cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1556912172-45b7abe8b7e1?w=300&h=250&fit=crop" 
                         alt="Dining" 
                         class="w-full h-64 object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <h3 class="text-2xl font-bold text-white">Dining</h3>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-xl group cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?w=300&h=250&fit=crop" 
                         alt="Office" 
                         class="w-full h-64 object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <h3 class="text-2xl font-bold text-white">Office</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <img src="https://images.unsplash.com/photo-1524758631624-e2822e304c36?w=600&h=400&fit=crop" 
                         alt="About Us" 
                         class="rounded-lg shadow-lg">
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">About HomeNest</h2>
                    <p class="text-gray-600 mb-4">
                        Founded in 2020, HomeNest has grown to become one of the leading furniture 
                        retailers in the region. We pride ourselves on offering high-quality, 
                        stylish furniture at affordable prices.
                    </p>
                    <p class="text-gray-600 mb-6">
                        Our mission is to help you create the perfect home environment with 
                        furniture that combines comfort, style, and durability.
                    </p>
                    <div class="flex items-center space-x-6">
                        <div>
                            <p class="text-2xl font-bold text-blue-600">5000+</p>
                            <p class="text-sm text-gray-500">Happy Customers</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-blue-600">1000+</p>
                            <p class="text-sm text-gray-500">Products Sold</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-blue-600">50+</p>
                            <p class="text-sm text-gray-500">Brand Partners</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">What Our Customers Say</h2>
                <p class="text-gray-600">Trusted by thousands of happy customers</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <div class="flex text-yellow-400 mb-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-600 mb-4">"Excellent quality furniture! The delivery was fast and the customer service was very helpful."</p>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Sarah Johnson</p>
                            <p class="text-sm text-gray-500">Verified Buyer</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <div class="flex text-yellow-400 mb-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-600 mb-4">"Beautiful modern designs. The sofa I bought is very comfortable and looks great in my living room."</p>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Michael Chen</p>
                            <p class="text-sm text-gray-500">Verified Buyer</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <div class="flex text-yellow-400 mb-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-600 mb-4">"Great selection of furniture. The bed frame is sturdy and the assembly instructions were easy to follow."</p>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Emily Davis</p>
                            <p class="text-sm text-gray-500">Verified Buyer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Get In Touch</h2>
                    <p class="text-gray-600 mb-6">Have questions? We'd love to hear from you.</p>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-blue-600 w-8"></i>
                            <span>123 Furniture Street, Mandalay, Myanmar</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone text-blue-600 w-8"></i>
                            <span>+95 9 259 820 422</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-blue-600 w-8"></i>
                            <span>info@homenest.com</span>
                        </div>
                    </div>
                </div>
                <div>
                    <form class="space-y-4">
                        <div>
                            <input type="text" placeholder="Your Name" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <input type="email" placeholder="Your Email" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <textarea rows="4" placeholder="Your Message" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-16 hero-gradient">
        <div class="max-w-4xl mx-auto text-center px-4">
            <h2 class="text-3xl font-bold text-white mb-4">Subscribe to Our Newsletter</h2>
            <p class="text-blue-100 mb-6">Get the latest updates on new products and exclusive offers</p>
            <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                <input type="email" placeholder="Your email address" 
                       class="flex-1 px-4 py-2 rounded-lg focus:outline-none">
                <button class="px-6 py-2 bg-white text-blue-600 font-semibold rounded-lg hover:bg-gray-100 transition">
                    Subscribe
                </button>
            </div>
        </div>
    </section>

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
                        <li><a href="#home" class="hover:text-white transition">Home</a></li>
                        <li><a href="#products" class="hover:text-white transition">Products</a></li>
                        <li><a href="#about" class="hover:text-white transition">About</a></li>
                        <li><a href="#contact" class="hover:text-white transition">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold mb-4">Categories</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Living Room</a></li>
                        <li><a href="#" class="hover:text-white transition">Bedroom</a></li>
                        <li><a href="#" class="hover:text-white transition">Dining</a></li>
                        <li><a href="#" class="hover:text-white transition">Office</a></li>
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
                <p>&copy; 2024 HomeNest Furniture. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                    mobileMenu.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>
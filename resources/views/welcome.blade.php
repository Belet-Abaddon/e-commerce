<?php
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Brand;
use App\Models\Feedback;

// Get featured products (limit 4)
$featuredProducts = Product::with(['brand', 'productType', 'images'])
    ->where('status', 'active')
    ->latest()
    ->take(4)
    ->get();

// Get categories with product counts
$categories = ProductType::withCount('products')->take(4)->get();

// Get brands with product counts
$brands = Brand::withCount('products')->take(4)->get();

// Get recent testimonials
$testimonials = Feedback::with('user')
    ->latest()
    ->take(3)
    ->get();

// Get product statistics
$totalProducts = Product::count();
$totalOrders = \App\Models\Order::count();
$totalCustomers = \App\Models\User::count();
?>

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
                    <a href="#brands" class="text-gray-700 hover:text-blue-600 transition">Brands</a>
                    <a href="#about" class="text-gray-700 hover:text-blue-600 transition">About</a>
                    <a href="#contact" class="text-gray-700 hover:text-blue-600 transition">Contact</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                    Dashboard
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition">Login</a>
                            <a href="{{ route('register') }}"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
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
                <a href="#brands" class="block py-2 text-gray-700 hover:text-blue-600">Brands</a>
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
                        <a href="{{ route('public.products.index') }}"
                            class="px-6 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-gray-100 transition">
                            Shop Now
                        </a>
                        <a href="#about"
                            class="px-6 py-3 border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-blue-600 transition">
                            Learn More
                        </a>
                    </div>
                </div>
                <div class="hidden md:block">
                    <img src="https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=600&h=500&fit=crop"
                        alt="Modern Living Room" class="rounded-lg shadow-xl">
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div>
                    <div class="text-3xl font-bold text-blue-600">{{ number_format($totalProducts) }}+</div>
                    <div class="text-sm text-gray-500">Products</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-blue-600">{{ number_format($totalOrders) }}+</div>
                    <div class="text-sm text-gray-500">Orders Completed</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-blue-600">{{ number_format($totalCustomers) }}+</div>
                    <div class="text-sm text-gray-500">Happy Customers</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-blue-600">{{ number_format($brands->count()) }}+</div>
                    <div class="text-sm text-gray-500">Trusted Brands</div>
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
                @forelse($featuredProducts as $product)
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition group">
                        <a href="{{ route('user.products.show', $product->id) }}" class="block">
                            @if($product->images && $product->images->first())
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-couch text-gray-300 text-4xl"></i>
                                </div>
                            @endif
                        </a>
                        <div class="p-4">
                            <p class="text-xs text-gray-500">{{ $product->brand->name ?? 'HomeNest' }}</p>
                            <h3 class="font-semibold text-gray-800 mt-1">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-500 mb-2">{{ Str::limit($product->description, 50) }}</p>
                            <div class="flex justify-between items-center">
                                <span
                                    class="text-xl font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                                <a href="{{ route('user.products.show', $product->id) }}"
                                    class="text-blue-600 hover:text-blue-700">
                                    <i class="fas fa-shopping-cart"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-12">
                        <p class="text-gray-500">No products available</p>
                    </div>
                @endforelse
            </div>
            <div class="text-center mt-8">
                <a href="{{ route('public.products.index') }}"
                    class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
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
                @forelse($categories as $category)
                    <a href="{{ route('public.products.index', ['product_type_id' => $category->id]) }}"
                        class="relative overflow-hidden rounded-xl group cursor-pointer block">
                        <!-- Background gradient with random colors -->
                        <div class="absolute inset-0 bg-gradient-to-br 
                                    @if($loop->index % 4 == 0) from-blue-500 to-blue-600
                                    @elseif($loop->index % 4 == 1) from-purple-500 to-purple-600
                                    @elseif($loop->index % 4 == 2) from-green-500 to-green-600
                                    @else from-orange-500 to-orange-600
                                    @endif">
                        </div>
                        <div class="relative p-8 text-center text-white">
                            <div class="mb-4">
                                @if($loop->index % 4 == 0)
                                    <i class="fas fa-couch text-4xl"></i>
                                @elseif($loop->index % 4 == 1)
                                    <i class="fas fa-bed text-4xl"></i>
                                @elseif($loop->index % 4 == 2)
                                    <i class="fas fa-utensils text-4xl"></i>
                                @else
                                    <i class="fas fa-chair text-4xl"></i>
                                @endif
                            </div>
                            <h3 class="text-2xl font-bold mb-2">{{ $category->name }}</h3>
                            <p class="text-sm opacity-90">{{ $category->products_count ?? 0 }} products</p>
                            @if($category->description)
                                <p class="text-xs mt-2 opacity-75">{{ Str::limit($category->description, 60) }}</p>
                            @endif
                        </div>
                        <!-- Hover overlay -->
                        <div
                            class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition-opacity duration-300">
                        </div>
                    </a>
                @empty
                    <div class="col-span-4 text-center py-12 bg-white rounded-xl">
                        <i class="fas fa-tags text-5xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No categories available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Brands Section -->
    <section id="brands" class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Our Trusted Brands</h2>
                <p class="text-gray-600">We partner with the best furniture brands</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @forelse($brands as $brand)
                    <div class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition">
                        @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}"
                                class="h-16 mx-auto mb-3 object-contain">
                        @else
                            <i class="fas fa-trademark text-4xl text-blue-600 mb-3"></i>
                        @endif
                        <h3 class="font-semibold text-gray-800">{{ $brand->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $brand->products_count }} products</p>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-12">
                        <p class="text-gray-500">No brands available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <img src="https://images.unsplash.com/photo-1524758631624-e2822e304c36?w=600&h=400&fit=crop"
                        alt="About Us" class="rounded-lg shadow-lg">
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
                            <p class="text-2xl font-bold text-blue-600">{{ number_format($totalCustomers) }}+</p>
                            <p class="text-sm text-gray-500">Happy Customers</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-blue-600">{{ number_format($totalProducts) }}+</p>
                            <p class="text-sm text-gray-500">Products</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-blue-600">{{ number_format($brands->count()) }}+</p>
                            <p class="text-sm text-gray-500">Brand Partners</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">What Our Customers Say</h2>
                <p class="text-gray-600">Trusted by thousands of happy customers</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                @forelse($testimonials as $testimonial)
                    <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition">
                        <div class="flex text-yellow-400 mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                <i
                                    class="fas fa-star {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                        <p class="text-gray-600 mb-4">"{{ Str::limit($testimonial->feedback, 120) }}"</p>
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold">{{ $testimonial->user->name }}</p>
                                <p class="text-sm text-gray-500">Verified Buyer</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <p class="text-gray-500">No testimonials available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-16 bg-gray-50">
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
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
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
                        @foreach($categories as $category)
                            <li><a href="{{ route('user.products.index', ['product_type_id' => $category->id]) }}"
                                    class="hover:text-white transition">{{ $category->name }}</a></li>
                        @endforeach
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
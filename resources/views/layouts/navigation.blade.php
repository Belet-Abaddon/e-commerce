<nav x-data="{ open: false }" class="bg-white shadow-md border-b border-blue-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <i class="fas fa-couch text-2xl text-blue-600"></i>
                        <span class="text-xl font-bold text-blue-800">HomeNest</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-700 hover:text-blue-600 hover:border-blue-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        {{ __('Dashboard') }}
                    </a>

                    <a href="#"
                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('user.orders*') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-700 hover:text-blue-600 hover:border-blue-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        {{ __('My Orders') }}
                    </a>

                    <a href="{{ route('user.products.index') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('user.products*') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-700 hover:text-blue-600 hover:border-blue-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                        <i class="fas fa-tag mr-2"></i>
                        {{ __('Products') }}
                    </a>

                    <a href="#"
                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('user.feedback*') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-700 hover:text-blue-600 hover:border-blue-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                        <i class="fas fa-star mr-2"></i>
                        {{ __('Feedbacks') }}
                    </a>
                    <a href="{{ route('user.cart.index') }}"
                        class="relative inline-flex items-center px-1 pt-1 text-gray-700 hover:text-blue-600 text-sm font-medium">
                        <i class="fas fa-shopping-basket text-lg mr-1"></i> Cart Bundle
                        @if (session()->has('cart') && count(session()->get('cart')) > 0)
                            <span
                                class="absolute -top-1 -right-3 bg-red-500 text-white text-[9px] font-black w-4 h-4 rounded-full flex items-center justify-center animate-bounce">
                                {{ count(session()->get('cart')) }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="ml-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 focus:outline-none transition duration-150 ease-in-out">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-2">
                                    <i class="fas fa-user text-blue-600 text-sm"></i>
                                </div>
                                <span>{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')" class="text-gray-700 hover:text-blue-600">
                                <i class="fas fa-user-circle mr-2"></i>
                                {{ __('My Profile') }}
                            </x-dropdown-link>

                            <div class="border-t border-gray-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-red-600 hover:text-red-700">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}"
                class="block w-full pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('dashboard') ? 'border-blue-600 text-blue-600 bg-blue-50' : 'border-transparent text-gray-700 hover:text-blue-600 hover:border-blue-300' }} text-left text-base font-medium transition duration-150 ease-in-out">
                <i class="fas fa-tachometer-alt mr-2"></i>
                {{ __('Dashboard') }}
            </a>

            <a href="#"
                class="block w-full pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('user.orders*') ? 'border-blue-600 text-blue-600 bg-blue-50' : 'border-transparent text-gray-700 hover:text-blue-600 hover:border-blue-300' }} text-left text-base font-medium transition duration-150 ease-in-out">
                <i class="fas fa-shopping-cart mr-2"></i>
                {{ __('My Orders') }}
            </a>

            <a href="{{ route('user.products.index') }}"
                class="block w-full pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('user.products*') ? 'border-blue-600 text-blue-600 bg-blue-50' : 'border-transparent text-gray-700 hover:text-blue-600 hover:border-blue-300' }} text-left text-base font-medium transition duration-150 ease-in-out">
                <i class="fas fa-tag mr-2"></i>
                {{ __('Products') }}
            </a>

            <a href="#"
                class="block w-full pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('user.feedback*') ? 'border-blue-600 text-blue-600 bg-blue-50' : 'border-transparent text-gray-700 hover:text-blue-600 hover:border-blue-300' }} text-left text-base font-medium transition duration-150 ease-in-out">
                <i class="fas fa-star mr-2"></i>
                {{ __('Feedbacks') }}
            </a>

            

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}"
                    class="block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-700 hover:text-blue-600 hover:border-blue-300 transition duration-150 ease-in-out">
                    <i class="fas fa-user-circle mr-2"></i>
                    {{ __('My Profile') }}
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                        class="block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-red-600 hover:text-red-700 hover:border-red-300 transition duration-150 ease-in-out">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        {{ __('Log Out') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>

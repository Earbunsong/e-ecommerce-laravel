<header>
    {{-- Top Bar --}}
    <div class="bg-blue-900 text-white text-sm">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center py-2 px-4">
            <div class="flex flex-wrap gap-4 items-center">
                <span><i class="fas fa-phone-alt mr-1"></i> 012 220 152</span>
                <span><i class="fas fa-phone-alt mr-1"></i> 093 456 747</span>
                <span><i class="fas fa-phone-alt mr-1"></i> 071 5777 378</span>
                <span><i class="fas fa-truck mr-1"></i> Delivery within 2 hours / 25 Provinces</span>
            </div>
            <div class="flex gap-4 mt-2 md:mt-0">
                <a href="#" class="hover:text-amber-400"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="hover:text-amber-400"><i class="fab fa-tiktok"></i></a>
                <a href="#" class="hover:text-amber-400"><i class="fab fa-instagram"></i></a>
                <a href="#" class="hover:text-amber-400"><i class="fab fa-telegram"></i></a>
                <a href="#" class="hover:text-amber-400"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>

    {{-- Middle Bar --}}
    <div class="bg-white shadow-sm">
        <div class="container mx-auto flex flex-wrap md:flex-nowrap items-center justify-between px-4 py-3">
            {{-- Logo --}}
            <div class="flex items-center">
                <img src="{{ asset('images/footers/logo.png') }}" alt="Logo" class="h-12 mr-3">
                <div>
                    <p class="text-xs text-gray-600">Hello</p>
                    <p class="text-sm font-semibold">Choose your location</p>
                </div>
            </div>

            {{-- Search Bar --}}
            <form action="{{ url('/search') }}" method="GET" class="flex-grow max-w-2xl mx-4 relative">
                <input type="text" name="q" placeholder="Search for products..."
                       class="w-full py-2 pl-4 pr-24 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                <button type="submit"
                        class="absolute right-0 top-0 h-full px-6 bg-amber-500 hover:bg-amber-600 text-white rounded-r-lg">
                    Search
                </button>
            </form>

            {{-- Icons --}}
            <div class="flex items-center gap-6">
                <a href="{{ url('/compare') }}" class="relative text-gray-700 hover:text-amber-500">
                    <i class="fas fa-balance-scale text-xl"></i>
                </a>
                <a href="{{ url('/wishlist') }}" class="relative text-gray-700 hover:text-amber-500">
                    <i class="fas fa-heart text-xl"></i>
                </a>
                <a href="{{ url('/cart') }}" class="relative text-gray-700 hover:text-amber-500">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute -top-2 -right-3 bg-red-500 text-white rounded-full text-xs px-1">
                            {{ count(session('cart')) }}
                        </span>
                    @endif
                </a>
                @auth
                    <a href="{{ url('/account') }}" class="text-gray-700 hover:text-amber-500">
                        <i class="fas fa-user text-xl"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-amber-500">
                        <i class="fas fa-sign-in-alt text-xl"></i>
                    </a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Nav Bar --}}
    <nav class="bg-white border-t border-b">
        <div class="container mx-auto px-4 flex flex-wrap md:flex-nowrap items-center justify-between">
            <ul class="flex flex-wrap space-x-6 py-3 text-gray-700 font-medium">
                <li><a href="{{ url('/') }}" class="hover:text-amber-500">Home</a></li>
                <li><a href="{{ url('/special-offer') }}" class="hover:text-amber-500 relative">
                        <span class="px-2 py-1 rounded bg-gradient-to-r from-pink-500 to-orange-500 text-white text-xs">Special Offer</span>
                    </a></li>
                <li class="group relative">
                    <a href="#" class="hover:text-amber-500">Shop <i class="fas fa-chevron-down text-xs"></i></a>
                    <ul class="absolute hidden group-hover:block bg-white shadow-md rounded mt-2 w-40">
                        <li><a href="{{ url('/shop/laptops') }}" class="block px-4 py-2 hover:bg-gray-100">Laptops</a></li>
                        <li><a href="{{ url('/shop/desktops') }}" class="block px-4 py-2 hover:bg-gray-100">Desktops</a></li>
                        <li><a href="{{ url('/shop/accessories') }}" class="block px-4 py-2 hover:bg-gray-100">Accessories</a></li>
                    </ul>
                </li>
                <li><a href="{{ url('/brands') }}" class="hover:text-amber-500">Brands</a></li>
                <li><a href="{{ url('/customer-care') }}" class="hover:text-amber-500">Customer Care</a></li>
                <li><a href="{{ url('/track-order') }}" class="hover:text-amber-500">Track Info</a></li>
                <li><a href="{{ url('/about') }}" class="hover:text-amber-500">About</a></li>
                <li><a href="{{ url('/contact') }}" class="hover:text-amber-500">Contact Us</a></li>
            </ul>
        </div>
    </nav>
</header>

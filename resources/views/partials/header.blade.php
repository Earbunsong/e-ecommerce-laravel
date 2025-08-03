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
                <a href="{{ route('home') }}" class="flex items-center">
                    <div class="h-12 w-12 bg-blue-900 rounded-lg flex items-center justify-center text-white font-bold text-xl mr-3">
                        K2
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Hello</p>
                        <p class="text-sm font-semibold">Choose your location</p>
                    </div>
                </a>
            </div>

            {{-- Search Bar --}}
            <form action="{{ route('products.search') }}" method="GET" class="flex-grow max-w-2xl mx-4 relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search for products..."
                       class="w-full py-2 pl-4 pr-24 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                <button type="submit"
                        class="absolute right-0 top-0 h-full px-6 bg-amber-500 hover:bg-amber-600 text-white rounded-r-lg transition-colors">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            {{-- Icons --}}
            <div class="flex items-center gap-6">
                <a href="#" class="relative text-gray-700 hover:text-amber-500 transition-colors">
                    <i class="fas fa-balance-scale text-xl"></i>
                </a>
                <a href="{{ route('wishlist.index') }}" class="relative text-gray-700 hover:text-amber-500 transition-colors">
                    <i class="fas fa-heart text-xl"></i>
                    @php $wishlistCount = session('wishlist') ? count(session('wishlist')) : 0; @endphp
                    @if($wishlistCount > 0)
                        <span id="wishlistCount" class="absolute -top-2 -right-3 bg-red-500 text-white rounded-full text-xs px-1 min-w-[20px] h-5 flex items-center justify-center">
                            {{ $wishlistCount }}
                        </span>
                    @else
                        <span id="wishlistCount" class="absolute -top-2 -right-3 bg-red-500 text-white rounded-full text-xs px-1 min-w-[20px] h-5 flex items-center justify-center hidden">0</span>
                    @endif
                </a>
                <a href="{{ route('cart.index') }}" class="relative text-gray-700 hover:text-amber-500 transition-colors">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    @php $cartCount = session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0; @endphp
                    @if($cartCount > 0)
                        <span id="cartCount" class="absolute -top-2 -right-3 bg-red-500 text-white rounded-full text-xs px-1 min-w-[20px] h-5 flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                    @else
                        <span id="cartCount" class="absolute -top-2 -right-3 bg-red-500 text-white rounded-full text-xs px-1 min-w-[20px] h-5 flex items-center justify-center hidden">0</span>
                    @endif
                </a>

                @auth
                    <a href="#" class="text-gray-700 hover:text-amber-500 transition-colors">
                        <i class="fas fa-user text-xl"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-amber-500 transition-colors">
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
                <li>
                    <a href="{{ route('home') }}" class="hover:text-amber-500 transition-colors {{ request()->routeIs('home') ? 'text-amber-500 font-semibold' : '' }}">
                        Home
                    </a>
                </li>
                <li>
                    <a href="#" class="hover:text-amber-500 relative">
                        <span class="px-2 py-1 rounded bg-gradient-to-r from-pink-500 to-orange-500 text-white text-xs font-semibold">Special Offer</span>
                    </a>
                </li>
                <li class="group relative">
                    <a href="{{ route('products.index') }}" class="hover:text-amber-500 transition-colors {{ request()->routeIs('products.*') ? 'text-amber-500 font-semibold' : '' }}">
                        Shop <i class="fas fa-chevron-down text-xs ml-1"></i>
                    </a>
                    <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-lg mt-2 w-48 z-50 border">
                        <li><a href="{{ route('products.index', ['category' => 'Laptops']) }}" class="block px-4 py-3 hover:bg-gray-100 transition-colors">Laptops</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'Desktops']) }}" class="block px-4 py-3 hover:bg-gray-100 transition-colors">Desktops</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'Accessories']) }}" class="block px-4 py-3 hover:bg-gray-100 transition-colors">Accessories</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'Monitors']) }}" class="block px-4 py-3 hover:bg-gray-100 transition-colors">Monitors</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'Printers']) }}" class="block px-4 py-3 hover:bg-gray-100 transition-colors">Printers</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'Networking']) }}" class="block px-4 py-3 hover:bg-gray-100 transition-colors">Networking</a></li>
                    </ul>
                </li>
                <li><a href="#" class="hover:text-amber-500 transition-colors">Brands</a></li>
                <li><a href="#" class="hover:text-amber-500 transition-colors">Customer Care</a></li>
                <li><a href="#" class="hover:text-amber-500 transition-colors">Track Info</a></li>
                <li><a href="#" class="hover:text-amber-500 transition-colors">About</a></li>
                <li><a href="#" class="hover:text-amber-500 transition-colors">Contact Us</a></li>
            </ul>
        </div>
    </nav>
</header>

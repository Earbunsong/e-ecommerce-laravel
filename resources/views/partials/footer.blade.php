<footer class="bg-white border-t mt-10">
    {{-- Top Brands Row --}}
    <div class="py-4 border-b">
        <div class="container mx-auto px-4 flex flex-wrap justify-center gap-6">
            @foreach (['Dell','Lenovo','ASUS','HP','Microsoft','Canon','Samsung','TP-Link','Logitech'] as $brand)
                <div class="h-10 w-20 bg-gray-100 rounded flex items-center justify-center text-gray-600 text-sm font-medium brand-logo">
                    {{ $brand }}
                </div>
            @endforeach
        </div>
    </div>

    {{-- Main Footer Content --}}
    <div class="container mx-auto px-4 py-8 grid grid-cols-1 md:grid-cols-5 gap-8">
        {{-- Logo & Opening Hours --}}
        <div class="col-span-1 md:col-span-1">
            <div class="h-14 w-14 bg-blue-900 rounded-lg flex items-center justify-center text-white font-bold text-xl mb-4">
                K2
            </div>
            <p class="text-gray-700 text-sm font-semibold">Opening Hours:</p>
            <p class="text-gray-600 text-sm">Monday to Sunday</p>
            <p class="text-gray-600 text-sm">8:00 AM to 5:30 PM</p>
        </div>

        {{-- Contact Info --}}
        <div>
            <h4 class="font-semibold mb-3">CONTACT US</h4>
            <p class="text-sm font-semibold">Sales Showroom</p>
            <ul class="text-gray-600 text-sm space-y-1 mb-3">
                <li><i class="fas fa-phone mr-2 text-amber-500"></i> 012 220 152</li>
                <li><i class="fas fa-phone mr-2 text-amber-500"></i> 093 456 747</li>
                <li><i class="fas fa-phone mr-2 text-amber-500"></i> 071 5777 378</li>
            </ul>
            <p class="text-sm font-semibold">Service Center</p>
            <ul class="text-gray-600 text-sm space-y-1">
                <li><i class="fas fa-phone mr-2 text-amber-500"></i> 012 220 135</li>
                <li><i class="fas fa-phone mr-2 text-amber-500"></i> 093 456 735</li>
                <li><i class="fas fa-phone mr-2 text-amber-500"></i> 031 3220 135</li>
            </ul>
        </div>

        {{-- Conditions --}}
        <div>
            <h4 class="font-semibold mb-3">CONDITIONS APPLY</h4>
            <ul class="text-gray-600 text-sm space-y-2">
                <li><a href="#" class="hover:text-amber-500 transition-colors">Terms & Conditions</a></li>
                <li><a href="#" class="hover:text-amber-500 transition-colors">Privacy Policy</a></li>
                <li><a href="#" class="hover:text-amber-500 transition-colors">About Us</a></li>
                <li><a href="#" class="hover:text-amber-500 transition-colors">Help</a></li>
            </ul>
        </div>

        {{-- My Account --}}
        <div>
            <h4 class="font-semibold mb-3">MY ACCOUNT</h4>
            <ul class="text-gray-600 text-sm space-y-2">
                <li><a href="{{ route('login') }}" class="hover:text-amber-500 transition-colors">Login</a></li>
                <li><a href="#" class="hover:text-amber-500 transition-colors">Order History</a></li>
                <li><a href="{{ route('wishlist.index') }}" class="hover:text-amber-500 transition-colors">My Wishlist</a></li>
                <li><a href="#" class="hover:text-amber-500 transition-colors">Track Order</a></li>
            </ul>
        </div>

        {{-- App QR & Buttons --}}
        <div class="text-center">
            <button class="bg-gradient-to-r from-red-500 to-green-500 text-white px-4 py-2 rounded-full shadow hover:scale-105 transition-transform">
                Download Our APP
            </button>
            <div class="flex justify-center space-x-2 mt-4">
                <div class="h-10 w-20 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-600">Play Store</div>
                <div class="h-10 w-20 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-600">App Store</div>
            </div>
            <div class="flex justify-center space-x-4 mt-4">
                <div class="h-20 w-20 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-600">QR Google</div>
                <div class="h-20 w-20 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-600">QR iOS</div>
            </div>
        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="bg-blue-900 text-white py-3">
        <div class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center">
            {{-- Social Icons --}}
            <div class="flex space-x-4 mb-3 md:mb-0">
                <a href="#" class="hover:text-amber-400 transition-colors"><i class="fab fa-facebook h-5 w-5"></i></a>
                <a href="#" class="hover:text-amber-400 transition-colors"><i class="fab fa-tiktok h-5 w-5"></i></a>
                <a href="#" class="hover:text-amber-400 transition-colors"><i class="fab fa-instagram h-5 w-5"></i></a>
                <a href="#" class="hover:text-amber-400 transition-colors"><i class="fab fa-telegram h-5 w-5"></i></a>
                <a href="#" class="hover:text-amber-400 transition-colors"><i class="fab fa-youtube h-5 w-5"></i></a>
            </div>
            {{-- Payment Logos --}}
            <div class="flex flex-wrap gap-3 items-center">
                <span class="text-sm mr-3">We Accept:</span>
                <div class="h-6 w-16 bg-white rounded text-black text-xs flex items-center justify-center font-semibold">KHQR</div>
                <div class="h-6 w-16 bg-white rounded text-black text-xs flex items-center justify-center font-semibold">VISA</div>
                <div class="h-6 w-16 bg-white rounded text-black text-xs flex items-center justify-center font-semibold">MASTER</div>
            </div>
        </div>
    </div>
</footer>

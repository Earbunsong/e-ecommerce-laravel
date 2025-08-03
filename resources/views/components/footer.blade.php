<footer class="bg-white border-t mt-10">
    {{-- Top Brands Row --}}
    <div class="py-4 border-b">
        <div class="container mx-auto px-4 flex flex-wrap justify-center gap-6">
            @foreach (['dell_logo.png','Lenovo.png','Asus.png','HP.png','Microsoft.png','dell_logo.png','Microsoft.png','dell_logo.png','dell_logo.png','dell_logo.png','dell_logo.png','dell_logo.png','dell_logo.png'] as $brand)
                <img src="{{ asset('images/brands/'.$brand) }}" alt="Brand" class="h-10 object-contain">
            @endforeach
        </div>
    </div>

    {{-- Main Footer Content --}}
    <div class="container mx-auto px-4 py-8 grid grid-cols-1 md:grid-cols-5 gap-8">
        {{-- Logo & Opening Hours --}}
        <div class="col-span-1 md:col-span-1">
            <img src="{{ asset('images/footers/logo.png') }}" alt="K2 Computer" class="h-14 mb-4">
            <p class="text-gray-700 text-sm font-semibold">Opening Hours:</p>
            <p class="text-gray-600 text-sm">Monday to Sunday, 8:00 AM to 5:30 PM</p>
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
                <li><a href="{{ url('/terms') }}" class="hover:text-amber-500">Terms & Conditions</a></li>
                <li><a href="{{ url('/privacy') }}" class="hover:text-amber-500">Privacy Policy</a></li>
                <li><a href="{{ url('/about') }}" class="hover:text-amber-500">About Us</a></li>
                <li><a href="{{ url('/help') }}" class="hover:text-amber-500">Help</a></li>
            </ul>
        </div>

        {{-- My Account --}}
        <div>
            <h4 class="font-semibold mb-3">MY ACCOUNT</h4>
            <ul class="text-gray-600 text-sm space-y-2">
                <li><a href="{{ route('login') }}" class="hover:text-amber-500">Login</a></li>
                <li><a href="{{ url('/orders') }}" class="hover:text-amber-500">Order History</a></li>
                <li><a href="{{ url('/wishlist') }}" class="hover:text-amber-500">My Wishlist</a></li>
                <li><a href="{{ url('/track-order') }}" class="hover:text-amber-500">Track Order</a></li>
            </ul>
        </div>

        {{-- App QR & Buttons --}}
        <div class="text-center">
            <button class="bg-gradient-to-r from-red-500 to-green-500 text-white px-4 py-2 rounded-full shadow hover:scale-105 transition">Download Our APP</button>
            <div class="flex justify-center space-x-2 mt-4">
                <img src="{{ asset('images/footers/ScanAppPlayStore.png') }}" alt="Google Play" class="h-10">
                <img src="{{ asset('images/ScanAppStore.png') }}" alt="App Store" class="h-10">
            </div>
            <div class="flex justify-center space-x-4 mt-4">
                <img src="{{ asset('images/qrcode-google.png') }}" alt="QR Google" class="h-20">
                <img src="{{ asset('images/qrcode-ios.png') }}" alt="QR iOS" class="h-20">
            </div>
        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="bg-blue-900 text-white py-3">
        <div class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center">
            {{-- Social Icons --}}
            <div class="flex space-x-4 mb-3 md:mb-0">
                <a href="#" class="hover:text-amber-400"><i class="fab fa-facebook h-5 w-5"></i></a>
                <a href="#" class="hover:text-amber-400"><i class="fab fa-tiktok h-5 w-5"></i></a>
                <a href="#" class="hover:text-amber-400"><i class="fab fa-instagram h-5 w-5"></i></a>
                <a href="#" class="hover:text-amber-400"><i class="fab fa-telegram h-5 w-5"></i></a>
                <a href="#" class="hover:text-amber-400"><i class="fab fa-youtube h-5 w-5"></i></a>
            </div>
            {{-- Payment Logos --}}
            <div class="flex flex-wrap gap-3 items-center">
                <span class="text-sm mr-3">We Accept:</span>
                @foreach (['khqr.png'] as $pay)
                    <img src="{{ asset('images/payments/'.$pay) }}" alt="Payment" class="h-6">
                @endforeach
            </div>
        </div>
    </div>
</footer>

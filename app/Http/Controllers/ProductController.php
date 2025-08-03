<?php
// app/Http/Controllers/ProductController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $allProducts = collect($this->getAllProducts());
        $categories = $this->getCategories(); // This method was missing - now added below
        $brands = $allProducts->pluck('brand')->unique()->sort()->values();

        // Apply filters
        $products = $allProducts;

        if ($request->category && $request->category !== 'all') {
            $products = $products->where('category', $request->category);
        }

        if ($request->brand && !empty($request->brand)) {
            $selectedBrands = is_array($request->brand) ? $request->brand : [$request->brand];
            $products = $products->whereIn('brand', $selectedBrands);
        }

        if ($request->price_range) {
            $products = $this->filterByPriceRange($products, $request->price_range);
        }

        if ($request->q) {
            $query = strtolower(trim($request->q));
            $products = $products->filter(function ($product) use ($query) {
                return str_contains(strtolower($product['name']), $query) ||
                    str_contains(strtolower($product['category']), $query) ||
                    str_contains(strtolower($product['brand']), $query) ||
                    str_contains(strtolower($product['description'] ?? ''), $query);
            });
        }

        // Apply sorting
        if ($request->sort) {
            $products = $this->sortProducts($products, $request->sort);
        }

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    public function show($id)
    {
        $product = collect($this->getAllProducts())->firstWhere('id', (int)$id);

        if (!$product) {
            abort(404, 'Product not found');
        }

        $relatedProducts = collect($this->getAllProducts())
            ->where('category', $product['category'])
            ->where('id', '!=', $product['id'])
            ->take(4);

        $isWishlisted = in_array($id, session('wishlist', []));

        return view('products.show', compact('product', 'relatedProducts', 'isWishlisted'));
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }

    // FIXED: Added the missing getCategories method
    private function getCategories()
    {
        return [
            ['id' => 1, 'name' => 'Laptops', 'count' => 15, 'image' => 'categories/laptops.jpg'],
            ['id' => 2, 'name' => 'Desktops', 'count' => 12, 'image' => 'categories/desktops.jpg'],
            ['id' => 3, 'name' => 'Accessories', 'count' => 25, 'image' => 'categories/accessories.jpg'],
            ['id' => 4, 'name' => 'Monitors', 'count' => 18, 'image' => 'categories/monitors.jpg'],
            ['id' => 5, 'name' => 'Printers', 'count' => 8, 'image' => 'categories/printers.jpg'],
            ['id' => 6, 'name' => 'Networking', 'count' => 14, 'image' => 'categories/networking.jpg'],
        ];
    }

    private function filterByPriceRange($products, $range)
    {
        switch ($range) {
            case '0-100':
                return $products->where('price', '<=', 100);
            case '100-500':
                return $products->where('price', '>', 100)->where('price', '<=', 500);
            case '500-1000':
                return $products->where('price', '>', 500)->where('price', '<=', 1000);
            case '1000+':
                return $products->where('price', '>', 1000);
            default:
                return $products;
        }
    }

    private function sortProducts($products, $sort)
    {
        switch ($sort) {
            case 'price-low':
                return $products->sortBy('price')->values();
            case 'price-high':
                return $products->sortByDesc('price')->values();
            case 'name':
                return $products->sortBy('name')->values();
            case 'rating':
                return $products->sortByDesc('rating')->values();
            case 'newest':
                return $products->sortByDesc('id')->values();
            default:
                return $products->values();
        }
    }

    // FIXED: Added complete getAllProducts method
    private function getAllProducts()
    {
        return [
            [
                'id' => 1,
                'name' => 'Dell Inspiron 15 3000 Laptop',
                'price' => 699.99,
                'original_price' => 799.99,
                'discount' => 13,
                'image' => 'products/dell-inspiron-15.jpg',
                'category' => 'Laptops',
                'brand' => 'Dell',
                'rating' => 4,
                'review_count' => 128,
                'in_stock' => true,
                'stock_quantity' => 15,
                'specs' => [
                    'Intel Core i5-1135G7 Processor',
                    '8GB DDR4 RAM',
                    '256GB PCIe NVMe SSD',
                    '15.6" Full HD Display',
                    'Intel Iris Xe Graphics',
                    'Windows 11 Home'
                ],
                'description' => 'Perfect laptop for everyday computing with reliable performance and modern features.',
                'sku' => 'DELL-INSP15-001',
                'colors' => ['Black', 'Silver'],
                'storage_options' => ['256GB', '512GB', '1TB']
            ],
            [
                'id' => 2,
                'name' => 'HP Pavilion Gaming Desktop',
                'price' => 899.99,
                'original_price' => 1099.99,
                'discount' => 18,
                'image' => 'products/hp-pavilion-desktop.jpg',
                'category' => 'Desktops',
                'brand' => 'HP',
                'rating' => 5,
                'review_count' => 89,
                'in_stock' => true,
                'stock_quantity' => 8,
                'specs' => [
                    'AMD Ryzen 5 5600G Processor',
                    '16GB DDR4 RAM',
                    '512GB PCIe NVMe SSD',
                    'NVIDIA GTX 1660 Super',
                    'Wi-Fi 6 & Bluetooth 5.2',
                    'Windows 11 Home'
                ],
                'description' => 'High-performance gaming desktop with powerful graphics and fast processing.',
                'sku' => 'HP-PAV-GAME-002',
                'colors' => ['Black'],
                'storage_options' => ['512GB', '1TB', '2TB']
            ],
            [
                'id' => 3,
                'name' => 'Logitech MX Master 3 Wireless Mouse',
                'price' => 89.99,
                'original_price' => 109.99,
                'discount' => 18,
                'image' => 'products/logitech-mx-master-3.jpg',
                'category' => 'Accessories',
                'brand' => 'Logitech',
                'rating' => 5,
                'review_count' => 256,
                'in_stock' => true,
                'stock_quantity' => 50,
                'specs' => [
                    'Advanced 2.4GHz Wireless',
                    'Bluetooth Low Energy',
                    'Rechargeable Battery (70 days)',
                    'Precision Scroll Wheel',
                    'Multi-device Connectivity',
                    'Ergonomic Design'
                ],
                'description' => 'Premium wireless mouse designed for power users and professionals.',
                'sku' => 'LOGI-MXM3-003',
                'colors' => ['Graphite', 'Light Gray', 'Mid Gray']
            ],
            [
                'id' => 4,
                'name' => 'Samsung 27" Curved Gaming Monitor',
                'price' => 299.99,
                'original_price' => 379.99,
                'discount' => 21,
                'image' => 'products/samsung-27-curved.jpg',
                'category' => 'Monitors',
                'brand' => 'Samsung',
                'rating' => 4,
                'review_count' => 167,
                'in_stock' => true,
                'stock_quantity' => 12,
                'specs' => [
                    '27" Curved VA Panel',
                    '2560 x 1440 Resolution',
                    '144Hz Refresh Rate',
                    '1ms Response Time',
                    'AMD FreeSync Premium',
                    'HDMI, DisplayPort, USB-C'
                ],
                'description' => 'Immersive curved gaming monitor with high refresh rate and crisp visuals.',
                'sku' => 'SAMS-C27-004'
            ],
            [
                'id' => 5,
                'name' => 'Canon PIXMA TR8620 All-in-One Printer',
                'price' => 179.99,
                'original_price' => 229.99,
                'discount' => 22,
                'image' => 'products/canon-pixma-tr8620.jpg',
                'category' => 'Printers',
                'brand' => 'Canon',
                'rating' => 4,
                'review_count' => 94,
                'in_stock' => false,
                'stock_quantity' => 0,
                'specs' => [
                    'Print, Scan, Copy, Fax',
                    'Wireless & Ethernet',
                    'Auto Document Feeder',
                    'Duplex Printing',
                    'Mobile Printing Support',
                    '5-Color Individual Ink System'
                ],
                'description' => 'Versatile all-in-one printer perfect for home office productivity.',
                'sku' => 'CANON-TR8620-005'
            ],
            [
                'id' => 6,
                'name' => 'TP-Link AX3000 Wi-Fi 6 Router',
                'price' => 129.99,
                'original_price' => 169.99,
                'discount' => 24,
                'image' => 'products/tplink-ax3000.jpg',
                'category' => 'Networking',
                'brand' => 'TP-Link',
                'rating' => 5,
                'review_count' => 203,
                'in_stock' => true,
                'stock_quantity' => 25,
                'specs' => [
                    'Wi-Fi 6 (802.11ax)',
                    'Dual-Band up to 3 Gbps',
                    '4 × Gigabit LAN Ports',
                    'OFDMA & MU-MIMO',
                    'WPA3 Security',
                    'TP-Link Tether App'
                ],
                'description' => 'Next-generation Wi-Fi 6 router for ultra-fast and reliable connectivity.',
                'sku' => 'TPLINK-AX3000-006'
            ],
            [
                'id' => 7,
                'name' => 'ASUS ROG Strix G15 Gaming Laptop',
                'price' => 1399.99,
                'original_price' => 1699.99,
                'discount' => 18,
                'image' => 'products/asus-rog-strix-g15.jpg',
                'category' => 'Laptops',
                'brand' => 'ASUS',
                'rating' => 5,
                'review_count' => 312,
                'in_stock' => true,
                'stock_quantity' => 5,
                'specs' => [
                    'AMD Ryzen 7 5800H',
                    '16GB DDR4 RAM',
                    '1TB PCIe NVMe SSD',
                    '15.6" 144Hz Full HD',
                    'NVIDIA RTX 3060 6GB',
                    'RGB Backlit Keyboard'
                ],
                'description' => 'High-performance gaming laptop with powerful graphics and premium features.',
                'sku' => 'ASUS-ROG-G15-007',
                'colors' => ['Eclipse Gray', 'Original Black'],
                'storage_options' => ['512GB', '1TB', '2TB']
            ],
            [
                'id' => 8,
                'name' => 'Microsoft Surface Studio Desktop',
                'price' => 2399.99,
                'original_price' => 2799.99,
                'discount' => 14,
                'image' => 'products/microsoft-surface-studio.jpg',
                'category' => 'Desktops',
                'brand' => 'Microsoft',
                'rating' => 4,
                'review_count' => 67,
                'in_stock' => true,
                'stock_quantity' => 3,
                'specs' => [
                    'Intel Core i7-11370H',
                    '32GB LPDDR4x RAM',
                    '1TB PCIe SSD',
                    '28" PixelSense Touchscreen',
                    'NVIDIA GeForce RTX 3060',
                    'Windows 11 Pro'
                ],
                'description' => 'Professional all-in-one desktop perfect for creative professionals.',
                'sku' => 'MSFT-SURF-STU-008',
                'colors' => ['Platinum']
            ],
            [
                'id' => 9,
                'name' => 'Razer DeathAdder V3 Gaming Mouse',
                'price' => 79.99,
                'original_price' => 99.99,
                'discount' => 20,
                'image' => 'products/razer-deathadder-v3.jpg',
                'category' => 'Accessories',
                'brand' => 'Razer',
                'rating' => 5,
                'review_count' => 445,
                'in_stock' => true,
                'stock_quantity' => 30,
                'specs' => [
                    'Focus Pro 30K Sensor',
                    '90-hour Battery Life',
                    'Wireless HyperSpeed',
                    '8 Programmable Buttons',
                    'Razer Chroma RGB',
                    'Ergonomic Design'
                ],
                'description' => 'Professional gaming mouse with precision sensor and long battery life.',
                'sku' => 'RAZER-DA-V3-009',
                'colors' => ['Black', 'White']
            ],
            [
                'id' => 10,
                'name' => 'LG 32" 4K UltraWide Monitor',
                'price' => 599.99,
                'original_price' => 749.99,
                'discount' => 20,
                'image' => 'products/lg-32-4k-ultrawide.jpg',
                'category' => 'Monitors',
                'brand' => 'LG',
                'rating' => 5,
                'review_count' => 189,
                'in_stock' => true,
                'stock_quantity' => 7,
                'specs' => [
                    '32" IPS UltraWide Display',
                    '3840 x 1600 Resolution',
                    'HDR10 Support',
                    'USB-C with 96W Power',
                    'Picture-by-Picture',
                    'Height Adjustable Stand'
                ],
                'description' => 'Premium ultrawide monitor perfect for productivity and content creation.',
                'sku' => 'LG-32UW-4K-010'
            ]
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories by slug - they should exist from CategorySeeder
        $laptops = Category::where('slug', 'laptops')->first();
        $desktops = Category::where('slug', 'desktops')->first();
        $accessories = Category::where('slug', 'accessories')->first();
        $monitors = Category::where('slug', 'monitors')->first();
        $printers = Category::where('slug', 'printers')->first();
        $networking = Category::where('slug', 'networking')->first();

        $products = [
            [
                'name' => 'Dell Inspiron 15 3000 Laptop',
                'description' => 'Perfect laptop for everyday computing with reliable performance and modern features.',
                'price' => 699.99,
                'original_price' => 799.99,
                'discount' => 13,
                'image' => 'products/ell-inspiron-15.jpg',
                'category_id' => $laptops->id,
                'brand' => 'Dell',
                'sku' => 'DELL-INSP15-001',
                'stock_quantity' => 15,
                'in_stock' => true,
                'rating' => 4,
                'review_count' => 128,
                'specs' => [
                    'Intel Core i5-1135G7 Processor',
                    '8GB DDR4 RAM',
                    '256GB PCIe NVMe SSD',
                    '15.6" Full HD Display',
                    'Intel Iris Xe Graphics',
                    'Windows 11 Home'
                ],
                'colors' => ['Black', 'Silver'],
                'storage_options' => ['256GB', '512GB', '1TB'],
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'name' => 'HP Pavilion Gaming Desktop',
                'description' => 'High-performance gaming desktop with powerful graphics and fast processing.',
                'price' => 899.99,
                'original_price' => 1099.99,
                'discount' => 18,
                'image' => 'products/hp-pavilion-desktop.jpg',
                'category_id' => $desktops->id,
                'brand' => 'HP',
                'sku' => 'HP-PAV-GAME-002',
                'stock_quantity' => 8,
                'in_stock' => true,
                'rating' => 5,
                'review_count' => 89,
                'specs' => [
                    'AMD Ryzen 5 5600G Processor',
                    '16GB DDR4 RAM',
                    '512GB PCIe NVMe SSD',
                    'NVIDIA GTX 1660 Super',
                    'Wi-Fi 6 & Bluetooth 5.2',
                    'Windows 11 Home'
                ],
                'colors' => ['Black'],
                'storage_options' => ['512GB', '1TB', '2TB'],
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'name' => 'Logitech MX Master 3 Wireless Mouse',
                'description' => 'Premium wireless mouse designed for power users and professionals.',
                'price' => 89.99,
                'original_price' => 109.99,
                'discount' => 18,
                'image' => 'products/logitech-mx-master-3.jpg',
                'category_id' => $accessories->id,
                'brand' => 'Logitech',
                'sku' => 'LOGI-MXM3-003',
                'stock_quantity' => 50,
                'in_stock' => true,
                'rating' => 5,
                'review_count' => 256,
                'specs' => [
                    'Advanced 2.4GHz Wireless',
                    'Bluetooth Low Energy',
                    'Rechargeable Battery (70 days)',
                    'Precision Scroll Wheel',
                    'Multi-device Connectivity',
                    'Ergonomic Design'
                ],
                'colors' => ['Graphite', 'Light Gray', 'Mid Gray'],
                'storage_options' => null,
                'is_featured' => false,
                'is_active' => true
            ],
            [
                'name' => 'Samsung 27" Curved Gaming Monitor',
                'description' => 'Immersive curved gaming monitor with high refresh rate and crisp visuals.',
                'price' => 299.99,
                'original_price' => 379.99,
                'discount' => 21,
                'image' => 'products/samsung-27-curved.jpg',
                'category_id' => $monitors->id,
                'brand' => 'Samsung',
                'sku' => 'SAMS-C27-004',
                'stock_quantity' => 12,
                'in_stock' => true,
                'rating' => 4,
                'review_count' => 167,
                'specs' => [
                    '27" Curved VA Panel',
                    '2560 x 1440 Resolution',
                    '144Hz Refresh Rate',
                    '1ms Response Time',
                    'AMD FreeSync Premium',
                    'HDMI, DisplayPort, USB-C'
                ],
                'colors' => null,
                'storage_options' => null,
                'is_featured' => false,
                'is_active' => true
            ],
            [
                'name' => 'Canon PIXMA TR8620 All-in-One Printer',
                'description' => 'Versatile all-in-one printer perfect for home office productivity.',
                'price' => 179.99,
                'original_price' => 229.99,
                'discount' => 22,
                'image' => 'products/canon-pixma-tr8620.jpg',
                'category_id' => $printers->id,
                'brand' => 'Canon',
                'sku' => 'CANON-TR8620-005',
                'stock_quantity' => 0,
                'in_stock' => false,
                'rating' => 4,
                'review_count' => 94,
                'specs' => [
                    'Print, Scan, Copy, Fax',
                    'Wireless & Ethernet',
                    'Auto Document Feeder',
                    'Duplex Printing',
                    'Mobile Printing Support',
                    '5-Color Individual Ink System'
                ],
                'colors' => null,
                'storage_options' => null,
                'is_featured' => false,
                'is_active' => true
            ],
            [
                'name' => 'TP-Link AX3000 Wi-Fi 6 Router',
                'description' => 'Next-generation Wi-Fi 6 router for ultra-fast and reliable connectivity.',
                'price' => 129.99,
                'original_price' => 169.99,
                'discount' => 24,
                'image' => 'products/tplink-ax3000.jpg',
                'category_id' => $networking->id,
                'brand' => 'TP-Link',
                'sku' => 'TPLINK-AX3000-006',
                'stock_quantity' => 25,
                'in_stock' => true,
                'rating' => 5,
                'review_count' => 203,
                'specs' => [
                    'Wi-Fi 6 (802.11ax)',
                    'Dual-Band up to 3 Gbps',
                    '4 × Gigabit LAN Ports',
                    'OFDMA & MU-MIMO',
                    'WPA3 Security',
                    'TP-Link Tether App'
                ],
                'colors' => null,
                'storage_options' => null,
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'name' => 'ASUS ROG Strix G15 Gaming Laptop',
                'description' => 'High-performance gaming laptop with powerful graphics and premium features.',
                'price' => 1399.99,
                'original_price' => 1699.99,
                'discount' => 18,
                'image' => 'products/asus-rog-strix-g15.jpg',
                'category_id' => $laptops->id,
                'brand' => 'ASUS',
                'sku' => 'ASUS-ROG-G15-007',
                'stock_quantity' => 5,
                'in_stock' => true,
                'rating' => 5,
                'review_count' => 312,
                'specs' => [
                    'AMD Ryzen 7 5800H',
                    '16GB DDR4 RAM',
                    '1TB PCIe NVMe SSD',
                    '15.6" 144Hz Full HD',
                    'NVIDIA RTX 3060 6GB',
                    'RGB Backlit Keyboard'
                ],
                'colors' => ['Eclipse Gray', 'Original Black'],
                'storage_options' => ['512GB', '1TB', '2TB'],
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'name' => 'Microsoft Surface Studio Desktop',
                'description' => 'Professional all-in-one desktop perfect for creative professionals.',
                'price' => 2399.99,
                'original_price' => 2799.99,
                'discount' => 14,
                'image' => 'products/microsoft-surface-studio.jpg',
                'category_id' => $desktops->id,
                'brand' => 'Microsoft',
                'sku' => 'MSFT-SURF-STU-008',
                'stock_quantity' => 3,
                'in_stock' => true,
                'rating' => 4,
                'review_count' => 67,
                'specs' => [
                    'Intel Core i7-11370H',
                    '32GB LPDDR4x RAM',
                    '1TB PCIe SSD',
                    '28" PixelSense Touchscreen',
                    'NVIDIA GeForce RTX 3060',
                    'Windows 11 Pro'
                ],
                'colors' => ['Platinum'],
                'storage_options' => null,
                'is_featured' => false,
                'is_active' => true
            ],
            [
                'name' => 'Razer DeathAdder V3 Gaming Mouse',
                'description' => 'Professional gaming mouse with precision sensor and long battery life.',
                'price' => 79.99,
                'original_price' => 99.99,
                'discount' => 20,
                'image' => 'products/razer-deathadder-v3.jpg',
                'category_id' => $accessories->id,
                'brand' => 'Razer',
                'sku' => 'RAZER-DA-V3-009',
                'stock_quantity' => 30,
                'in_stock' => true,
                'rating' => 5,
                'review_count' => 445,
                'specs' => [
                    'Focus Pro 30K Sensor',
                    '90-hour Battery Life',
                    'Wireless HyperSpeed',
                    '8 Programmable Buttons',
                    'Razer Chroma RGB',
                    'Ergonomic Design'
                ],
                'colors' => ['Black', 'White'],
                'storage_options' => null,
                'is_featured' => false,
                'is_active' => true
            ],
            [
                'name' => 'LG 32" 4K UltraWide Monitor',
                'description' => 'Premium ultrawide monitor perfect for productivity and content creation.',
                'price' => 599.99,
                'original_price' => 749.99,
                'discount' => 20,
                'image' => 'products/lg-32-4k-ultrawide.jpg',
                'category_id' => $monitors->id,
                'brand' => 'LG',
                'sku' => 'LG-32UW-4K-010',
                'stock_quantity' => 7,
                'in_stock' => true,
                'rating' => 5,
                'review_count' => 189,
                'specs' => [
                    '32" IPS UltraWide Display',
                    '3840 x 1600 Resolution',
                    'HDR10 Support',
                    'USB-C with 96W Power',
                    'Picture-by-Picture',
                    'Height Adjustable Stand'
                ],
                'colors' => null,
                'storage_options' => null,
                'is_featured' => true,
                'is_active' => true
            ]
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        $this->command->info('✓ 10 products seeded successfully!');
    }
}
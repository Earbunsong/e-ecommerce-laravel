<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Laptops',
                'slug' => 'laptops',
                'description' => 'Portable computers for personal and professional use',
                'image' => null,
                'is_active' => true,
                'display_order' => 1,
            ],
            [
                'name' => 'Desktops',
                'slug' => 'desktops',
                'description' => 'Desktop computers and workstations',
                'image' => null,
                'is_active' => true,
                'display_order' => 2,
            ],
            [
                'name' => 'Monitors',
                'slug' => 'monitors',
                'description' => 'Computer displays and screens',
                'image' => null,
                'is_active' => true,
                'display_order' => 3,
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Computer peripherals and accessories',
                'image' => null,
                'is_active' => true,
                'display_order' => 4,
            ],
            [
                'name' => 'Printers',
                'slug' => 'printers',
                'description' => 'Printing devices and scanners',
                'image' => null,
                'is_active' => true,
                'display_order' => 5,
            ],
            [
                'name' => 'Networking',
                'slug' => 'networking',
                'description' => 'Network equipment and devices',
                'image' => null,
                'is_active' => true,
                'display_order' => 6,
            ],
            [
                'name' => 'Storage',
                'slug' => 'storage',
                'description' => 'External drives and storage solutions',
                'image' => null,
                'is_active' => true,
                'display_order' => 7,
            ],
            [
                'name' => 'Gaming',
                'slug' => 'gaming',
                'description' => 'Gaming computers and accessories',
                'image' => null,
                'is_active' => true,
                'display_order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
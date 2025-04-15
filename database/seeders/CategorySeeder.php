<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Electronics' => [
                'Mobile Phones',
                'Laptops',
                'Tablets',
                'Televisions',
                'Cameras',
                'Headphones',
                'Smart Watches',
                'Gaming Consoles',
            ],
            'Clothing & Accessories' => [
                'Men\'s Clothing',
                'Women\'s Clothing',
                'Kid\'s Clothing',
                'Shoes',
                'Watches',
                'Bags & Wallets',
                'Jewelry',
                'Sunglasses',
            ],
            'Home & Kitchen' => [
                'Furniture',
                'Kitchen Appliances',
                'Cookware & Bakeware',
                'Bedding',
                'Home Decor',
                'Storage & Organization',
                'Lighting',
                'Cleaning Supplies',
            ],
            'Beauty & Personal Care' => [
                'Makeup',
                'Skincare',
                'Hair Care',
                'Fragrances',
                'Oral Care',
                'Tools & Accessories',
                'Shaving & Grooming',
            ],
            'Health & Wellness' => [
                'Supplements',
                'Fitness Equipment',
                'First Aid',
                'Medical Supplies',
                'Wellness Products',
            ],
            'Sports & Outdoors' => [
                'Exercise & Fitness',
                'Cycling',
                'Camping & Hiking',
                'Outdoor Recreation',
                'Team Sports',
                'Water Sports',
            ],
            'Toys & Games' => [
                'Action Figures',
                'Puzzles',
                'Board Games',
                'Building Sets',
                'Educational Toys',
                'Outdoor Toys',
                'Dolls & Plush',
            ],
            'Automotive' => [
                'Car Accessories',
                'Motorcycle Parts',
                'Car Electronics',
                'Tires & Wheels',
                'Oils & Fluids',
                'Tools & Equipment',
            ],
            'Books & Stationery' => [
                'Fiction',
                'Non-Fiction',
                'Children\'s Books',
                'School Supplies',
                'Office Supplies',
                'Notebooks & Diaries',
            ],
            'Baby & Kids' => [
                'Diapers',
                'Baby Gear',
                'Feeding',
                'Bath & Skincare',
                'Toys for Babies',
                'Clothing for Babies',
            ]
        ];

        foreach ($categories as $parentName => $subCategories) {
            $parent = Category::create(['name' => $parentName]);

            foreach ($subCategories as $childName) {
                Category::create([
                    'name' => $childName,
                    'parent_id' => $parent->id
                ]);
            }
        }
    }
}

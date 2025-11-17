<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            // Seeds Category
            [
                'name' => 'Premium Wheat Seeds',
                'description' => 'High-yield wheat seeds with excellent disease resistance. Perfect for commercial farming.',
                'category' => Product::CATEGORY_SEEDS,
                'price' => 25.99,
                'currency' => 'USD',
                'stock_quantity' => 500,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Organic Corn Seeds',
                'description' => 'Non-GMO organic corn seeds. Ideal for sustainable farming practices.',
                'category' => Product::CATEGORY_SEEDS,
                'price' => 32.50,
                'currency' => 'USD',
                'stock_quantity' => 350,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Hybrid Rice Seeds',
                'description' => 'High-yield hybrid rice seeds with superior grain quality and fast growth.',
                'category' => Product::CATEGORY_SEEDS,
                'price' => 28.75,
                'currency' => 'USD',
                'stock_quantity' => 600,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Soybean Seeds Premium',
                'description' => 'Premium soybean seeds with high protein content. Excellent for crop rotation.',
                'category' => Product::CATEGORY_SEEDS,
                'price' => 30.00,
                'currency' => 'USD',
                'stock_quantity' => 400,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Sunflower Seeds',
                'description' => 'High-oil content sunflower seeds. Perfect for oil production and bird feed.',
                'category' => Product::CATEGORY_SEEDS,
                'price' => 18.50,
                'currency' => 'USD',
                'stock_quantity' => 250,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Barley Seeds',
                'description' => 'Quality barley seeds for brewing and animal feed. High germination rate.',
                'category' => Product::CATEGORY_SEEDS,
                'price' => 22.00,
                'currency' => 'USD',
                'stock_quantity' => 300,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Tomato Seeds Heirloom',
                'description' => 'Heirloom tomato seeds with rich flavor. Perfect for home gardens.',
                'category' => Product::CATEGORY_SEEDS,
                'price' => 12.99,
                'currency' => 'USD',
                'stock_quantity' => 150,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Carrot Seeds Organic',
                'description' => 'Organic carrot seeds. High germination rate and disease resistant.',
                'category' => Product::CATEGORY_SEEDS,
                'price' => 9.99,
                'currency' => 'USD',
                'stock_quantity' => 200,
                'status' => Product::STATUS_ACTIVE,
            ],

            // Fertilizers Category
            [
                'name' => 'NPK 20-20-20 Fertilizer',
                'description' => 'Balanced NPK fertilizer for all crops. Promotes healthy growth and high yields.',
                'category' => Product::CATEGORY_FERTILIZERS,
                'price' => 45.00,
                'currency' => 'USD',
                'stock_quantity' => 100,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Organic Compost 50kg',
                'description' => 'Rich organic compost made from farm waste. Improves soil structure and fertility.',
                'category' => Product::CATEGORY_FERTILIZERS,
                'price' => 35.00,
                'currency' => 'USD',
                'stock_quantity' => 80,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Urea Fertilizer 46-0-0',
                'description' => 'High nitrogen content fertilizer. Essential for leafy growth and green color.',
                'category' => Product::CATEGORY_FERTILIZERS,
                'price' => 38.50,
                'currency' => 'USD',
                'stock_quantity' => 120,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Phosphate Fertilizer',
                'description' => 'High phosphate content for root development and flowering. 50kg bag.',
                'category' => Product::CATEGORY_FERTILIZERS,
                'price' => 42.00,
                'currency' => 'USD',
                'stock_quantity' => 90,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Potash Fertilizer',
                'description' => 'Potassium-rich fertilizer for fruit development and disease resistance.',
                'category' => Product::CATEGORY_FERTILIZERS,
                'price' => 40.00,
                'currency' => 'USD',
                'stock_quantity' => 75,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Liquid Seaweed Fertilizer',
                'description' => 'Organic liquid fertilizer from seaweed. Rich in micronutrients and growth hormones.',
                'category' => Product::CATEGORY_FERTILIZERS,
                'price' => 28.99,
                'currency' => 'USD',
                'stock_quantity' => 60,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Bone Meal Fertilizer',
                'description' => 'Organic phosphorus source. Slow-release fertilizer for long-term plant nutrition.',
                'category' => Product::CATEGORY_FERTILIZERS,
                'price' => 32.00,
                'currency' => 'USD',
                'stock_quantity' => 50,
                'status' => Product::STATUS_ACTIVE,
            ],

            // Equipment Category
            [
                'name' => 'Tractor 50HP',
                'description' => 'Reliable 50HP tractor for medium-scale farming. Includes plow attachment.',
                'category' => Product::CATEGORY_EQUIPMENT,
                'price' => 25000.00,
                'currency' => 'USD',
                'stock_quantity' => 5,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Combine Harvester',
                'description' => 'Modern combine harvester for efficient grain harvesting. Suitable for large fields.',
                'category' => Product::CATEGORY_EQUIPMENT,
                'price' => 125000.00,
                'currency' => 'USD',
                'stock_quantity' => 2,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Irrigation System Drip',
                'description' => 'Complete drip irrigation system for efficient water usage. Covers 1 acre.',
                'category' => Product::CATEGORY_EQUIPMENT,
                'price' => 850.00,
                'currency' => 'USD',
                'stock_quantity' => 15,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Seed Drill Machine',
                'description' => 'Precision seed drill for uniform planting. Reduces seed waste significantly.',
                'category' => Product::CATEGORY_EQUIPMENT,
                'price' => 3200.00,
                'currency' => 'USD',
                'stock_quantity' => 8,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Rotary Tiller',
                'description' => 'Heavy-duty rotary tiller for soil preparation. Perfect for breaking hard soil.',
                'category' => Product::CATEGORY_EQUIPMENT,
                'price' => 1800.00,
                'currency' => 'USD',
                'stock_quantity' => 12,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Sprayer Backpack',
                'description' => 'Professional backpack sprayer for pesticides and fertilizers. 20L capacity.',
                'category' => Product::CATEGORY_EQUIPMENT,
                'price' => 125.00,
                'currency' => 'USD',
                'stock_quantity' => 30,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Greenhouse Structure',
                'description' => 'Complete greenhouse structure 20x40ft. Includes frame, cover, and ventilation.',
                'category' => Product::CATEGORY_EQUIPMENT,
                'price' => 4500.00,
                'currency' => 'USD',
                'stock_quantity' => 6,
                'status' => Product::STATUS_ACTIVE,
            ],

            // Tools Category
            [
                'name' => 'Garden Hoe Heavy Duty',
                'description' => 'Professional grade garden hoe with reinforced steel blade. Long-lasting tool.',
                'category' => Product::CATEGORY_TOOLS,
                'price' => 24.99,
                'currency' => 'USD',
                'stock_quantity' => 50,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Pruning Shears Professional',
                'description' => 'High-quality pruning shears with ergonomic grip. Perfect for tree maintenance.',
                'category' => Product::CATEGORY_TOOLS,
                'price' => 35.00,
                'currency' => 'USD',
                'stock_quantity' => 40,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Shovel Round Point',
                'description' => 'Durable round point shovel for digging and soil work. Fiberglass handle.',
                'category' => Product::CATEGORY_TOOLS,
                'price' => 28.50,
                'currency' => 'USD',
                'stock_quantity' => 45,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Rake Leaf 24-Tine',
                'description' => 'Heavy-duty leaf rake with 24 tines. Perfect for gathering leaves and debris.',
                'category' => Product::CATEGORY_TOOLS,
                'price' => 22.00,
                'currency' => 'USD',
                'stock_quantity' => 35,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Wheelbarrow Heavy Duty',
                'description' => 'Professional wheelbarrow with steel tray. 6 cubic feet capacity.',
                'category' => Product::CATEGORY_TOOLS,
                'price' => 95.00,
                'currency' => 'USD',
                'stock_quantity' => 20,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Garden Fork 4-Tine',
                'description' => 'Sturdy garden fork for turning soil and compost. Tempered steel tines.',
                'category' => Product::CATEGORY_TOOLS,
                'price' => 32.00,
                'currency' => 'USD',
                'stock_quantity' => 30,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Hand Trowel Set',
                'description' => 'Set of 3 hand trowels in different sizes. Perfect for planting and transplanting.',
                'category' => Product::CATEGORY_TOOLS,
                'price' => 18.99,
                'currency' => 'USD',
                'stock_quantity' => 25,
                'status' => Product::STATUS_ACTIVE,
            ],
            [
                'name' => 'Watering Can 10L',
                'description' => 'Large capacity watering can with detachable rose. Made from durable plastic.',
                'category' => Product::CATEGORY_TOOLS,
                'price' => 19.50,
                'currency' => 'USD',
                'stock_quantity' => 40,
                'status' => Product::STATUS_ACTIVE,
            ],

            // Out of Stock Products
            [
                'name' => 'Premium Cotton Seeds',
                'description' => 'High-quality cotton seeds with excellent fiber quality. Currently out of stock.',
                'category' => Product::CATEGORY_SEEDS,
                'price' => 35.00,
                'currency' => 'USD',
                'stock_quantity' => 0,
                'status' => Product::STATUS_OUT_OF_STOCK,
            ],
            [
                'name' => 'Organic Manure 100kg',
                'description' => 'Well-composted organic manure. Rich in nutrients for healthy plant growth.',
                'category' => Product::CATEGORY_FERTILIZERS,
                'price' => 55.00,
                'currency' => 'USD',
                'stock_quantity' => 0,
                'status' => Product::STATUS_OUT_OF_STOCK,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('Created ' . count($products) . ' products.');
    }
}


<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = [
            [
                'service_id' => 'S001',
                'name' => 'Crop Planning & Management',
                'description' => 'Strategic crop planning and management services to maximize your farm\'s productivity. Our experts help you plan crop rotations, select optimal varieties, and manage resources efficiently.',
                'category' => 'Planning',
                'icon' => 'fa-seedling',
                'price' => 299.00,
                'price_type' => 'monthly',
                'active_clients' => 24,
                'status' => Service::STATUS_ACTIVE,
            ],
            [
                'service_id' => 'S002',
                'name' => 'Soil Testing & Analysis',
                'description' => 'Comprehensive soil testing and analysis to understand your soil\'s nutrient levels, pH, and composition. Get detailed reports and recommendations for optimal crop growth.',
                'category' => 'Analysis',
                'icon' => 'fa-flask',
                'price' => 150.00,
                'price_type' => 'fixed',
                'active_clients' => 18,
                'status' => Service::STATUS_ACTIVE,
            ],
            [
                'service_id' => 'S003',
                'name' => 'Irrigation System Design',
                'description' => 'Custom irrigation system design and installation. We create efficient water management solutions tailored to your farm\'s needs, reducing water waste and costs.',
                'category' => 'Infrastructure',
                'icon' => 'fa-tint',
                'price' => 500.00,
                'price_type' => 'fixed',
                'active_clients' => 12,
                'status' => Service::STATUS_ACTIVE,
            ],
            [
                'service_id' => 'S004',
                'name' => 'Pest & Disease Management',
                'description' => 'Expert pest and disease management services. We identify problems early and provide eco-friendly solutions to protect your crops without harming the environment.',
                'category' => 'Protection',
                'icon' => 'fa-shield-alt',
                'price' => 250.00,
                'price_type' => 'monthly',
                'active_clients' => 30,
                'status' => Service::STATUS_ACTIVE,
            ],
            [
                'service_id' => 'S005',
                'name' => 'Harvesting Services',
                'description' => 'Professional harvesting services using modern equipment. We ensure timely and efficient harvesting to maximize crop quality and minimize losses.',
                'category' => 'Harvesting',
                'icon' => 'fa-hands-helping',
                'price' => 75.00,
                'price_type' => 'hourly',
                'active_clients' => 15,
                'status' => Service::STATUS_ACTIVE,
            ],
            [
                'service_id' => 'S006',
                'name' => 'Agricultural Consulting',
                'description' => 'Expert agricultural consulting services. Get advice on best practices, technology adoption, market trends, and sustainable farming methods from experienced consultants.',
                'category' => 'Consulting',
                'icon' => 'fa-user-tie',
                'price' => 200.00,
                'price_type' => 'hourly',
                'active_clients' => 22,
                'status' => Service::STATUS_ACTIVE,
            ],
            [
                'service_id' => 'S007',
                'name' => 'Farm Equipment Rental',
                'description' => 'Rent high-quality farm equipment without the upfront investment. We offer tractors, harvesters, tillers, and more. Flexible rental terms available.',
                'category' => 'Equipment',
                'icon' => 'fa-tractor',
                'price' => 150.00,
                'price_type' => 'per_unit',
                'active_clients' => 35,
                'status' => Service::STATUS_ACTIVE,
            ],
            [
                'service_id' => 'S008',
                'name' => 'Organic Certification Support',
                'description' => 'Complete support for organic certification. We guide you through the entire process, from application to inspection, ensuring compliance with organic standards.',
                'category' => 'Certification',
                'icon' => 'fa-certificate',
                'price' => 450.00,
                'price_type' => 'fixed',
                'active_clients' => 8,
                'status' => Service::STATUS_ACTIVE,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        $this->command->info('Created ' . count($services) . ' services.');
    }
}


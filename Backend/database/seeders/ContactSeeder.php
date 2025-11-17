<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contacts = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@example.com',
                'phone' => '+1234567890',
                'subject' => Contact::SUBJECT_CONSULTATION,
                'message' => 'I am interested in learning more about your crop planning services. I have a 50-acre farm and would like to improve my yields. Can you provide more information about your consultation services?',
                'status' => Contact::STATUS_NEW,
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subDays(2),
            ],
            [
                'name' => 'Maria Garcia',
                'email' => 'maria.garcia@example.com',
                'phone' => '+1987654321',
                'subject' => Contact::SUBJECT_SERVICE,
                'message' => 'I need help with soil testing for my organic farm. What is the process and how long does it take to get results?',
                'status' => Contact::STATUS_READ,
                'ip_address' => '192.168.1.101',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'created_at' => now()->subDays(5),
            ],
            [
                'name' => 'Robert Johnson',
                'email' => 'robert.j@example.com',
                'phone' => '+1555123456',
                'subject' => Contact::SUBJECT_PARTNERSHIP,
                'message' => 'I represent a large agricultural cooperative and we are interested in partnering with your company. We have 200+ member farms and are looking for comprehensive agricultural services.',
                'status' => Contact::STATUS_REPLIED,
                'ip_address' => '192.168.1.102',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/91.0.4472.124',
                'replied_at' => now()->subDays(3),
                'replied_by' => 1,
                'created_at' => now()->subDays(7),
            ],
            [
                'name' => 'Sarah Williams',
                'email' => 'sarah.w@example.com',
                'phone' => '+1444555666',
                'subject' => Contact::SUBJECT_SUPPORT,
                'message' => 'I purchased seeds from your store last month but haven\'t received them yet. Can you check the status of my order? Order number: ORD-2025-001234',
                'status' => Contact::STATUS_REPLIED,
                'ip_address' => '192.168.1.103',
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15',
                'replied_at' => now()->subDay(),
                'replied_by' => 1,
                'created_at' => now()->subDays(4),
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael.brown@example.com',
                'phone' => '+1777888999',
                'subject' => Contact::SUBJECT_GENERAL,
                'message' => 'I am a new farmer and would like to know more about your services. What would be the best starting point for someone just getting into agriculture?',
                'status' => Contact::STATUS_NEW,
                'ip_address' => '192.168.1.104',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Edge/91.0.864.59',
                'created_at' => now()->subHours(5),
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily.davis@example.com',
                'phone' => '+1666777888',
                'subject' => Contact::SUBJECT_CONSULTATION,
                'message' => 'I am planning to start an organic vegetable farm. I would like to schedule a consultation to discuss soil preparation, crop selection, and organic certification.',
                'status' => Contact::STATUS_READ,
                'ip_address' => '192.168.1.105',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Safari/605.1.15',
                'created_at' => now()->subDays(1),
            ],
            [
                'name' => 'David Wilson',
                'email' => 'david.wilson@example.com',
                'phone' => null,
                'subject' => Contact::SUBJECT_SERVICE,
                'message' => 'Do you offer irrigation system installation services? I have a 30-acre farm and need a reliable irrigation solution. What are your rates?',
                'status' => Contact::STATUS_NEW,
                'ip_address' => '192.168.1.106',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36',
                'created_at' => now()->subHours(12),
            ],
            [
                'name' => 'Lisa Anderson',
                'email' => 'lisa.anderson@example.com',
                'phone' => '+1999888777',
                'subject' => Contact::SUBJECT_OTHER,
                'message' => 'I am interested in your pest management services. I have been experiencing issues with aphids on my crops. Can you provide organic solutions?',
                'status' => Contact::STATUS_ARCHIVED,
                'ip_address' => '192.168.1.107',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Firefox/89.0',
                'created_at' => now()->subDays(10),
            ],
            [
                'name' => 'James Taylor',
                'email' => 'james.taylor@example.com',
                'phone' => '+1888777666',
                'subject' => Contact::SUBJECT_CONSULTATION,
                'message' => 'I need advice on crop rotation for my farm. I currently grow corn, soybeans, and wheat. What would be the best rotation schedule?',
                'status' => Contact::STATUS_READ,
                'ip_address' => '192.168.1.108',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/91.0.4472.124',
                'created_at' => now()->subDays(3),
            ],
            [
                'name' => 'Jennifer Martinez',
                'email' => 'jennifer.m@example.com',
                'phone' => '+1777666555',
                'subject' => Contact::SUBJECT_SUPPORT,
                'message' => 'I have a question about the fertilizer I purchased. The instructions are unclear. Can someone help me understand the application rate?',
                'status' => Contact::STATUS_REPLIED,
                'ip_address' => '192.168.1.109',
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15',
                'replied_at' => now()->subHours(2),
                'replied_by' => 1,
                'created_at' => now()->subDays(1),
            ],
        ];

        foreach ($contacts as $contact) {
            Contact::create($contact);
        }

        $this->command->info('Created ' . count($contacts) . ' contact submissions.');
    }
}


<?php

namespace Database\Seeders;

use App\Actions\Teams\CreateTeam;
use App\Models\AuditLog;
use App\Models\BuyerProfile;
use App\Models\Category;
use App\Models\Commodity;
use App\Models\FarmerProfile;
use App\Models\Inquiry;
use App\Models\InquiryMessage;
use App\Models\Listing;
use App\Models\ModerationReview;
use App\Models\Notification;
use App\Models\PriceRecord;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database for the 13 relational tables.
     */
    public function run(): void
    {
        $createTeam = app(CreateTeam::class);

        // 1. Municipal Administrator Account (Table: USERS)
        $admin = User::firstOrCreate(
            ['email' => 'admin@tabonet.ph'],
            [
                'full_name' => 'Municipal Administrator',
                'password_hash' => Hash::make('password'),
                'role' => 'admin',
                'phone_number' => '09123456789',
                'verification_status' => 'verified',
                'email_verified_at' => now(),
            ]
        );

        if (! $admin->currentTeam) {
            $createTeam->handle($admin, 'Cantilan Admin Office', isPersonal: true);
        }

        // 2. Verified Smallholder Farmer Account & Profile (Tables: USERS & FARMER_PROFILES)
        $farmer = User::firstOrCreate(
            ['email' => 'farmer@tabonet.ph'],
            [
                'full_name' => 'Mang Pedro Cantilan',
                'password_hash' => Hash::make('password'),
                'role' => 'farmer',
                'phone_number' => '09171234567',
                'verification_status' => 'verified',
                'email_verified_at' => now(),
            ]
        );

        if (! $farmer->currentTeam) {
            $createTeam->handle($farmer, 'Linotan Farm Produce', isPersonal: true);
        }

        FarmerProfile::firstOrCreate(
            ['user_id' => $farmer->user_id],
            [
                'farm_name' => 'Linotan Organic Harvest Farm',
                'farm_location' => 'Linotan',
                'farm_type' => 'Crops & Tubers',
                'valid_id_url' => '16-68-04-001-000123',
                'bio' => 'Family-operated agro-ecological farm specializing in indigenous rice varieties and upland root crops.',
            ]
        );

        // 3. Registered Commercial Buyer Account & Profile (Tables: USERS & BUYER_PROFILES)
        $buyer = User::firstOrCreate(
            ['email' => 'buyer@tabonet.ph'],
            [
                'full_name' => 'Maria Santos',
                'password_hash' => Hash::make('password'),
                'role' => 'buyer',
                'phone_number' => '09281234567',
                'verification_status' => 'verified',
                'email_verified_at' => now(),
            ]
        );

        if (! $buyer->currentTeam) {
            $createTeam->handle($buyer, 'Santos Wholesale & Retail', isPersonal: true);
        }

        BuyerProfile::firstOrCreate(
            ['user_id' => $buyer->user_id],
            [
                'business_name' => 'Santos Commercial Trading',
                'delivery_address' => 'Poblacion',
                'buyer_type' => 'Wholesaler',
            ]
        );

        // 4. Pending Accreditation Smallholder Farmer Account (For Admin Verification Workflow)
        $pendingFarmer = User::firstOrCreate(
            ['email' => 'farmer.pending@tabonet.ph'],
            [
                'full_name' => 'Juan Dela Cruz (Pending Accreditation)',
                'password_hash' => Hash::make('password'),
                'role' => 'farmer',
                'phone_number' => '09391234567',
                'verification_status' => 'pending',
                'email_verified_at' => now(),
            ]
        );

        if (! $pendingFarmer->currentTeam) {
            $createTeam->handle($pendingFarmer, 'Calagdaan High Farm', isPersonal: true);
        }

        FarmerProfile::firstOrCreate(
            ['user_id' => $pendingFarmer->user_id],
            [
                'farm_name' => 'Calagdaan Highland Agro',
                'farm_location' => 'Calagdaan',
                'farm_type' => 'Orchard & Vegetables',
                'valid_id_url' => '16-68-04-002-000456',
                'bio' => 'Seeking municipal verification for seasonal fruit harvesting.',
            ]
        );

        // 5. CATEGORIES (Table: CATEGORIES)
        $categories = [
            'Grains & Cereals' => 'Staple grain crops including milled rice, corn, and grain derivatives.',
            'Root Crops' => 'Tubers, sweet potato, cassava, and ube harvested across upland Cantilan.',
            'Fruits' => 'Naturally tree-ripened local fruits including Carabao mango and Saba bananas.',
            'Vegetables' => 'Fresh lowland and highland leafy greens, eggplant, and squash.',
            'Aquaculture' => 'Inland freshwater pond fish harvests including tilapia and bangus.',
        ];

        $categoryModels = [];
        foreach ($categories as $name => $desc) {
            $categoryModels[$name] = Category::firstOrCreate(
                ['name' => $name],
                ['description' => $desc]
            );
        }

        // 6. COMMODITIES (Table: COMMODITIES)
        $commoditiesData = [
            [
                'name' => 'Native Milled Rice (Dinorado)',
                'category' => 'Grains & Cereals',
                'unit' => 'sako',
                'desc' => 'Freshly harvested and milled dinorado rice from Magosilom river valleys.',
                'price' => 52.00,
                'min' => 48.00,
                'max' => 55.00,
            ],
            [
                'name' => 'Highland Sweet Potato (Kamote)',
                'category' => 'Root Crops',
                'unit' => 'kg',
                'desc' => 'Firm, newly dug tubers with rich nutritional content from upland Linotan.',
                'price' => 35.00,
                'min' => 30.00,
                'max' => 40.00,
            ],
            [
                'name' => 'Carabao Mango (Sweet)',
                'category' => 'Fruits',
                'unit' => 'kaing',
                'desc' => 'Naturally sweetened export-grade mangoes from Calagdaan fruit orchards.',
                'price' => 95.00,
                'min' => 90.00,
                'max' => 105.00,
            ],
            [
                'name' => 'Saba Cooking Banana',
                'category' => 'Fruits',
                'unit' => 'kaing',
                'desc' => 'Cardaba cooking variety ideal for chips, cooking, and institutional feeding.',
                'price' => 30.00,
                'min' => 25.00,
                'max' => 35.00,
            ],
            [
                'name' => 'Freshwater Tilapia',
                'category' => 'Aquaculture',
                'unit' => 'kg',
                'desc' => 'Clean inland pond harvests from Cabas-an aquaculture farms.',
                'price' => 140.00,
                'min' => 130.00,
                'max' => 150.00,
            ],
        ];

        $commodityModels = [];
        foreach ($commoditiesData as $cd) {
            $cat = $categoryModels[$cd['category']];
            $comm = Commodity::firstOrCreate(
                ['name' => $cd['name'], 'category_id' => $cat->category_id],
                [
                    'unit_of_measure' => $cd['unit'],
                    'description' => $cd['desc'],
                ]
            );
            $commodityModels[$cd['name']] = $comm;

            // 7. PRICE_RECORDS (Table: PRICE_RECORDS)
            PriceRecord::firstOrCreate(
                [
                    'commodity_id' => $comm->commodity_id,
                    'recorded_date' => now()->toDateString(),
                ],
                [
                    'prevailing_price' => $cd['price'],
                    'min_price' => $cd['min'],
                    'max_price' => $cd['max'],
                    'market_location' => 'Cantilan Public Market',
                ]
            );
        }

        // 8. LISTINGS (Table: LISTINGS)
        if (Listing::count() === 0) {
            $listing1 = Listing::create([
                'farmer_id' => $farmer->user_id,
                'commodity_id' => $commodityModels['Highland Sweet Potato (Kamote)']->commodity_id,
                'title' => 'Highland Sweet Potato (Kamote)',
                'description' => 'Newly dug tubers from upland Linotan. Firm texture, suitable for boiling and market retail.',
                'price_per_unit' => 35.00,
                'available_quantity' => 120.00,
                'status' => 'active',
            ]);

            $listing2 = Listing::create([
                'farmer_id' => $farmer->user_id,
                'commodity_id' => $commodityModels['Carabao Mango (Sweet)']->commodity_id,
                'title' => 'Carabao Mango (Sweet)',
                'description' => 'Naturally ripened sweet mangoes from Calagdaan orchard. Available per kg or whole kaing.',
                'price_per_unit' => 95.00,
                'available_quantity' => 15.00,
                'status' => 'active',
            ]);

            $listing3 = Listing::create([
                'farmer_id' => $farmer->user_id,
                'commodity_id' => $commodityModels['Native Milled Rice (Dinorado)']->commodity_id,
                'title' => 'Native Milled Rice (Dinorado)',
                'description' => 'Harvested and milled in Magosilom agricultural zone. Unbleached, newly milled 50kg sacks.',
                'price_per_unit' => 52.00,
                'available_quantity' => 25.00,
                'status' => 'active',
            ]);

            $listing4 = Listing::create([
                'farmer_id' => $farmer->user_id,
                'commodity_id' => $commodityModels['Saba Cooking Banana']->commodity_id,
                'title' => 'Saba Cooking Banana',
                'description' => 'Cardaba cooking variety. High volume available for local vendors and processors.',
                'price_per_unit' => 30.00,
                'available_quantity' => 30.00,
                'status' => 'active',
            ]);

            $listing5 = Listing::create([
                'farmer_id' => $farmer->user_id,
                'commodity_id' => $commodityModels['Freshwater Tilapia']->commodity_id,
                'title' => 'Freshwater Tilapia',
                'description' => 'Fresh harvest tilapia from clean inland aquaculture ponds in Cabas-an.',
                'price_per_unit' => 140.00,
                'available_quantity' => 80.00,
                'status' => 'active',
            ]);

            // 9. LISTING_IMAGES (Table: LISTING_IMAGES)
            $listing1->images()->create([
                'image_url' => '/images/produce/sweet-potato.jpg',
                'is_primary' => true,
            ]);

            $listing3->images()->create([
                'image_url' => '/images/produce/native-rice.jpg',
                'is_primary' => true,
            ]);

            // 10. INQUIRIES & INQUIRY_MESSAGES (Tables: INQUIRIES & INQUIRY_MESSAGES)
            $inquiry = Inquiry::create([
                'listing_id' => $listing3->listing_id,
                'buyer_id' => $buyer->user_id,
                'status' => 'pending',
            ]);

            InquiryMessage::create([
                'inquiry_id' => $inquiry->inquiry_id,
                'sender_id' => $buyer->user_id,
                'message' => 'Hello Mang Pedro, interested in 10 sacks of Dinorado Rice for retail delivery on Friday.',
                'is_read' => false,
                'sent_at' => now()->subHours(2),
            ]);

            // 11. NOTIFICATIONS (Table: NOTIFICATIONS)
            Notification::create([
                'user_id' => $farmer->user_id,
                'inquiry_id' => $inquiry->inquiry_id,
                'title' => 'New Trade Inquiry Received',
                'message' => "Maria Santos submitted a trade inquiry for {$listing3->title}.",
                'is_read' => false,
            ]);

            // 12. MODERATION_REVIEWS (Table: MODERATION_REVIEWS)
            ModerationReview::create([
                'listing_id' => $listing3->listing_id,
                'admin_id' => $admin->user_id,
                'action' => 'approved',
                'reason' => 'DA standards and pricing compliance verified.',
                'reviewed_at' => now()->subDay(),
            ]);

            // 13. AUDIT_LOGS (Table: AUDIT_LOGS)
            AuditLog::log(
                $admin->user_id,
                'listing_moderation',
                'Listing',
                $listing3->listing_id,
                ['action' => 'approved', 'price' => 52.00]
            );
        }
    }
}

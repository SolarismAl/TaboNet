<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for the 12 relational domain tables.
     */
    public function up(): void
    {
        // 2. FARMER_PROFILES (Extension for Farmer Specific Data)
        Schema::create('farmer_profiles', function (Blueprint $table) {
            $table->id('profile_id');
            $table->foreignId('user_id')->unique()->constrained('users', 'user_id')->cascadeOnDelete();
            $table->string('farm_name', 150)->nullable();
            $table->text('farm_location');
            $table->string('farm_type', 100)->nullable(); // e.g., Crops, Poultry, Livestock, Organic
            $table->string('valid_id_url', 255)->nullable(); // For admin identity verification / RSBSA
            $table->text('bio')->nullable();
            $table->timestamps();
        });

        // 3. BUYER_PROFILES (Extension for Buyer Specific Data)
        Schema::create('buyer_profiles', function (Blueprint $table) {
            $table->id('profile_id');
            $table->foreignId('user_id')->unique()->constrained('users', 'user_id')->cascadeOnDelete();
            $table->string('business_name', 150)->nullable();
            $table->text('delivery_address');
            $table->string('buyer_type', 50)->nullable(); // Wholesaler, Retailer, Individual Consumer
            $table->timestamps();
        });

        // 4. CATEGORIES (Standardized Produce Groupings)
        Schema::create('categories', function (Blueprint $table) {
            $table->id('category_id');
            $table->string('name', 100)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 5. COMMODITIES (Standardized Produce Catalog)
        Schema::create('commodities', function (Blueprint $table) {
            $table->id('commodity_id');
            $table->foreignId('category_id')->constrained('categories', 'category_id')->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('unit_of_measure', 20); // e.g., kg, kaing, sako
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 6. PRICE_RECORDS (Municipal Spot Price Registry)
        Schema::create('price_records', function (Blueprint $table) {
            $table->id('price_record_id');
            $table->foreignId('commodity_id')->constrained('commodities', 'commodity_id')->cascadeOnDelete();
            $table->decimal('prevailing_price', 10, 2);
            $table->decimal('min_price', 10, 2)->nullable();
            $table->decimal('max_price', 10, 2)->nullable();
            $table->string('market_location', 150)->default('Cantilan Public Market');
            $table->date('recorded_date');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });

        // 7. LISTINGS (Farmer Produce Inventory / Offerings)
        Schema::create('listings', function (Blueprint $table) {
            $table->id('listing_id');
            $table->foreignId('farmer_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->foreignId('commodity_id')->constrained('commodities', 'commodity_id')->cascadeOnDelete();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->decimal('price_per_unit', 10, 2);
            $table->decimal('available_quantity', 10, 2);
            $table->string('status', 20)->default('active'); // active, sold_out, archived, pending_review
            $table->timestamps();
        });

        // 8. LISTING_IMAGES (Listing Visual Assets)
        Schema::create('listing_images', function (Blueprint $table) {
            $table->id('image_id');
            $table->foreignId('listing_id')->constrained('listings', 'listing_id')->cascadeOnDelete();
            $table->string('image_url', 255);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // 9. INQUIRIES (Buyer Expressions of Interest / Trade Dialogues)
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id('inquiry_id');
            $table->foreignId('listing_id')->constrained('listings', 'listing_id')->cascadeOnDelete();
            $table->foreignId('buyer_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->string('status', 20)->default('pending'); // pending, accepted, declined, completed
            $table->timestamps();
        });

        // 10. INQUIRY_MESSAGES (Negotiation Thread Messages)
        Schema::create('inquiry_messages', function (Blueprint $table) {
            $table->id('message_id');
            $table->foreignId('inquiry_id')->constrained('inquiries', 'inquiry_id')->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamps();
        });

        // 11. NOTIFICATIONS (System & Operational Alerts)
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('notification_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->foreignId('inquiry_id')->nullable()->constrained('inquiries', 'inquiry_id')->nullOnDelete();
            $table->string('title', 150);
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });

        // 12. MODERATION_REVIEWS (Municipal Oversight / Compliance Log)
        Schema::create('moderation_reviews', function (Blueprint $table) {
            $table->id('review_id');
            $table->foreignId('listing_id')->constrained('listings', 'listing_id')->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->string('action', 50); // approved, rejected, flagged
            $table->text('reason')->nullable();
            $table->timestamp('reviewed_at')->useCurrent();
            $table->timestamps();
        });

        // 13. AUDIT_LOGS (System-wide Accountability & Security Trail)
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->foreignId('admin_id')->nullable()->constrained('users', 'user_id')->nullOnDelete();
            $table->string('action_type', 50); // price_update, user_verified, listing_removed
            $table->string('target_entity', 50);
            $table->unsignedBigInteger('target_id');
            $table->json('details')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('moderation_reviews');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('inquiry_messages');
        Schema::dropIfExists('inquiries');
        Schema::dropIfExists('listing_images');
        Schema::dropIfExists('listings');
        Schema::dropIfExists('price_records');
        Schema::dropIfExists('commodities');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('buyer_profiles');
        Schema::dropIfExists('farmer_profiles');
    }
};

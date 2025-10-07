<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('business_name');
            $table->string('business_type');
            $table->string('business_registration_number')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->string('website')->nullable();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->enum('status', ['pending', 'active', 'suspended', 'inactive'])->default('pending');
            $table->enum('verification_status', ['unverified', 'pending', 'verified', 'rejected'])->default('unverified');
            $table->decimal('commission_rate', 5, 2)->default(0.00);
            $table->string('subscription_plan')->nullable();
            $table->timestamp('subscription_expires_at')->nullable();
            $table->decimal('total_earnings', 15, 2)->default(0.00);
            $table->integer('total_orders')->default(0);
            $table->decimal('rating', 3, 1)->default(0.0);
            $table->integer('review_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->json('bank_account_details')->nullable();
            $table->json('payment_methods')->nullable();
            $table->json('shipping_policies')->nullable();
            $table->json('return_policies')->nullable();
            $table->json('business_address')->nullable();
            $table->json('warehouse_addresses')->nullable();
            $table->json('operating_hours')->nullable();
            $table->json('languages_supported')->nullable();
            $table->string('currency_preference')->default('PHP');
            $table->string('timezone')->default('Asia/Manila');
            $table->json('notification_preferences')->nullable();
            $table->json('api_credentials')->nullable();
            $table->json('integration_settings')->nullable();
            $table->timestamps();

            $table->index(['status', 'verification_status']);
            $table->index(['is_featured', 'is_verified']);
            $table->index('subscription_plan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};
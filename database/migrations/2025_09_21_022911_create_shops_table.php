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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('category');
            $table->string('subcategory')->nullable();
            $table->enum('status', ['draft', 'active', 'suspended', 'inactive'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->decimal('rating', 3, 1)->default(0.0);
            $table->integer('review_count')->default(0);
            $table->integer('total_products')->default(0);
            $table->integer('total_sales')->default(0);
            $table->decimal('total_earnings', 15, 2)->default(0.00);
            $table->integer('followers_count')->default(0);
            $table->json('social_links')->nullable();
            $table->json('contact_info')->nullable();
            $table->json('shipping_info')->nullable();
            $table->json('return_policy')->nullable();
            $table->json('warranty_policy')->nullable();
            $table->json('custom_fields')->nullable();
            $table->json('seo_settings')->nullable();
            $table->json('theme_settings')->nullable();
            $table->json('layout_settings')->nullable();
            $table->json('analytics_settings')->nullable();
            $table->json('integration_settings')->nullable();
            $table->timestamps();

            $table->index(['status', 'is_featured']);
            $table->index(['category', 'subcategory']);
            $table->index('seller_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
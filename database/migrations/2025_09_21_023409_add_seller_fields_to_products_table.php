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
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('seller_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('shop_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('sku')->nullable()->unique();
            $table->string('barcode')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->json('dimensions')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->enum('condition', ['new', 'used', 'refurbished'])->default('new');
            $table->enum('status', ['draft', 'active', 'inactive', 'suspended'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_digital')->default(false);
            $table->string('download_link')->nullable();
            $table->json('tags')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('seo_keywords')->nullable();
            
            $table->index(['seller_id', 'status']);
            $table->index(['shop_id', 'status']);
            $table->index(['category', 'status']);
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['seller_id']);
            $table->dropForeign(['shop_id']);
            $table->dropColumn([
                'seller_id', 'shop_id', 'sku', 'barcode', 'weight', 'dimensions',
                'brand', 'model', 'condition', 'status', 'is_featured', 'is_digital',
                'download_link', 'tags', 'meta_title', 'meta_description', 'seo_keywords'
            ]);
        });
    }
};
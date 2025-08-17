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
            $table->text('review_summary')->nullable()->after('stock');
            $table->timestamp('review_summary_updated_at')->nullable()->after('review_summary');
            $table->decimal('average_rating', 3, 1)->default(0)->after('review_summary_updated_at');
            $table->unsignedInteger('review_count')->default(0)->after('average_rating');
            $table->unsignedInteger('positive_review_count')->default(0)->after('review_count');
            $table->unsignedInteger('negative_review_count')->default(0)->after('positive_review_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'review_summary',
                'review_summary_updated_at',
                'average_rating',
                'review_count',
                'positive_review_count',
                'negative_review_count'
            ]);
        });
    }
};
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
        Schema::table('trackings', function (Blueprint $table) {
            $table->string('carrier_code')->nullable()->after('carrier');
            $table->string('status_description')->nullable()->after('status');
            $table->timestamp('actual_delivery')->nullable()->after('estimated_delivery');
            $table->timestamp('shipped_at')->nullable()->after('actual_delivery');
            $table->json('shipping_address')->nullable()->after('history');
            $table->integer('delivery_attempts')->default(0)->after('shipping_address');
            $table->text('delivery_notes')->nullable()->after('delivery_attempts');
            $table->boolean('signature_required')->default(false)->after('delivery_notes');
            $table->boolean('signature_received')->default(false)->after('signature_required');
            $table->string('delivery_photo')->nullable()->after('signature_received');
            $table->decimal('weight', 8, 2)->nullable()->after('delivery_photo');
            $table->json('dimensions')->nullable()->after('weight');
            $table->decimal('shipping_cost', 10, 2)->nullable()->after('dimensions');
            $table->decimal('insurance_amount', 10, 2)->nullable()->after('shipping_cost');
            $table->text('special_instructions')->nullable()->after('insurance_amount');
            $table->string('return_tracking_number')->nullable()->after('special_instructions');
            $table->boolean('is_returned')->default(false)->after('return_tracking_number');
            $table->text('return_reason')->nullable()->after('is_returned');
            $table->string('updated_by')->nullable()->after('return_reason');
            $table->timestamp('last_updated_at')->nullable()->after('updated_by');

            $table->index(['carrier', 'status']);
            $table->index(['status', 'estimated_delivery']);
            $table->index('last_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trackings', function (Blueprint $table) {
            $table->dropColumn([
                'carrier_code', 'status_description', 'actual_delivery', 'shipped_at',
                'shipping_address', 'delivery_attempts', 'delivery_notes', 'signature_required',
                'signature_received', 'delivery_photo', 'weight', 'dimensions', 'shipping_cost',
                'insurance_amount', 'special_instructions', 'return_tracking_number',
                'is_returned', 'return_reason', 'updated_by', 'last_updated_at'
            ]);
        });
    }
};
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
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('delivery_confirmation_deadline')->nullable()->after('delivered_at');
            $table->enum('delivery_status', ['pending', 'delivered', 'confirmed_received', 'return_requested', 'auto_confirmed'])->default('pending')->after('delivery_confirmation_deadline');
            $table->timestamp('customer_response_at')->nullable()->after('delivery_status');
            $table->text('return_reason')->nullable()->after('customer_response_at');
            $table->enum('return_status', ['none', 'requested', 'approved', 'rejected', 'completed'])->default('none')->after('return_reason');
            $table->timestamp('return_requested_at')->nullable()->after('return_status');
            $table->timestamp('return_processed_at')->nullable()->after('return_requested_at');
            
            $table->index(['delivery_status', 'delivery_confirmation_deadline']);
            $table->index(['return_status', 'return_requested_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_confirmation_deadline',
                'delivery_status',
                'customer_response_at',
                'return_reason',
                'return_status',
                'return_requested_at',
                'return_processed_at'
            ]);
        });
    }
};

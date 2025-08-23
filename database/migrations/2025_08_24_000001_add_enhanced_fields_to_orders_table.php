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
            $table->string('order_number')->unique()->after('id');
            $table->string('contact_number')->nullable()->after('shipping_address');
            $table->string('email')->nullable()->after('contact_number');
            $table->text('notes')->nullable()->after('total');
            $table->timestamp('delivered_at')->nullable()->after('paid_at');
            $table->timestamp('refunded_at')->nullable()->after('delivered_at');
            $table->text('refund_reason')->nullable()->after('refunded_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_number',
                'contact_number',
                'email',
                'notes',
                'delivered_at',
                'refunded_at',
                'refund_reason'
            ]);
        });
    }
};

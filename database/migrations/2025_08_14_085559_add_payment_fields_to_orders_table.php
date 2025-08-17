<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentFieldsToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_status')->default('pending')->after('status');
            $table->string('payment_method')->nullable()->after('payment_status');
            $table->string('payment_reference')->nullable()->after('payment_method');
            $table->json('payment_details')->nullable()->after('payment_reference');
            $table->timestamp('paid_at')->nullable()->after('payment_details');
            $table->decimal('refund_amount', 10, 2)->nullable()->after('paid_at');
            $table->timestamp('refunded_at')->nullable()->after('refund_amount');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'payment_method',
                'payment_reference',
                'payment_details',
                'paid_at',
                'refund_amount',
                'refunded_at'
            ]);
        });
    }
}
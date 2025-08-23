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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->date('birth_date')->nullable()->after('phone');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('birth_date');
            
            // Address fields
            $table->string('address_line1')->nullable()->after('gender');
            $table->string('address_line2')->nullable()->after('address_line1');
            $table->string('city')->nullable()->after('address_line2');
            $table->string('state')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('state');
            $table->string('country')->nullable()->after('postal_code');
            
            // Additional profile fields
            $table->text('bio')->nullable()->after('country');
            $table->string('avatar')->nullable()->after('bio');
            $table->boolean('email_notifications')->default(true)->after('avatar');
            $table->boolean('sms_notifications')->default(false)->after('email_notifications');
            $table->string('preferred_language')->default('en')->after('sms_notifications');
            $table->string('timezone')->default('UTC')->after('preferred_language');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'birth_date', 'gender', 'address_line1', 'address_line2',
                'city', 'state', 'postal_code', 'country', 'bio', 'avatar',
                'email_notifications', 'sms_notifications', 'preferred_language', 'timezone'
            ]);
        });
    }
};

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
        Schema::table('bc_customers', function (Blueprint $table) {
            // Already referenced in BcCustomer::$fillable / sync code but never
            // had a column — filterCustomerDataByExistingColumns() was silently
            // dropping them on every sync.
            $table->string('display_name')->nullable()->after('name');
            $table->string('phone_number')->nullable()->after('phone');
            $table->string('profile_image_url')->nullable()->after('address');

            $table->string('mobile_phone_no')->nullable()->after('phone_number');
            $table->string('city')->nullable()->after('address');
            $table->string('payment_terms_code')->nullable()->after('city');
            $table->string('customer_price_group')->nullable()->after('payment_terms_code');
            $table->string('location_code')->nullable()->after('customer_price_group');
            $table->string('ship_to_code')->nullable()->after('location_code');
            $table->string('blocked')->nullable()->after('ship_to_code');
            $table->decimal('balance', 15, 2)->default(0)->after('blocked');
            $table->decimal('balance_due', 15, 2)->default(0)->after('balance');
            $table->decimal('credit_limit', 15, 2)->default(0)->after('balance_due');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bc_customers', function (Blueprint $table) {
            $table->dropColumn([
                'display_name',
                'phone_number',
                'profile_image_url',
                'mobile_phone_no',
                'city',
                'payment_terms_code',
                'customer_price_group',
                'location_code',
                'ship_to_code',
                'blocked',
                'balance',
                'balance_due',
                'credit_limit',
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {

            // VAT %
            if (!Schema::hasColumn('items', 'vat_percent')) {
                $table->decimal('vat_percent', 8, 2)->default(0)->after('unit_price');
            }

            // Tax amount
            if (!Schema::hasColumn('items', 'tax_amount')) {
                $table->decimal('tax_amount', 18, 2)->default(0)->after('vat_percent');
            }

            // Discount amount
            if (!Schema::hasColumn('items', 'discount_amount')) {
                $table->decimal('discount_amount', 18, 2)->default(0)->after('tax_amount');
            }

            // Discount start date
            if (!Schema::hasColumn('items', 'discount_start_date')) {
                $table->dateTime('discount_start_date')->nullable()->after('discount_amount');
            }

            // Discount end date
            if (!Schema::hasColumn('items', 'discount_end_date')) {
                $table->dateTime('discount_end_date')->nullable()->after('discount_start_date');
            }

        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {

            $columns = [];

            foreach ([
                'vat_percent',
                'tax_amount',
                'discount_amount',
                'discount_start_date',
                'discount_end_date'
            ] as $col) {
                if (Schema::hasColumn('items', $col)) {
                    $columns[] = $col;
                }
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }

        });
    }
};

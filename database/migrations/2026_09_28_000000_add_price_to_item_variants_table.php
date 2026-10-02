<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('item_variants', function (Blueprint $table) {
            // Null = the variant sells at its product's unit_price
            // (see Item::unitPriceFor()).
            $table->decimal('price', 18, 2)->nullable()->after('is_visible');
        });
    }

    public function down(): void
    {
        Schema::table('item_variants', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('item_variant_id')->nullable()
                ->after('item_id')
                ->constrained('item_variants')
                ->nullOnDelete();

            $table->string('variant_description')->nullable()
                ->after('item_name');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('item_variant_id');
            $table->dropColumn('variant_description');
        });
    }
};

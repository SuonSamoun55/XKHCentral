<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('bc_id');
            $table->string('number');
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->decimal('unit_price', 18, 2)->default(0);
            $table->decimal('inventory', 10, 2)->default(0);
            $table->boolean('blocked')->default(false);
            // Null = not set up yet (new sync, undecided). true/false = admin explicitly
            // chose to show/block it on the user-facing store.
            $table->boolean('is_visible')->nullable()->default(null);
            $table->boolean('allow_oversell')->default(false);
            $table->boolean('category_visible')->default(true);
            $table->string('item_category_code')->nullable();
            $table->foreignId('number_series_id')->nullable()->constrained('number_series')->nullOnDelete();
            // The real, sequentially-issued number from the item's tagged
            // number series (e.g. "ITM-0001") — separate from `number`,
            // which is always Business Central's own item number and gets
            // overwritten on every BC sync. Left alone by sync, so it
            // survives re-syncing untouched.
            $table->string('series_number')->nullable();
            $table->string('base_unit_of_measure_code')->nullable();
            $table->boolean('price_includes_tax')->default(false);
            $table->string('image_url')->nullable();
            $table->string('custom_image_url')->nullable();
            $table->string('tax_group_code')->nullable();
            $table->string('default_location_code')->nullable();
            $table->decimal('tax_amount', 18, 2)->default(0);
            $table->decimal('discount_amount', 18, 2)->default(0);
            $table->dateTime('discount_start_date')->nullable();
            $table->dateTime('discount_end_date')->nullable();
            $table->timestamps();
            $table->unique(['company_id', 'bc_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

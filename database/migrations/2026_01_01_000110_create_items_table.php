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
            $table->string('type')->nullable();
            $table->decimal('unit_price', 18, 2)->default(0);
            $table->integer('inventory')->default(0);
            $table->boolean('blocked')->default(false);
            $table->boolean('is_visible')->default(true);
            $table->boolean('category_visible')->default(true);
            $table->string('item_category_code')->nullable();
            $table->string('base_unit_of_measure_code')->nullable();
            $table->boolean('price_includes_tax')->default(false);
            $table->string('image_url')->nullable();
            $table->string('default_location_code')->nullable();
            $table->timestamps();
            $table->decimal('vat_percent', 5, 2)->nullable();
            $table->decimal('tax_amount', 18, 2)->default(0);
            $table->decimal('discount_amount', 18, 2)->default(0);
            $table->dateTime('discount_start_date')->nullable();
            $table->dateTime('discount_end_date')->nullable();
            $table->unique(['company_id', 'bc_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

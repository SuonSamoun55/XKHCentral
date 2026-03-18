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

            $table->string('bc_id')->unique();
            $table->string('number');
            $table->string('display_name')->nullable();

            $table->decimal('unit_price', 18, 2)->default(0);
            $table->integer('inventory')->default(0);

            $table->boolean('blocked')->default(false);

            $table->string('item_category_code')->nullable();
            $table->string('base_unit_of_measure_code')->nullable();

            $table->boolean('price_includes_tax')->default(false);

            $table->string('image_url')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

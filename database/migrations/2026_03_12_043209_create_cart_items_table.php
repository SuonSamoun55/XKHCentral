<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('cart_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('item_id')
                  ->constrained('items')
                  ->cascadeOnDelete();

            $table->string('item_no');

            $table->string('item_name')->nullable();

            $table->integer('qty')->default(1);

            $table->decimal('unit_price',18,2)->default(0);

            $table->decimal('line_total',18,2)->default(0);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};

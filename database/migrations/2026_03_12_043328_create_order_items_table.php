<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {

            $table->id();

            // 🔥 link to order
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->cascadeOnDelete();

            // 🔥 VERY IMPORTANT (multi-company)
            $table->foreignId('company_id')
                  ->constrained('companies')
                  ->cascadeOnDelete();

            // 🔥 link to item
            $table->foreignId('item_id')
                  ->constrained('items')
                  ->cascadeOnDelete();

            $table->string('item_no');
            $table->string('item_name')->nullable();

            $table->integer('qty')->default(1);

            $table->decimal('unit_price', 18, 2)->default(0);
            $table->decimal('line_total', 18, 2)->default(0);

            // 🔥 IMPORTANT for stock/location later
            $table->string('location_code')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

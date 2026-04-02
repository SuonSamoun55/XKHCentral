<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('order_histories', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('order_no')->unique()->index();
        $table->string('customer_no')->nullable();
        $table->decimal('total_amount', 15, 2);
        $table->string('status')->default('pending'); // pending, on-the-way, delivered, cancelled
        $table->text('items_summary')->nullable(); // Store a JSON snapshot of items
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_histories');
    }
};

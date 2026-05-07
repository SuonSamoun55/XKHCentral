<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('orders', function (Blueprint $table) {

        $table->id();

        // 🔥 REQUIRED
        $table->foreignId('company_id')
              ->constrained('companies')
              ->cascadeOnDelete();

        $table->string('order_no')->unique();

        $table->foreignId('user_id')
              ->constrained()
              ->cascadeOnDelete();

        // optional but recommended
        $table->string('customer_no')->nullable();

        $table->string('currency_code')->default('USD');
        $table->decimal('currency_factor',18,6)->default(1);

        $table->decimal('subtotal',18,2)->default(0);
        $table->decimal('discount_amount',18,2)->default(0);
        $table->decimal('total_amount',18,2)->default(0);
        $table->decimal('amount_paid', 10, 2)->nullable()->after('total_amount');

        // 🔥 IMPORTANT for POS
        $table->string('location_code')->nullable();

        $table->string('status')->default('pending');
        // pending, completed, cancelled

        $table->string('sync_status')->default('pending');
        // pending, synced, failed

        $table->string('bc_document_no')->nullable();

        $table->timestamp('checked_out_at')->nullable();

        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

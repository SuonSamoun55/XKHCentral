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
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('order_no')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('customer_no')->nullable();
            $table->string('currency_code')->default('USD');
            $table->decimal('currency_factor', 18, 6)->default(1);
            $table->decimal('subtotal', 18, 2)->default(0);
            $table->decimal('discount_amount', 18, 2)->default(0);
            $table->decimal('total_amount', 18, 2)->default(0);
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->string('location_code')->nullable();
            $table->string('status')->default('pending');
            $table->string('sync_status')->default('pending');
            $table->string('bc_order_id')->nullable()->index();
            $table->string('bc_document_no')->nullable();
            $table->string('bc_invoice_no')->nullable()->index();
            $table->timestamp('bc_last_synced_at')->nullable();
            $table->text('bc_sync_error')->nullable();
            $table->string('invoice_document_path')->nullable();
            $table->string('invoice_document_name')->nullable();
            $table->string('document_type')->default('commercial');
            $table->timestamp('checked_out_at')->nullable();
            $table->timestamps();
            $table->string('bc_status')->nullable()->comment('Business Central status');
            $table->timestamp('shipped_at')->nullable()->comment('When order was shipped');
            $table->timestamp('last_synced_at')->nullable()->comment('Last sync time with BC');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

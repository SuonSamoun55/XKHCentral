<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bc_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->string('bc_id')->nullable();
            $table->string('bc_customer_no');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('connect_status')->default('not_connected');
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
            $table->unique(['company_id', 'bc_customer_no']);
        });

        Schema::create('company_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('tenant_id');
            $table->string('client_id');
            $table->text('client_secret');
            $table->string('company_bc_id');
            $table->string('environment')->nullable();
            $table->text('base_url')->nullable();
            $table->text('token_url')->nullable();
            $table->string('api_scope')->nullable();
            $table->text('customers_endpoint')->nullable();
            $table->text('items_endpoint')->nullable();
            $table->text('sales_orders_endpoint')->nullable();
            $table->text('sales_order_lines_endpoint')->nullable();
            $table->text('sales_order_lines_by_document_endpoint')->nullable();
            $table->text('sales_order_post_status_endpoint')->nullable();
            $table->text('sales_orders_by_number_endpoint')->nullable();
            $table->text('sales_order_pdf_endpoint')->nullable();
            $table->text('posted_sales_invoice_endpoint')->nullable();
            $table->text('posted_sales_invoice_lines_endpoint')->nullable();
            $table->text('posted_sales_invoice_pdf_endpoint')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_connections');
        Schema::dropIfExists('bc_customers');
    }
};

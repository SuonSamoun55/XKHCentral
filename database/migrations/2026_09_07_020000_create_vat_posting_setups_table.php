<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vat_posting_setups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('bc_id');

            $table->string('vat_bus_posting_group')->nullable();
            $table->string('vat_prod_posting_group')->nullable();
            $table->string('description')->nullable();
            $table->boolean('blocked')->default(false);
            $table->string('vat_identifier')->nullable();
            $table->decimal('vat_pct', 9, 2)->default(0);
            $table->string('vat_calculation_type')->nullable();
            $table->string('unrealized_vat_type')->nullable();
            $table->boolean('adjust_for_payment_discount')->default(false);
            $table->string('sales_vat_account')->nullable();
            $table->string('sales_vat_unreal_account')->nullable();
            $table->string('purchase_vat_account')->nullable();
            $table->string('purch_vat_unreal_account')->nullable();
            $table->string('reverse_chrg_vat_acc')->nullable();
            $table->string('reverse_chrg_vat_unreal_acc')->nullable();
            $table->string('vat_clause_code')->nullable();
            $table->boolean('eu_service')->default(false);
            $table->boolean('certificate_of_supply_required')->default(false);
            $table->string('tax_category')->nullable();

            $table->timestamps();

            $table->unique(['company_id', 'bc_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vat_posting_setups');
    }
};

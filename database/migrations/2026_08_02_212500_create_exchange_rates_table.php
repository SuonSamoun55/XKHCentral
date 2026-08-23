<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->string('currency_code');
            $table->string('relational_currency_code');
            $table->decimal('exchange_rate_amount', 15, 6)->nullable();
            $table->decimal('relational_exchange_rate_amount', 15, 6)->nullable();
            $table->decimal('adjustment_exchange_rate_amount', 15, 6)->nullable();
            $table->decimal('relational_adjustment_exch_rate_amt', 15, 6)->nullable();
            $table->date('starting_date');
            $table->timestamp('last_fetched_at')->nullable();
            $table->timestamps();
            $table->unique(['company_id', 'currency_code', 'starting_date']);
            $table->index(['currency_code', 'starting_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
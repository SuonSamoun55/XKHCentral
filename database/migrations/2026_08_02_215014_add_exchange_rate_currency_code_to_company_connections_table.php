<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_connections', function (Blueprint $table) {
            if (!Schema::hasColumn('company_connections', 'exchange_rate_currency_code')) {
                $table->string('exchange_rate_currency_code', 10)
                    ->nullable()
                    ->default('USD')
                    ->after('exchange_rate_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('company_connections', function (Blueprint $table) {
            if (Schema::hasColumn('company_connections', 'exchange_rate_currency_code')) {
                $table->dropColumn('exchange_rate_currency_code');
            }
        });
    }
};
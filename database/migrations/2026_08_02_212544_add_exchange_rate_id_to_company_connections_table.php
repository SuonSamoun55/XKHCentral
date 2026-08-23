<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_connections', function (Blueprint $table) {
            if (!Schema::hasColumn('company_connections', 'exchange_rate_id')) {
                $table->string('exchange_rate_id')->nullable()->after('api_scope');
            }
        });
    }

    public function down(): void
    {
        Schema::table('company_connections', function (Blueprint $table) {
            if (Schema::hasColumn('company_connections', 'exchange_rate_id')) {
                $table->dropColumn('exchange_rate_id');
            }
        });
    }
};
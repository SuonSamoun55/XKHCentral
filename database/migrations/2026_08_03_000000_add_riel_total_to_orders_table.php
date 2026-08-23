<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('riel_exchange_rate', 18, 6)->nullable()->after('amount_paid');
            $table->decimal('total_amount_riel', 18, 2)->nullable()->after('riel_exchange_rate');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['riel_exchange_rate', 'total_amount_riel']);
        });
    }
};

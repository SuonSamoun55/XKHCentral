<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_connections', function (Blueprint $table) {
            $table->text('item_variants_endpoint')->nullable()->after('items_endpoint');
        });
    }

    public function down(): void
    {
        Schema::table('company_connections', function (Blueprint $table) {
            $table->dropColumn('item_variants_endpoint');
        });
    }
};

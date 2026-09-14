<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('report_settings', function (Blueprint $table) {
            // Matches the existing show_logo/show_address/... pattern — the
            // company name was the one field in that group with no toggle.
            $table->boolean('show_company_name')->default(true)->after('show_logo');

            // Off by default: the current receipt layout has no image
            // column, so turning this on is an opt-in layout change.
            $table->boolean('show_item_image')->default(false)->after('show_vat_column');
        });
    }

    public function down(): void
    {
        Schema::table('report_settings', function (Blueprint $table) {
            $table->dropColumn(['show_company_name', 'show_item_image']);
        });
    }
};

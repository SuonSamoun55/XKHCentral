<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('report_settings', function (Blueprint $table) {
            // On by default — unlike show_item_image, this isn't a layout
            // change, it's a data column already shown elsewhere in the app.
            $table->boolean('show_unit_column')->default(true)->after('show_vat_column');
        });
    }

    public function down(): void
    {
        Schema::table('report_settings', function (Blueprint $table) {
            $table->dropColumn('show_unit_column');
        });
    }
};

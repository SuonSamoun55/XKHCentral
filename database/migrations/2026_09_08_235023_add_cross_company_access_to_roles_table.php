<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            // When on, staff carrying this role can manage/assign staff
            // roles in ANY company, not just the one they're scoped to.
            $table->boolean('is_cross_company')->default(false)->after('display_name');
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('is_cross_company');
        });
    }
};

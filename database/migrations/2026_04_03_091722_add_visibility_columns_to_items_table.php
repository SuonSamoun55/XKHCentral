<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // From Business Central
            $table->string('type')->nullable()->after('display_name');

            // Your visibility controls
            $table->boolean('is_visible')->default(true)->after('blocked');
            $table->boolean('category_visible')->default(true)->after('is_visible');
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'is_visible',
                'category_visible'
            ]);
        });
    }
};

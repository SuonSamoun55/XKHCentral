<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Existing rows keep their current true/false value (already decided).
        // Only new items synced from now on default to NULL — "not set up yet" —
        // so the store admin can choose to show or block before a customer sees them.
        DB::statement('ALTER TABLE items MODIFY is_visible TINYINT(1) NULL DEFAULT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('UPDATE items SET is_visible = 1 WHERE is_visible IS NULL');
        DB::statement('ALTER TABLE items MODIFY is_visible TINYINT(1) NOT NULL DEFAULT 1');
    }
};

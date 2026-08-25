<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * item_variants.bc_id was globally unique — fine for one company, but
     * two companies pointing at the same Business Central company (or any
     * bc_id collision) get handed the same variant id from BC, so the
     * second company's sync could never insert its own copy: every insert
     * hit a duplicate-key error, silently failing the whole sync request.
     * Scope uniqueness to (item_id, bc_id) instead — same fix already
     * applied to items via unique(['company_id', 'bc_id']).
     */
    public function up(): void
    {
        Schema::table('item_variants', function (Blueprint $table) {
            $table->dropUnique(['bc_id']);
            $table->unique(['item_id', 'bc_id']);
        });
    }

    public function down(): void
    {
        Schema::table('item_variants', function (Blueprint $table) {
            $table->dropUnique(['item_id', 'bc_id']);
            $table->unique('bc_id');
        });
    }
};

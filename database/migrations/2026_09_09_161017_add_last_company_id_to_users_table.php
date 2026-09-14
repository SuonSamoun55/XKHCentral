<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Persists a cross-company admin's last-selected company so
            // login doesn't re-prompt the company picker every time —
            // separate from company_id, which pins a company-scoped user
            // to one company and must never be touched by this.
            $table->foreignId('last_company_id')->nullable()->after('company_id')
                ->constrained('companies')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('last_company_id');
        });
    }
};

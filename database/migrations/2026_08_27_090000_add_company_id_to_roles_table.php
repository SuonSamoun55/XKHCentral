<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('id')
                ->constrained('companies')->nullOnDelete();
        });

        // Roles were created before "which company owns this role" existed
        // as a concept. Assign the existing ones to whichever company is
        // actually using them (the original/earliest company) so they keep
        // working exactly as before, instead of becoming invisible to
        // everyone once roles start being scoped per company.
        $firstCompanyId = DB::table('companies')->orderBy('id')->value('id');
        if ($firstCompanyId) {
            DB::table('roles')->whereNull('company_id')->update(['company_id' => $firstCompanyId]);
        }

        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique(['name']);
            $table->unique(['company_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'name']);
            $table->unique('name');
            $table->dropConstrainedForeignId('company_id');
        });
    }
};

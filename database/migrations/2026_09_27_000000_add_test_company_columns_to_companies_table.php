<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Marks a company created by "Clone as test company" so the list
            // can badge it and deleting it also removes the users/customers
            // that were copied into it (see CompanyController::destroy).
            $table->boolean('is_test')->default(false)->after('is_active');
            $table->foreignId('cloned_from_id')->nullable()->after('is_test')
                ->constrained('companies')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cloned_from_id');
            $table->dropColumn('is_test');
        });
    }
};

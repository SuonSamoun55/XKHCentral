<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
                    // The tag added to the staff emails copied into a test company
                    // exactly as typed (admin@gmail.com + ".xkh" -> admin.xkh@gmail.com), so cloning that test
                    // company again can swap the tag instead of stacking a second one.
            $table->string('staff_email_tag', 20)->nullable()->after('cloned_from_id');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('staff_email_tag');
        });
    }
};

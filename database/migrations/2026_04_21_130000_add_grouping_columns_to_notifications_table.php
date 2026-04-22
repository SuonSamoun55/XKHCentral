<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'group_key')) {
                $table->string('group_key', 120)->nullable()->after('category');
                $table->index('group_key');
            }

            if (!Schema::hasColumn('notifications', 'is_group_summary')) {
                $table->boolean('is_group_summary')->default(false)->after('group_key');
                $table->index('is_group_summary');
            }

            if (!Schema::hasColumn('notifications', 'unread_count')) {
                $table->unsignedInteger('unread_count')->default(1)->after('is_group_summary');
            }
        });
    }

    public function down(): void
    {
        $indexes = [];

        if (Schema::hasColumn('notifications', 'group_key')) {
            $indexes[] = 'group_key';
        }

        if (Schema::hasColumn('notifications', 'is_group_summary')) {
            $indexes[] = 'is_group_summary';
        }

        if (!empty($indexes)) {
            Schema::table('notifications', function (Blueprint $table) use ($indexes) {
                foreach ($indexes as $index) {
                    try {
                        $table->dropIndex([$index]);
                    } catch (\Throwable $e) {
                        // Ignore index drop failures.
                    }
                }
            });
        }

        Schema::table('notifications', function (Blueprint $table) {
            $toDrop = [];

            foreach (['group_key', 'is_group_summary', 'unread_count'] as $column) {
                if (Schema::hasColumn('notifications', $column)) {
                    $toDrop[] = $column;
                }
            }

            if (!empty($toDrop)) {
                $table->dropColumn($toDrop);
            }
        });
    }
};

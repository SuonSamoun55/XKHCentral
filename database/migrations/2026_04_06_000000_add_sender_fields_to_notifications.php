<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('notifications', 'sender_id')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->unsignedBigInteger('sender_id')->nullable()->after('user_id');
            });
        }

        if (!Schema::hasColumn('notifications', 'sender_name')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->string('sender_name')->nullable()->after('sender_id');
            });
        }

        if (!Schema::hasColumn('notifications', 'sender_profile_image')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->string('sender_profile_image')->nullable()->after('sender_name');
            });
        }

        if (Schema::hasColumn('notifications', 'sender_id')) {
            try {
                Schema::table('notifications', function (Blueprint $table) {
                    $table->foreign('sender_id')->references('id')->on('users')->nullOnDelete();
                });
            } catch (\Throwable $e) {
                // Ignore if foreign key already exists.
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('notifications', 'sender_id')) {
            try {
                Schema::table('notifications', function (Blueprint $table) {
                    $table->dropForeign(['sender_id']);
                });
            } catch (\Throwable $e) {
                // Ignore if foreign key does not exist.
            }
        }

        $columnsToDrop = [];
        foreach (['sender_id', 'sender_name', 'sender_profile_image'] as $column) {
            if (Schema::hasColumn('notifications', $column)) {
                $columnsToDrop[] = $column;
            }
        }

        if (!empty($columnsToDrop)) {
            Schema::table('notifications', function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            });
        }
    }
};

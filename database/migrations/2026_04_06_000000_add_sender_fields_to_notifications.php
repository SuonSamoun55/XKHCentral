<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('sender_id')->nullable()->after('user_id');
            $table->string('sender_name')->nullable()->after('sender_id');
            $table->string('sender_profile_image')->nullable()->after('sender_name');
            
            $table->foreign('sender_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['sender_id']);
            $table->dropColumn(['sender_id', 'sender_name', 'sender_profile_image']);
        });
    }
};

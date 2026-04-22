<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->string('message_type', 20)->default('text')->after('message');
            $table->string('attachment_path')->nullable()->after('message_type');
            $table->string('attachment_mime', 100)->nullable()->after('attachment_path');
            $table->unsignedInteger('attachment_size')->nullable()->after('attachment_mime');
        });
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn([
                'message_type',
                'attachment_path',
                'attachment_mime',
                'attachment_size',
            ]);
        });
    }
};

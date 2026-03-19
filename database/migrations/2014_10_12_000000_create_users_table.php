<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // BC link
            $table->string('bc_customer_no')->unique();

            // user info (from BC)
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();

            // login
            $table->string('password');
            $table->string('role')->default('customer');

            // status
            $table->boolean('status')->default(true);
            $table->timestamp('linked_at')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

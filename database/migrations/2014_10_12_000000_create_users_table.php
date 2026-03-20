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
$table->unsignedBigInteger('company_id')->nullable();
$table->string('bc_customer_no');
$table->string('name');
$table->string('email')->nullable();
$table->string('phone')->nullable();
$table->string('password');
$table->string('role')->default('customer');
$table->boolean('status')->default(true);
$table->timestamp('linked_at')->nullable();
$table->rememberToken();
$table->timestamps();

$table->unique(['company_id', 'bc_customer_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('bc_customers', function (Blueprint $table) {
   $table->id();
$table->unsignedBigInteger('company_id')->nullable();
$table->string('bc_id')->nullable();
$table->string('bc_customer_no');
$table->string('name');
$table->string('email')->nullable();
$table->string('phone')->nullable();
$table->string('address')->nullable();
$table->string('connect_status')->default('not_connected');
$table->timestamp('last_synced_at')->nullable();
$table->timestamps();

$table->unique(['company_id', 'bc_customer_no']);
});
    }

    public function down(): void
    {
        Schema::dropIfExists('bc_customers');
    }
};

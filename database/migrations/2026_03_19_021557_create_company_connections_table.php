<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();

            $table->string('tenant_id');
            $table->string('client_id');
            $table->text('client_secret');
            $table->string('company_bc_id');

            $table->string('environment')->nullable();
            $table->text('base_url')->nullable();
            $table->text('token_url')->nullable();

            $table->boolean('is_default')->default(false);
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_connections');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('buyer_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('source', 20);
            $table->integer('quantity_change');
            $table->integer('old_inventory')->default(0);
            $table->integer('new_inventory')->default(0);
            $table->timestamp('happened_at')->nullable()->index();
            $table->string('reference_no')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->index(['company_id', 'item_id']);
            $table->index(['company_id', 'source']);
        });

        Schema::create('bc_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->string('order_no');
            $table->string('bc_document_no');
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->string('result')->default('checked');
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bc_sync_logs');
        Schema::dropIfExists('inventory_movements');
    }
};

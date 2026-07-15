<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();

            $table->string('bc_id');           // variant SystemId from BC
            $table->string('item_number');     // matches items.number
            $table->string('code');            // variant code, e.g. RED
            $table->string('description')->nullable();
            $table->string('description2')->nullable();

            $table->boolean('blocked')->default(false);
            $table->boolean('sales_blocked')->default(false);
            $table->boolean('purchasing_blocked')->default(false);
            $table->boolean('is_visible')->default(true);

            $table->string('image_url')->nullable();
            $table->timestamps();

            $table->unique('bc_id');
            $table->unique(['item_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_variants');
    }
};
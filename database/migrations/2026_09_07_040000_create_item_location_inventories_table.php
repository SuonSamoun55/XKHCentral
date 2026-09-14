<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_location_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->string('location_code')->nullable();
            $table->string('location_name')->nullable();
            $table->integer('inventory')->default(0);
            $table->timestamps();

            $table->unique(['item_id', 'location_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_location_inventories');
    }
};

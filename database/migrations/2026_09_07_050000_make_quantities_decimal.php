<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE cart_items MODIFY qty DECIMAL(10,2) NOT NULL DEFAULT 1');
        DB::statement('ALTER TABLE order_items MODIFY qty DECIMAL(10,2) NOT NULL DEFAULT 1');
        DB::statement('ALTER TABLE items MODIFY inventory DECIMAL(10,2) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE inventory_movements MODIFY quantity_change DECIMAL(10,2) NOT NULL');
        DB::statement('ALTER TABLE inventory_movements MODIFY old_inventory DECIMAL(10,2) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE inventory_movements MODIFY new_inventory DECIMAL(10,2) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE item_location_inventories MODIFY inventory DECIMAL(10,2) NOT NULL DEFAULT 0');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE cart_items MODIFY qty INT NOT NULL DEFAULT 1');
        DB::statement('ALTER TABLE order_items MODIFY qty INT NOT NULL DEFAULT 1');
        DB::statement('ALTER TABLE items MODIFY inventory INT NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE inventory_movements MODIFY quantity_change INT NOT NULL');
        DB::statement('ALTER TABLE inventory_movements MODIFY old_inventory INT NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE inventory_movements MODIFY new_inventory INT NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE item_location_inventories MODIFY inventory INT NOT NULL DEFAULT 0');
    }
};

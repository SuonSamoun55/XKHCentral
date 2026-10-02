<?php

use App\Models\POS\OrderItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Each order line keeps its own copy of the product picture from when it was
// ordered (see OrderItem::snapshotImage), so replacing a product picture in
// Product Management no longer changes old orders.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('image_path', 500)->nullable()->after('variant_description');
        });

        // Existing orders: freeze the picture they show today.
        OrderItem::with('item', 'itemVariant')
            ->whereNull('image_path')
            ->chunkById(200, function ($lines) {
                foreach ($lines as $line) {
                    $line->image_path = OrderItem::snapshotImage($line->item, $line->itemVariant);
                    $line->saveQuietly();
                }
            });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('user_id')
                ->constrained('companies')->nullOnDelete();
            $table->index(['user_id', 'status', 'company_id']);
        });

        // Best-effort backfill for existing carts: infer the company from
        // whichever company the cart's own items already belong to, so a
        // cart that was never touched under more than one company keeps
        // working exactly as before. A cart with no items, or items from
        // more than one company (the very bug this migration fixes), is
        // left with company_id = null and will simply start a fresh
        // company-scoped cart the next time something is added.
        DB::table('carts')->whereNull('company_id')->orderBy('id')->each(function ($cart) {
            $companyId = DB::table('cart_items')
                ->join('items', 'items.id', '=', 'cart_items.item_id')
                ->where('cart_items.cart_id', $cart->id)
                ->distinct()
                ->pluck('items.company_id');

            if ($companyId->count() === 1) {
                DB::table('carts')->where('id', $cart->id)->update(['company_id' => $companyId->first()]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropIndex(['user_id', 'status', 'company_id']);
            $table->dropColumn('company_id');
        });
    }
};

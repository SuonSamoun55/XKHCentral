<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// Approval Entries show this note in "Details". The wording no longer names
// Business Central, so existing entries get the same text as new ones.
return new class extends Migration
{
    private const OLD_NOTE = 'Order confirmed by admin and stored in Business Central Sales Order.';
    private const NEW_NOTE = 'Order confirmed by admin and sent as a sales order.';

    public function up(): void
    {
        DB::table('order_actions')->where('note', self::OLD_NOTE)->update(['note' => self::NEW_NOTE]);
    }

    public function down(): void
    {
        DB::table('order_actions')->where('note', self::NEW_NOTE)->update(['note' => self::OLD_NOTE]);
    }
};

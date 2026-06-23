<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $email = strtolower(trim(env('SUPPORT_ADMIN_EMAIL', 'extricatesupportcoltd@gmail.com')));
        $password = env('SUPPORT_ADMIN_PASSWORD', 'extricatesupportcoltd@885*#%');
        $now = now();
        $existing = DB::table('users')->where('email', $email)->first();

        $payload = [
            'name' => 'xtricate Support',
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'role_id' => DB::table('roles')->where('name', 'admin')->value('id'),
            'status' => true,
            'bc_customer_no' => $existing->bc_customer_no ?? 'SUPPORT-ADMIN-001',
            'updated_at' => $now,
        ];

        if ($existing) {
            DB::table('users')->where('id', $existing->id)->update($payload);

            return;
        }

        DB::table('users')->insert(array_merge($payload, [
            'linked_at' => $now,
            'created_at' => $now,
        ]));
    }

    public function down(): void
    {
        DB::table('users')
            ->where('email', strtolower(trim(env('SUPPORT_ADMIN_EMAIL', 'extricatesupportcoltd@gmail.com'))))
            ->delete();
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $email = env('SUPPORT_ADMIN_EMAIL', 'extricatesupportcoltd@gmail.com');
        $plainPassword = env('SUPPORT_ADMIN_PASSWORD', 'extricatesupportcoltd@885*#%');
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        $now = now();

        $existing = DB::table('users')->where('email', $email)->first();

        $payload = [
            'name' => 'Extricate Support',
            'email' => $email,
            'password' => Hash::make($plainPassword),
            'role' => 'admin',
            'role_id' => $adminRoleId,
            'status' => true,
            'bc_customer_no' => $existing->bc_customer_no ?? 'SUPPORT-ADMIN-001',
            'updated_at' => $now,
        ];

        if ($existing) {
            DB::table('users')
                ->where('id', $existing->id)
                ->update($payload);
            return;
        }

        DB::table('users')->insert(array_merge($payload, [
            'created_at' => $now,
            'linked_at' => $now,
        ]));
    }

    public function down(): void
    {
        $email = env('SUPPORT_ADMIN_EMAIL', 'extricatesupportcoltd@gmail.com');

        DB::table('users')
            ->where('email', $email)
            ->delete();
    }
};

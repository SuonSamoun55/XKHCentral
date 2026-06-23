<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SupportAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('SUPPORT_ADMIN_EMAIL', 'extricatesupportcoltd@gmail.com');
        $plainPassword = env('SUPPORT_ADMIN_PASSWORD', 'extricatesupportcoltd@885*#%');
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        $existing = DB::table('users')->where('email', $email)->first();
        $now = now();

        $payload = [
            'name' => 'xtricate Support',
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
}

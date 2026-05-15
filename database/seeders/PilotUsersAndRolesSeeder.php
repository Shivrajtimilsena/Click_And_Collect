<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PilotUsersAndRolesSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // SYSTEM ADMIN
        DB::table('user')->updateOrInsert(
            ['email' => 'admin@clickcollect.local'],
            [
                'full_name' => 'System Admin',
                'phone_no' => '9800000001',
                'age' => 30,
                'password' => 'Admin123@',
                'dob' => '1995-01-01',
                'verification_code' => 'ADMIN-VERIFIED',
                'status' => 'ACTIVE',
                'role' => 'ADMIN',
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $adminUserId = DB::table('user')->where('email', 'admin@clickcollect.local')->value('user_id');

        DB::table('admin')->updateOrInsert(
            ['user_id' => $adminUserId],
            [
                'access_level' => 'SYSTEM',
                'last_login' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        // 5 TRADERS — one for each shop
        $traders = [
            [
                'full_name' => 'Butcher Trader',
                'email' => 'butcher@clickcollect.local',
                'phone_no' => '9800000101',
                'shop_type' => 'BUTCHER',
            ],
            [
                'full_name' => 'Greengrocer Trader',
                'email' => 'greengrocer@clickcollect.local',
                'phone_no' => '9800000102',
                'shop_type' => 'GREENGROCER',
            ],
            [
                'full_name' => 'Fishmonger Trader',
                'email' => 'fishmonger@clickcollect.local',
                'phone_no' => '9800000103',
                'shop_type' => 'FISHMONGER',
            ],
            [
                'full_name' => 'Bakery Trader',
                'email' => 'bakery@clickcollect.local',
                'phone_no' => '9800000104',
                'shop_type' => 'BAKERY',
            ],
            [
                'full_name' => 'Delicatessen Trader',
                'email' => 'deli@clickcollect.local',
                'phone_no' => '9800000105',
                'shop_type' => 'DELICATESSEN',
            ],
        ];

        foreach ($traders as $index => $trader) {
            DB::table('user')->updateOrInsert(
                ['email' => $trader['email']],
                [
                    'full_name' => $trader['full_name'],
                    'phone_no' => $trader['phone_no'],
                    'age' => 28 + $index,
                    'password' => 'trader@123',
                    'dob' => '1996-01-01',
                    'verification_code' => strtoupper($trader['shop_type']).'-VERIFIED',
                    'status' => 'ACTIVE',
                    'role' => 'TRADER',
                    'email_verified_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            $userId = DB::table('user')->where('email', $trader['email'])->value('user_id');

            DB::table('trader')->updateOrInsert(
                ['user_id' => $userId],
                [
                    'shop_type' => $trader['shop_type'],
                    'logo_url' => null,
                    'is_active' => 'Y',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        // 2 TEST CUSTOMERS
        $customers = [
            [
                'full_name' => 'Customer One',
                'email' => 'customer1@clickcollect.local',
                'phone_no' => '9800000201',
            ],
            [
                'full_name' => 'Customer Two',
                'email' => 'customer2@clickcollect.local',
                'phone_no' => '9800000202',
            ],
        ];

        foreach ($customers as $customer) {
            DB::table('user')->updateOrInsert(
                ['email' => $customer['email']],
                [
                    'full_name' => $customer['full_name'],
                    'phone_no' => $customer['phone_no'],
                    'age' => 24,
                    'password' => 'customer@123',
                    'dob' => '2001-01-01',
                    'verification_code' => 'CUSTOMER-VERIFIED',
                    'status' => 'ACTIVE',
                    'role' => 'CUSTOMER',
                    'email_verified_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            $userId = DB::table('user')->where('email', $customer['email'])->value('user_id');

            DB::table('customer')->updateOrInsert(
                ['user_id' => $userId],
                [
                    'loyalty_points' => 0,
                    'is_active' => 'Y',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}

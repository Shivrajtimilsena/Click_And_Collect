<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PilotUsersAndRolesSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // SYSTEM ADMIN
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@clickcollect.local'],
            [
                'full_name'         => 'System Admin',
                'phone_no'          => '9800000001',
                'age'               => 30,
                'password'          => Hash::make('Admin123@'),
                'dob'               => '1995-01-01',
                'verification_code' => 'ADMIN-VERIFIED',
                'status'            => 'ACTIVE',
                'role'              => 'ADMIN',
                'email_verified_at' => $now,
                'created_at'        => $now,
                'updated_at'        => $now,
            ]
        );

        $adminUserId = DB::table('users')->where('email', 'admin@clickcollect.local')->value('user_id');

        DB::table('admins')->updateOrInsert(
            ['user_id' => $adminUserId],
            [
                'access_level' => 'SYSTEM',
                'last_login'   => $now,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]
        );

        // PILOT TRADERS
        $traders = [
            [
                'full_name'  => 'Butcher Trader',
                'email'      => 'butcher@clickcollect.local',
                'phone_no'   => '9800000101',
                'shop_type'  => 'BUTCHER',
            ],
            [
                'full_name'  => 'Greengrocer Trader',
                'email'      => 'greengrocer@clickcollect.local',
                'phone_no'   => '9800000102',
                'shop_type'  => 'GREENGROCER',
            ],
            [
                'full_name'  => 'Fishmonger Trader',
                'email'      => 'fishmonger@clickcollect.local',
                'phone_no'   => '9800000103',
                'shop_type'  => 'FISHMONGER',
            ],
            [
                'full_name'  => 'Bakery Trader',
                'email'      => 'bakery@clickcollect.local',
                'phone_no'   => '9800000104',
                'shop_type'  => 'BAKERY',
            ],
            [
                'full_name'  => 'Delicatessen Trader',
                'email'      => 'deli@clickcollect.local',
                'phone_no'   => '9800000105',
                'shop_type'  => 'DELICATESSEN',
            ],
        ];

        foreach ($traders as $index => $trader) {
            DB::table('users')->updateOrInsert(
                ['email' => $trader['email']],
                [
                    'full_name'         => $trader['full_name'],
                    'phone_no'          => $trader['phone_no'],
                    'age'               => 28 + $index,
                    'password'          => Hash::make('Trader123@'),
                    'dob'               => '1996-01-01',
                    'verification_code' => strtoupper($trader['shop_type']) . '-VERIFIED',
                    'status'            => 'ACTIVE',
                    'role'              => 'TRADER',
                    'email_verified_at' => $now,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ]
            );

            $userId = DB::table('users')->where('email', $trader['email'])->value('user_id');

            DB::table('traders')->updateOrInsert(
                ['user_id' => $userId],
                [
                    'shop_type'  => $trader['shop_type'],
                    'logo_url'   => null,
                    'is_active'  => 'Y',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        // SAMPLE CUSTOMERS
        $customers = [
            [
                'full_name' => 'Customer One',
                'email'     => 'customer1@clickcollect.local',
                'phone_no'  => '9800000201',
            ],
            [
                'full_name' => 'Customer Two',
                'email'     => 'customer2@clickcollect.local',
                'phone_no'  => '9800000202',
            ],
        ];

        foreach ($customers as $customer) {
            DB::table('users')->updateOrInsert(
                ['email' => $customer['email']],
                [
                    'full_name'         => $customer['full_name'],
                    'phone_no'          => $customer['phone_no'],
                    'age'               => 24,
                    'password'          => Hash::make('Customer123@'),
                    'dob'               => '2001-01-01',
                    'verification_code' => 'CUSTOMER-VERIFIED',
                    'status'            => 'ACTIVE',
                    'role'              => 'CUSTOMER',
                    'email_verified_at' => $now,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ]
            );

            $userId = DB::table('users')->where('email', $customer['email'])->value('user_id');

            DB::table('customers')->updateOrInsert(
                ['user_id' => $userId],
                [
                    'loyalty_points' => 0,
                    'is_active'      => 'Y',
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ]
            );
        }
    }
}
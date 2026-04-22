<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PilotCollectionSlotSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $slotTemplates = [
            ['slot_label' => '10:00-13:00', 'start_time' => '10:00', 'end_time' => '13:00'],
            ['slot_label' => '13:00-16:00', 'start_time' => '13:00', 'end_time' => '16:00'],
            ['slot_label' => '16:00-19:00', 'start_time' => '16:00', 'end_time' => '19:00'],
        ];

        for ($i = 1; $i <= 28; $i++) {
            $date = Carbon::now()->addDays($i);

            if (!in_array($date->format('D'), ['Wed', 'Thu', 'Fri'])) {
                continue;
            }

            foreach ($slotTemplates as $slot) {
                DB::table('collection_slots')->updateOrInsert(
                    [
                        'slot_date'  => $date->toDateString(),
                        'slot_label' => $slot['slot_label'],
                    ],
                    [
                        'slot_day'    => $date->format('l'),
                        'start_time'  => $slot['start_time'],
                        'end_time'    => $slot['end_time'],
                        'capacity'    => 20,
                        'total_order' => 0,
                        'is_active'   => 'Y',
                        'created_at'  => $now,
                        'updated_at'  => $now,
                    ]
                );
            }
        }
    }
}
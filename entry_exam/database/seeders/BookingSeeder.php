<?php

namespace Database\Seeders;

use App\Models\Hotel;
use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('bookings')->truncate();
        Schema::enableForeignKeyConstraints();

        $hotels = Hotel::all();

        if ($hotels->isEmpty()) {
            return;
        }

        $sampleBookings = [
            [
                'customer_name' => '山田 太郎 (Taro Yamada)',
                'customer_contact' => '090-1234-5678',
                'checkin_time' => '2026-10-01 15:00:00',
                'checkout_time' => '2026-10-03 10:00:00',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ],
            [
                'customer_name' => '佐藤 花子 (Hanako Sato)',
                'customer_contact' => '080-9876-5432',
                'checkin_time' => '2026-10-05 14:00:00',
                'checkout_time' => '2026-10-07 11:00:00',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ],
            [
                'customer_name' => '鈴木 一郎 (Ichiro Suzuki)',
                'customer_contact' => '070-1111-2222',
                'checkin_time' => '2026-10-10 16:00:00',
                'checkout_time' => '2026-10-12 10:00:00',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ],
            [
                'customer_name' => '高橋 美咲 (Misaki Takahashi)',
                'customer_contact' => '090-3333-4444',
                'checkin_time' => '2026-10-15 15:00:00',
                'checkout_time' => '2026-10-18 10:00:00',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ],
            [
                'customer_name' => 'John Doe',
                'customer_contact' => '+1-555-0199',
                'checkin_time' => '2026-11-01 15:00:00',
                'checkout_time' => '2026-11-05 10:00:00',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ],
        ];

        foreach ($sampleBookings as $index => $booking) {
            $hotel = $hotels->get($index % $hotels->count());
            $booking['hotel_id'] = $hotel->hotel_id;
            DB::table('bookings')->insert($booking);
        }
    }
}

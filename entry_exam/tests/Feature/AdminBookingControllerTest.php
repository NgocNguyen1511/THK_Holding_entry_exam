<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Prefecture;
use Database\Seeders\BookingSeeder;
use Database\Seeders\HotelSeeder;
use Database\Seeders\PrefectureSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBookingControllerTest extends TestCase
{
    use RefreshDatabase;

    private Hotel $hotel;

    protected function setUp(): void
    {
        parent::setUp();

        $prefecture = Prefecture::create([
            'prefecture_name' => '東京都',
            'prefecture_name_alpha' => 'tokyo',
        ]);

        $this->hotel = Hotel::create([
            'hotel_name' => 'Tokyo Bay Resort',
            'prefecture_id' => $prefecture->prefecture_id,
        ]);

        Booking::create([
            'hotel_id' => $this->hotel->hotel_id,
            'customer_name' => '山田 太郎',
            'customer_contact' => '090-1234-5678',
            'checkin_time' => '2026-10-01 15:00:00',
            'checkout_time' => '2026-10-03 10:00:00',
        ]);

        Booking::create([
            'hotel_id' => $this->hotel->hotel_id,
            'customer_name' => '佐藤 花子',
            'customer_contact' => 'sato@example.com',
            'checkin_time' => '2026-11-01 15:00:00',
            'checkout_time' => '2026-11-05 10:00:00',
        ]);
    }

    public function testShowBookingSearchScreenReturnsSearchPage(): void
    {
        $response = $this->get(route('adminBookingSearchPage'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.booking.search');
        $response->assertSee('予約情報検索画面');
    }

    public function testSearchResultScreenDisplaysResults(): void
    {
        $response = $this->get(route('adminBookingSearchResult'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.booking.result');
        $response->assertViewHas('bookings');
        $response->assertSee('山田 太郎');
        $response->assertSee('佐藤 花子');
        $response->assertSee('Tokyo Bay Resort');
    }

    public function testFilterBookingsByCustomerName(): void
    {
        $response = $this->get(route('adminBookingSearchResult', [
            'customer_name' => '山田',
        ]));

        $response->assertStatus(200);
        $response->assertSee('山田 太郎');
        $response->assertDontSee('佐藤 花子');
    }

    public function testFilterBookingsByCustomerContact(): void
    {
        $response = $this->get(route('adminBookingSearchResult', [
            'customer_contact' => 'sato@example.com',
        ]));

        $response->assertStatus(200);
        $response->assertSee('佐藤 花子');
        $response->assertDontSee('山田 太郎');
    }

    public function testFilterBookingsByCheckinAndCheckoutTime(): void
    {
        $response = $this->get(route('adminBookingSearchResult', [
            'checkin_time' => '2026-10-01',
            'checkout_time' => '2026-10-04',
        ]));

        $response->assertStatus(200);
        $response->assertSee('山田 太郎');
        $response->assertDontSee('佐藤 花子');
    }

    public function testBookingSeederInsertsSampleBookings(): void
    {
        $this->seed(PrefectureSeeder::class);
        $this->seed(HotelSeeder::class);
        $this->seed(BookingSeeder::class);

        $this->assertDatabaseHas('bookings', [
            'customer_name' => '山田 太郎 (Taro Yamada)',
        ]);
        $this->assertGreaterThan(0, Booking::count());
    }
}

<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Prefecture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AdminHotelControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Prefecture::create([
            'prefecture_name' => '東京都',
            'prefecture_name_alpha' => 'tokyo',
            'file_path' => 'prefecture/tokyo.png',
        ]);

        Prefecture::create([
            'prefecture_name' => '大阪府',
            'prefecture_name_alpha' => 'osaka',
            'file_path' => 'prefecture/osaka.png',
        ]);
    }

    public function testShowSearchPageReturnsSuccessfulView(): void
    {
        $response = $this->get(route('adminHotelSearchPage'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.hotel.search');
        $response->assertViewHas('prefectures');
    }

    public function testShowCreatePageReturnsSuccessfulView(): void
    {
        $response = $this->get(route('adminHotelCreatePage'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.hotel.create');
        $response->assertViewHas('prefectures');
    }

    public function testSearchResultFiltersHotelsByNameAndPrefecture(): void
    {
        $tokyo = Prefecture::where('prefecture_name_alpha', 'tokyo')->first();
        $osaka = Prefecture::where('prefecture_name_alpha', 'osaka')->first();

        Hotel::create([
            'hotel_name' => 'Tokyo Grand Hotel',
            'prefecture_id' => $tokyo->prefecture_id,
        ]);

        Hotel::create([
            'hotel_name' => 'Osaka Bay Hotel',
            'prefecture_id' => $osaka->prefecture_id,
        ]);

        $response = $this->get(route('adminHotelSearchResult', [
            'hotel_name' => 'Tokyo',
            'prefecture_id' => $tokyo->prefecture_id,
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('admin.hotel.result');
        $response->assertViewHas('hotelList', function ($hotelList) {
            return $hotelList->count() === 1 && $hotelList->first()->hotel_name === 'Tokyo Grand Hotel';
        });
        $hotel = Hotel::where('hotel_name', 'Tokyo Grand Hotel')->first();
        $response->assertSee(route('hotelDetail', ['hotel_id' => $hotel->hotel_id]));
    }

    public function testCreateHotelStoresRecordAndRedirects(): void
    {
        $tokyo = Prefecture::where('prefecture_name_alpha', 'tokyo')->first();

        $response = $this->post(route('adminHotelCreateProcess'), [
            'hotel_name' => 'New Luxury Hotel',
            'prefecture_id' => $tokyo->prefecture_id,
        ]);

        $response->assertRedirect(route('adminHotelCreatePage'));
        $response->assertSessionHas('success', __('hotel.created_success'));

        $this->assertDatabaseHas('hotels', [
            'hotel_name' => 'New Luxury Hotel',
            'prefecture_id' => $tokyo->prefecture_id,
        ]);
    }

    public function testCreateHotelWithFileUploadStoresImage(): void
    {
        $tokyo = Prefecture::where('prefecture_name_alpha', 'tokyo')->first();
        $fakeFile = UploadedFile::fake()->image('hotel.png', 300, 300);

        $response = $this->post(route('adminHotelCreateProcess'), [
            'hotel_name' => 'Hotel With Image',
            'prefecture_id' => $tokyo->prefecture_id,
            'file_path' => $fakeFile,
        ]);

        $response->assertRedirect(route('adminHotelCreatePage'));
        $response->assertSessionHas('success', __('hotel.created_success'));

        $hotel = Hotel::where('hotel_name', 'Hotel With Image')->first();
        $this->assertNotNull($hotel);
        $this->assertNotNull($hotel->file_path);

        $uploadedPath = public_path('assets/img/'.$hotel->file_path);
        $this->assertTrue(File::exists($uploadedPath));

        if (File::exists($uploadedPath)) {
            File::delete($uploadedPath);
        }
    }

    public function testDeleteHotelRemovesRecord(): void
    {
        $tokyo = Prefecture::where('prefecture_name_alpha', 'tokyo')->first();
        $hotel = Hotel::create([
            'hotel_name' => 'Hotel To Delete',
            'prefecture_id' => $tokyo->prefecture_id,
        ]);

        $response = $this->post(route('adminHotelDeleteProcess'), [
            'hotel_id' => $hotel->hotel_id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('hotels', [
            'hotel_id' => $hotel->hotel_id,
        ]);
    }

    public function testCreateHotelValidationFailsWhenRequiredFieldsMissing(): void
    {
        $response = $this->post(route('adminHotelCreateProcess'), []);

        $response->assertSessionHasErrors(['hotel_name', 'prefecture_id']);
    }

    public function testDeleteHotelValidationFailsWhenHotelDoesNotExist(): void
    {
        $response = $this->post(route('adminHotelDeleteProcess'), [
            'hotel_id' => 999999,
        ]);

        $response->assertSessionHasErrors(['hotel_id']);
    }
}

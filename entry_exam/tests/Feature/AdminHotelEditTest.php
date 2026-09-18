<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Prefecture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AdminHotelEditTest extends TestCase
{
    use RefreshDatabase;

    private Prefecture $tokyo;

    private Prefecture $osaka;

    private Hotel $hotel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tokyo = Prefecture::create([
            'prefecture_name' => '東京都',
            'prefecture_name_alpha' => 'tokyo',
            'file_path' => 'prefecture/tokyo.png',
        ]);

        $this->osaka = Prefecture::create([
            'prefecture_name' => '大阪府',
            'prefecture_name_alpha' => 'osaka',
            'file_path' => 'prefecture/osaka.png',
        ]);

        $this->hotel = Hotel::create([
            'hotel_name' => 'Initial Tokyo Hotel',
            'prefecture_id' => $this->tokyo->prefecture_id,
            'file_path' => null,
        ]);
    }

    public function testShowEditScreenDisplaysHotelAndPrefectures(): void
    {
        $response = $this->get(route('adminHotelEditPage', ['hotel_id' => $this->hotel->hotel_id]));

        $response->assertStatus(200);
        $response->assertViewIs('admin.hotel.edit');
        $response->assertViewHas('hotel', function ($hotel) {
            return $hotel->hotel_id === $this->hotel->hotel_id;
        });
        $response->assertViewHas('prefectures');
        $response->assertSee('Initial Tokyo Hotel');
    }

    public function testEditConfirmValidationFailsOnMissingData(): void
    {
        $response = $this->post(route('adminHotelEditConfirm'), [
            'hotel_id' => $this->hotel->hotel_id,
            'hotel_name' => '',
            'prefecture_id' => '',
        ]);

        $response->assertSessionHasErrors(['hotel_name', 'prefecture_id']);
    }

    public function testEditConfirmRendersConfirmationViewWithoutImage(): void
    {
        $response = $this->post(route('adminHotelEditConfirm'), [
            'hotel_id' => $this->hotel->hotel_id,
            'hotel_name' => 'Updated Tokyo Hotel Name',
            'prefecture_id' => $this->osaka->prefecture_id,
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('admin.hotel.edit-confirm');
        $response->assertViewHas('hotelName', 'Updated Tokyo Hotel Name');
        $response->assertViewHas('prefectureId', $this->osaka->prefecture_id);
        $response->assertSee('Updated Tokyo Hotel Name');
        $response->assertSee('大阪府');
    }

    public function testEditConfirmUploadsTempImageAndRendersConfirmationView(): void
    {
        $fakeImage = UploadedFile::fake()->image('new_hotel.png', 400, 300);

        $response = $this->post(route('adminHotelEditConfirm'), [
            'hotel_id' => $this->hotel->hotel_id,
            'hotel_name' => 'Updated Tokyo Hotel Name',
            'prefecture_id' => $this->tokyo->prefecture_id,
            'file_path' => $fakeImage,
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('admin.hotel.edit-confirm');
        $tempPath = $response->viewData('tempFilePath');

        $this->assertNotNull($tempPath);
        $this->assertStringStartsWith('hotel/temp/', $tempPath);

        $tempFullPath = public_path('assets/img/'.$tempPath);
        $this->assertTrue(File::exists($tempFullPath));

        if (File::exists($tempFullPath)) {
            File::delete($tempFullPath);
        }
    }

    public function testEditCompleteUpdatesHotelWithoutChangingImage(): void
    {
        $response = $this->post(route('adminHotelEditComplete'), [
            'hotel_id' => $this->hotel->hotel_id,
            'hotel_name' => 'Final Confirmed Name',
            'prefecture_id' => $this->osaka->prefecture_id,
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('admin.hotel.edit-complete');
        $response->assertSee('Final Confirmed Name');

        $this->assertDatabaseHas('hotels', [
            'hotel_id' => $this->hotel->hotel_id,
            'hotel_name' => 'Final Confirmed Name',
            'prefecture_id' => $this->osaka->prefecture_id,
        ]);
    }

    public function testEditCompletePromotesTempImageAndCleansOldImage(): void
    {
        // 1. Create initial dummy image file
        $oldImageDir = public_path('assets/img/hotel');
        if (! File::exists($oldImageDir)) {
            File::makeDirectory($oldImageDir, 0755, true);
        }
        $oldImagePath = $oldImageDir.'/old_image.png';
        File::put($oldImagePath, 'dummy-old-image');

        $this->hotel->update(['file_path' => 'hotel/old_image.png']);

        // 2. Upload temp image
        $tempDir = public_path('assets/img/hotel/temp');
        if (! File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }
        $tempFileName = 'temp_'.time().'.png';
        File::put($tempDir.'/'.$tempFileName, 'dummy-temp-image');

        // 3. Complete edit
        $response = $this->post(route('adminHotelEditComplete'), [
            'hotel_id' => $this->hotel->hotel_id,
            'hotel_name' => 'Hotel With Replaced Image',
            'prefecture_id' => $this->tokyo->prefecture_id,
            'temp_file_path' => 'hotel/temp/'.$tempFileName,
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('admin.hotel.edit-complete');

        $updatedHotel = Hotel::find($this->hotel->hotel_id);
        $this->assertEquals('Hotel With Replaced Image', $updatedHotel->hotel_name);
        $this->assertEquals('hotel/'.$tempFileName, $updatedHotel->file_path);

        // Old file deleted
        $this->assertFalse(File::exists($oldImagePath));

        // New promoted file exists
        $newPermanentPath = public_path('assets/img/'.$updatedHotel->file_path);
        $this->assertTrue(File::exists($newPermanentPath));

        if (File::exists($newPermanentPath)) {
            File::delete($newPermanentPath);
        }
    }
}

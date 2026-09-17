<?php

namespace Tests\Feature\Admin;

use App\Models\Hotel;
use App\Models\Prefecture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class HotelCreateTest extends TestCase
{
    use RefreshDatabase;

    private Prefecture $prefecture;

    protected function setUp(): void
    {
        parent::setUp();

        $this->prefecture = Prefecture::create([
            'prefecture_name' => 'Tokyo',
            'prefecture_name_alpha' => 'tokyo',
            'file_path' => 'prefecture/tokyo.png',
        ]);
    }

    /**
     * Test hotel create page loads successfully.
     */
    public function test_admin_hotel_create_page_can_be_rendered(): void
    {
        $response = $this->get(route('adminHotelCreatePage'));

        $response->assertStatus(200);
        $response->assertSee('Create New Hotel');
        $response->assertSee('Tokyo');
        $response->assertViewHas('prefectures');
    }

    /**
     * Test hotel creation fails when required fields are missing.
     */
    public function test_hotel_creation_fails_when_required_fields_are_missing(): void
    {
        $response = $this->from(route('adminHotelCreatePage'))
            ->post(route('adminHotelCreateProcess'), []);

        $response->assertRedirect(route('adminHotelCreatePage'));
        $response->assertSessionHasErrors(['hotel_name', 'prefecture_id']);

        $this->assertDatabaseCount('hotels', 0);
    }

    /**
     * Test hotel creation fails when hotel_name exceeds 255 characters.
     */
    public function test_hotel_creation_fails_when_hotel_name_exceeds_255_chars(): void
    {
        $response = $this->from(route('adminHotelCreatePage'))
            ->post(route('adminHotelCreateProcess'), [
                'hotel_name' => str_repeat('a', 256),
                'prefecture_id' => $this->prefecture->prefecture_id,
            ]);

        $response->assertRedirect(route('adminHotelCreatePage'));
        $response->assertSessionHasErrors(['hotel_name']);

        $this->assertDatabaseCount('hotels', 0);
    }

    /**
     * Test hotel creation fails when prefecture does not exist in DB.
     */
    public function test_hotel_creation_fails_when_prefecture_does_not_exist(): void
    {
        $response = $this->from(route('adminHotelCreatePage'))
            ->post(route('adminHotelCreateProcess'), [
                'hotel_name' => 'Sample Hotel',
                'prefecture_id' => 9999,
            ]);

        $response->assertRedirect(route('adminHotelCreatePage'));
        $response->assertSessionHasErrors(['prefecture_id']);

        $this->assertDatabaseCount('hotels', 0);
    }

    /**
     * Test hotel creation fails when uploaded file is not an image.
     */
    public function test_hotel_creation_fails_when_file_is_not_an_image(): void
    {
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->from(route('adminHotelCreatePage'))
            ->post(route('adminHotelCreateProcess'), [
                'hotel_name' => 'Sample Hotel',
                'prefecture_id' => $this->prefecture->prefecture_id,
                'file' => $file,
            ]);

        $response->assertRedirect(route('adminHotelCreatePage'));
        $response->assertSessionHasErrors(['file']);

        $this->assertDatabaseCount('hotels', 0);
    }

    /**
     * Test hotel creation fails when uploaded image exceeds 2MB limit.
     */
    public function test_hotel_creation_fails_when_image_exceeds_max_size(): void
    {
        $file = UploadedFile::fake()->image('large.jpg')->size(3000); // 3MB

        $response = $this->from(route('adminHotelCreatePage'))
            ->post(route('adminHotelCreateProcess'), [
                'hotel_name' => 'Sample Hotel',
                'prefecture_id' => $this->prefecture->prefecture_id,
                'file' => $file,
            ]);

        $response->assertRedirect(route('adminHotelCreatePage'));
        $response->assertSessionHasErrors(['file']);

        $this->assertDatabaseCount('hotels', 0);
    }

    /**
     * Test hotel is successfully created without an image.
     */
    public function test_can_create_hotel_without_image(): void
    {
        $response = $this->post(route('adminHotelCreateProcess'), [
            'hotel_name' => 'Tokyo Station Hotel',
            'prefecture_id' => $this->prefecture->prefecture_id,
        ]);

        $response->assertRedirect(route('adminHotelCreatePage'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('hotels', [
            'hotel_name' => 'Tokyo Station Hotel',
            'prefecture_id' => $this->prefecture->prefecture_id,
            'file_path' => null,
        ]);
    }

    /**
     * Test hotel is successfully created with an image upload.
     */
    public function test_can_create_hotel_with_image(): void
    {
        $file = UploadedFile::fake()->image('hotel_cover.jpg', 640, 480);

        $response = $this->post(route('adminHotelCreateProcess'), [
            'hotel_name' => 'Hotel Grand Tokyo',
            'prefecture_id' => $this->prefecture->prefecture_id,
            'file' => $file,
        ]);

        $response->assertRedirect(route('adminHotelCreatePage'));
        $response->assertSessionHas('success');

        $hotel = Hotel::where('hotel_name', 'Hotel Grand Tokyo')->first();
        $this->assertNotNull($hotel);
        $this->assertNotNull($hotel->file_path);
        $this->assertStringStartsWith('hotel/', $hotel->file_path);

        $savedFilePath = public_path('assets/img/' . $hotel->file_path);
        $this->assertFileExists($savedFilePath);

        // Clean up test file
        if (file_exists($savedFilePath)) {
            unlink($savedFilePath);
        }
    }
}

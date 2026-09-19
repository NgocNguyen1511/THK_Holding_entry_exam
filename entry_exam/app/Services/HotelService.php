<?php

namespace App\Services;

use App\Models\Hotel;
use Illuminate\Http\UploadedFile;

class HotelService
{
    public function __construct(
        private Hotel $hotel,
        private FileService $fileService,
    ) {}

    public function createHotel(array $attributes, ?UploadedFile $file = null): Hotel
    {
        $filePath = $this->fileService->handleUploadedImage($file, 'hotel');

        return $this->hotel->create([
            'hotel_name' => $attributes['hotel_name'],
            'prefecture_id' => $attributes['prefecture_id'],
            'file_path' => $filePath,
        ]);
    }

    public function updateHotel(int $hotelId, array $attributes, ?string $newFilePath = null): Hotel
    {
        $hotel = $this->hotel->findOrFail($hotelId);

        $filePath = $hotel->file_path;

        if ($newFilePath && $newFilePath !== $hotel->file_path && file_exists(public_path('assets/img/'.$newFilePath))) {
            $this->fileService->deleteImage($hotel->file_path);
            $filePath = $newFilePath;
        }

        $hotel->update([
            'hotel_name' => $attributes['hotel_name'],
            'prefecture_id' => $attributes['prefecture_id'],
            'file_path' => $filePath,
            'updated_at' => now(),
        ]);

        return $hotel;
    }

    public function deleteHotel(int $hotelId): bool
    {
        $hotel = $this->hotel->find($hotelId);

        if (! $hotel) {
            return false;
        }

        $this->fileService->deleteImage($hotel->file_path);

        return (bool) $hotel->delete();
    }

    public function searchHotels(?string $hotelName = null, ?int $prefectureId = null): array
    {
        return $this->hotel->getHotelList($hotelName, $prefectureId);
    }
}
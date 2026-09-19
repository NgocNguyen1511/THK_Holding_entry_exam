<?php

namespace App\Services;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Collection;
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

    public function updateHotel(int $hotelId, array $attributes, ?string $tempFilePath = null): Hotel
    {
        $hotel = $this->hotel->findOrFail($hotelId);

        $filePath = $hotel->file_path;

        if ($tempFilePath && file_exists(public_path('assets/img/'.$tempFilePath))) {
            $this->fileService->deleteImage($hotel->file_path);
            $filePath = $this->fileService->promoteTempImage($tempFilePath, 'hotel');
        }

        $hotel->update([
            'hotel_name' => $attributes['hotel_name'],
            'prefecture_id' => $attributes['prefecture_id'],
            'file_path' => $filePath,
        ]);

        return $hotel;
    }

    public function handleTempUploadedImage(?UploadedFile $file): ?string
    {
        return $this->fileService->handleTempUploadedImage($file);
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
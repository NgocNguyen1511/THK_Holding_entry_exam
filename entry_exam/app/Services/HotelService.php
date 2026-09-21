<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\Prefecture;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class HotelService
{
    public function __construct(
        private Hotel $hotel,
        private FileService $fileService,
    ) {}

    public function createHotel(array $attributes, ?UploadedFile $file = null): Hotel
    {
        $filePath = $this->fileService->upload($file, 'img/hotel');

        return $this->hotel->create([
            'hotel_name' => $attributes['hotel_name'],
            'prefecture_id' => $attributes['prefecture_id'],
            'file_path' => $filePath,
        ]);
    }

    public function updateHotel(int $hotelId, array $attributes): Hotel
    {
        $hotel = $this->hotel->findOrFail($hotelId);
        $filePath = $hotel->file_path;

        if (($isNew = isset($attributes['file_path']) && $attributes['file_path'] instanceof UploadedFile) || (array_key_exists('file_path', $attributes) && empty($attributes['file_path']))) {
            $hotel->file_path && $this->fileService->delete('img/' . $hotel->file_path);
            $filePath = $isNew ? str_replace('img/', '', $this->fileService->upload($attributes['file_path'], 'img/hotel')) : null;
        }

        $hotel->update([
            'hotel_name' => $attributes['hotel_name'],
            'prefecture_id' => $attributes['prefecture_id'],
            'file_path' => $filePath,
        ]);

        return $hotel;
    }

    public function deleteHotel(int $hotelId): bool
    {
        $hotel = $this->hotel->find($hotelId);

        if (!$hotel) {
            return false;
        }

        $this->fileService->delete('img/' . $hotel->file_path);

        return (bool) $hotel->delete();
    }

    public function searchHotels(?string $hotelName = null, ?int $prefectureId = null): array
    {
        return $this->hotel->getHotelList($hotelName, $prefectureId);
    }
}

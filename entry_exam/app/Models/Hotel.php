<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hotel extends Model
{
    /**
     * @var string
     */
    protected $primaryKey = 'hotel_id';

    /**
     * @var array
     */
    protected $guarded = ['hotel_id'];

    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(Prefecture::class, 'prefecture_id', 'prefecture_id');
    }

    /**
     * @param string $hotelName
     * @param integer|null $prefectureId
     * @return array
     */
    public function getHotelList(string $hotelName, ?int $prefectureId = null): array
    {
        return $this->with('prefecture')
            ->when($hotelName, function ($query, string $name): void {
                $query->where('hotel_name', 'LIKE', '%' . addcslashes($name, '%_') . '%');
            })
            ->when($prefectureId, function ($query, int $prefId): void {
                $query->where('prefecture_id', $prefId);
            })
            ->get()
            ->toArray();
    }

    /**
     * @param string $hotelName
     * @return array
     */
    public static function getHotelListByName(string $hotelName): array
    {
        return static::where('hotel_name', 'LIKE', '%' . addcslashes($hotelName, '%_') . '%')
            ->with('prefecture')
            ->get()
            ->toArray();
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}

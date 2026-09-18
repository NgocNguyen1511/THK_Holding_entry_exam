<?php

namespace App\Models;

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

    /**
     * @return BelongsTo
     */
    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(Prefecture::class, 'prefecture_id', 'prefecture_id');
    }

    /**
     * Search hotel by hotel name
     *
     * @param string $hotelName
     * @return array
     */
    public static function getHotelListByName(string $hotelName): array
    {
        $result = Hotel::where('hotel_name', 'LIKE', '%' . $hotelName . '%')
            ->with('prefecture')
            ->get()
            ->toArray();

        return $result;
    }

    public static function getHotelList(?string $hotelName, ?int $prefectureId): array
    {
        return self::with('prefecture')
            ->when($hotelName, function ($query, $name) {
                $query->where('hotel_name', 'LIKE', '%' . addcslashes($name, '%_') . '%');
            })
            ->when($prefectureId, function ($query, $prefId) {
                $query->where('prefecture_id', $prefId);
            })
            ->get()
            ->toArray();
    }

    /**
     * Override serializeDate method to customize date format
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}

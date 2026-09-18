<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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

    public function scopeSearch(Builder $query, ?string $hotelName = null, ?int $prefectureId = null): Builder
    {
        return $query->with('prefecture')
            ->when($hotelName, function (Builder $q, string $name): void {
                $q->where('hotel_name', 'LIKE', '%'.addcslashes($name, '%_').'%');
            })
            ->when($prefectureId, function (Builder $q, int $prefId): void {
                $q->where('prefecture_id', $prefId);
            });
    }

    public function getHotelList(?string $hotelName = null, ?int $prefectureId = null): Collection
    {
        return $this->search($hotelName, $prefectureId)->get();
    }

    public static function getHotelListByName(string $hotelName): Collection
    {
        return static::where('hotel_name', 'LIKE', '%'.addcslashes($hotelName, '%_').'%')
            ->with('prefecture')
            ->get();
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}

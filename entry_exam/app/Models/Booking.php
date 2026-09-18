<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $primaryKey = 'booking_id';

    protected $guarded = ['booking_id'];

    protected function casts(): array
    {
        return [
            'checkin_time' => 'datetime',
            'checkout_time' => 'datetime',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotel_id');
    }

    public function scopeSearch(
        Builder $query,
        ?string $customerName = null,
        ?string $customerContact = null,
        ?string $checkinTime = null,
        ?string $checkoutTime = null
    ): Builder {
        return $query->with('hotel')
            ->when($customerName, function (Builder $q, string $name): void {
                $q->where('customer_name', 'LIKE', '%'.addcslashes($name, '%_').'%');
            })
            ->when($customerContact, function (Builder $q, string $contact): void {
                $q->where('customer_contact', 'LIKE', '%'.addcslashes($contact, '%_').'%');
            })
            ->when($checkinTime, function (Builder $q, string $checkin): void {
                $q->where('checkin_time', '>=', $checkin);
            })
            ->when($checkoutTime, function (Builder $q, string $checkout): void {
                $q->where('checkout_time', '<=', $checkout);
            });
    }

    public function getBookingList(
        ?string $customerName = null,
        ?string $customerContact = null,
        ?string $checkinTime = null,
        ?string $checkoutTime = null
    ): Collection {
        return $this->search($customerName, $customerContact, $checkinTime, $checkoutTime)
            ->latest('booking_id')
            ->get();
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}

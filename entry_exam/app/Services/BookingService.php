<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Collection;

class BookingService
{
    public function __construct(
        private Booking $booking
    ) {}

    public function searchBookings(
        ?string $customerName = null,
        ?string $customerContact = null,
        ?string $checkinTime = null,
        ?string $checkoutTime = null
    ): Collection {
        return $this->booking->getBookingList(
            $customerName,
            $customerContact,
            $checkinTime,
            $checkoutTime
        );
    }
}

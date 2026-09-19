<?php

namespace App\Services;

use App\Models\Booking;

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
    ): array {
        return $this->booking->getBookingList(
            $customerName,
            $customerContact,
            $checkinTime,
            $checkoutTime
        );
    }
}

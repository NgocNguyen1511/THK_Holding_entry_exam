<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchBookingRequest;
use App\Services\BookingService;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService,
    ) {}

    public function showSearch(): View
    {
        return view('admin.booking.search');
    }

    public function searchResult(SearchBookingRequest $request): View
    {
        $bookings = $this->bookingService->searchBookings(
            $request->input('customer_name'),
            $request->input('customer_contact'),
            $request->input('checkin_time'),
            $request->input('checkout_time'),
        );

        return view('admin.booking.result', compact('bookings'));
    }
}

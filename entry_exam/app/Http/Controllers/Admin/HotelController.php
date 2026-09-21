<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchHotelNameRequest;
use App\Http\Requests\UpsertHotelRequest;
use App\Models\Hotel;
use App\Models\Prefecture;
use App\Services\HotelService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HotelController extends Controller
{
    public function __construct(
        private HotelService $hotelService,
        private Hotel $hotel,
        private Prefecture $prefecture,
    ) {
    }

    public function showSearch(): View
    {
        $prefectures = $this->prefecture->all();

        return view('admin.hotel.search', compact('prefectures'));

    }

    public function showEdit(int $hotel_id): View
    {
        $hotel = $this->hotel->findOrFail($hotel_id);
        $prefectures = $this->prefecture->all();

        return view('admin.hotel.edit', compact('hotel', 'prefectures'));
    }

    public function showCreate(): View
    {
        $prefectures = $this->prefecture->all();

        return view('admin.hotel.create', compact('prefectures'));
    }

    public function showEditConfirm(UpsertHotelRequest $request, int $hotel_id): View
    {
        return view('admin.hotel.edit-confirm', [
            'hotelId' => $hotel_id,
        ]);
    }

    public function showEditComplete(): View
    {
        return view('admin.hotel.edit-complete');
    }

    public function searchResult(SearchHotelNameRequest $request): View
    {
        $prefectures = $this->prefecture->all();

        session(['admin_hotel_search_url' => $request->fullUrl()]);

        return view('admin.hotel.result', [
            'hotelList' => $this->hotelService->searchHotels(
                $request->input('hotel_name'),
                $request->integer('prefecture_id') ?: null,
            ),
            'prefectures' => $prefectures,
        ]);
    }

    public function edit(UpsertHotelRequest $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $this->hotelService->updateHotel(
            $request->integer('hotel_id'),
            $request->validated()
        );

        return redirect()->route('adminHotelEditComplete');
    }

    public function create(UpsertHotelRequest $request): RedirectResponse
    {
        $this->hotelService->createHotel(
            $request->validated(),
            $request->file('file_path'),
        );

        return back()->with('success', __('hotel.created_success'));
    }

    public function delete(Request $request): RedirectResponse
    {
        $this->hotelService->deleteHotel($request->integer('hotel_id'));

        return back();
    }
}

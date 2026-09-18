<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteHotelRequest;
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
    ) {}

    public function showSearch(): View
    {
        $prefectures = $this->prefecture->all();

        return view('admin.hotel.search', compact('prefectures'));
    }

    public function showResult(): View
    {
        return view('admin.hotel.result');
    }

    public function showEdit(Request $request): View
    {
        $hotel = $request->filled('hotel_id') ? $this->hotel->find($request->input('hotel_id')) : null;
        $prefectures = $this->prefecture->all();

        return view('admin.hotel.edit', compact('hotel', 'prefectures'));
    }

    public function showCreate(): View
    {
        $prefectures = $this->prefecture->all();

        return view('admin.hotel.create', compact('prefectures'));
    }

    public function searchResult(SearchHotelNameRequest $request): View
    {
        $hotelList = $this->hotelService->searchHotels(
            $request->input('hotel_name'),
            $request->integer('prefecture_id') ?: null,
        );
        $prefectures = $this->prefecture->all();

        return view('admin.hotel.result', compact('hotelList', 'prefectures'));
    }

    public function edit(Request $request): void
    {
        //
    }

    public function create(UpsertHotelRequest $request): RedirectResponse
    {
        $this->hotelService->createHotel(
            $request->validated(),
            $request->file('file_path'),
        );

        return redirect()
            ->route('adminHotelCreatePage')
            ->with('success', __('hotel.created_success'));
    }

    public function delete(DeleteHotelRequest $request): RedirectResponse
    {
        $this->hotelService->deleteHotel($request->integer('hotel_id'));

        return back();
    }
}

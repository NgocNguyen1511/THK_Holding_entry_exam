<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompleteEditHotelRequest;
use App\Http\Requests\ConfirmEditHotelRequest;
use App\Http\Requests\DeleteHotelRequest;
use App\Http\Requests\SearchHotelNameRequest;
use App\Http\Requests\UpsertHotelRequest;
use App\Models\Hotel;
use App\Models\Prefecture;
use App\Services\FileService;
use App\Services\HotelService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HotelController extends Controller
{
    public function __construct(
        private HotelService $hotelService,
        private FileService $fileService,
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
        $hotelId = $request->input('hotel_id');
        $hotel = $this->hotel->findOrFail($hotelId);
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

    public function editConfirm(ConfirmEditHotelRequest $request): View
    {
        $hotel = $this->hotel->findOrFail($request->integer('hotel_id'));
        $prefecture = $this->prefecture->findOrFail($request->integer('prefecture_id'));

        $newFilePath = $request->input('new_file_path');

        if ($request->hasFile('file_path')) {
            if ($newFilePath && $newFilePath !== $hotel->file_path) {
                $this->fileService->deleteImage($newFilePath);
            }
            $newFilePath = $this->fileService->handleUploadedImage($request->file('file_path'), 'hotel');
        }

        $hotelName = $request->input('hotel_name');
        $prefectureId = $request->integer('prefecture_id');

        return view('admin.hotel.edit-confirm', compact('hotel', 'prefecture', 'newFilePath', 'hotelName', 'prefectureId'));
    }

    public function editComplete(CompleteEditHotelRequest $request): View
    {
        $hotel = $this->hotelService->updateHotel(
            $request->integer('hotel_id'),
            $request->validated(),
            $request->input('new_file_path'),
        );

        return view('admin.hotel.edit-complete', compact('hotel'));
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

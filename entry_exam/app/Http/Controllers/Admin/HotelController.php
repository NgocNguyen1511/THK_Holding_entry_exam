<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Hotel;
use App\Models\Prefecture;

class HotelController extends Controller
{
    /** get methods */

    public function showSearch(): View
    {
        $prefectures = Prefecture::all();
        return view('admin.hotel.search', compact('prefectures'));
    }

    public function showResult(): View
    {
        return view('admin.hotel.result');
    }

    public function showEdit(): View
    {
        return view('admin.hotel.edit');
    }

    public function showCreate(): View
    {
        return view('admin.hotel.create');
    }

    /** post methods */

    public function searchResult(Request $request): View
    {
        $request->validate(
            [
                'hotel_name' => 'required',
            ],
            [
                'hotel_name.required' => '何も入力されていません',
            ],
        );

        $var = [];

        $hotelNameToSearch = $request->input('hotel_name');
        $prefectureIdToFilter = $request->input('prefecture_id');
        $hotelList = Hotel::getHotelList($hotelNameToSearch, $prefectureIdToFilter);

        $var['hotelList'] = $hotelList;
        $var['prefectures'] = Prefecture::all();

        return view('admin.hotel.result', $var);
    }

    public function edit(Request $request): void
    {
        //
    }

    public function create(Request $request): void
    {
        //
    }

    public function delete(Request $request): void
    {
        //
    }
}

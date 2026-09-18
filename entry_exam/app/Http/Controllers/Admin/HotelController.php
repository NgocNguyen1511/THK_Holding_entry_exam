<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Hotel;
use App\Models\Prefecture;

class HotelController extends Controller
{
    /** get methods */

    public function showSearch(): View
    {
        return view('admin.hotel.search');
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
        $prefectures = Prefecture::all();
        return view('admin.hotel.create', compact('prefectures'));
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
        $hotelList = Hotel::getHotelListByName($hotelNameToSearch);

        $var['hotelList'] = $hotelList;

        return view('admin.hotel.result', $var);
    }

    public function edit(Request $request): void
    {
        //
    }

    public function create(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'hotel_name' => ['required', 'string', 'max:255'],
                'prefecture_id' => ['required', 'integer', 'exists:prefectures,prefecture_id'],
                'file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            ],
            [
                'hotel_name.required' => 'Hotel name is required.',
                'hotel_name.max' => 'Hotel name must not exceed 255 characters.',
                'prefecture_id.required' => 'Prefecture is required.',
                'prefecture_id.exists' => 'Selected prefecture does not exist.',
                'file.image' => 'Uploaded file must be an image.',
                'file.mimes' => 'Image file must be in jpg, jpeg, png, or webp format.',
                'file.max' => 'Image file size must be less than 2MB.',
            ],
        );

        $filePath = '';

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $fileName = $request->getSchemeAndHttpHost() . 'assets/img/hotel/' . time() . '_' . $file->getClientOriginalName();
            $targetDir = public_path('assets/img/hotel');

            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            $file->move($targetDir, $fileName);
            $filePath = 'hotel/' . $fileName;
        }

        Hotel::create([
            'hotel_name' => $request->input('hotel_name'),
            'prefecture_id' => $request->input('prefecture_id'),
            'file_path' => $filePath,
        ]);

        return redirect()
            ->route('adminHotelCreatePage')
            ->with('success', 'ホテルを作成しました。(Hotel created successfully)');
    }

    public function delete(Request $request): RedirectResponse
    {
        $request->validate([
            'hotel_id' => ['required', 'integer', 'exists:hotels,hotel_id'],
        ]);

        $hotelId = $request->input('hotel_id');
        $hotel = Hotel::find($hotelId);

        if ($hotel) {
            if ($hotel->file_path) {
                $imageFullPath = public_path('assets/img/' . $hotel->file_path);
                if (file_exists($imageFullPath)) {
                    @unlink($imageFullPath);
                }
            }

            $hotel->delete();
        }

        return redirect()
            ->back();
    }
}

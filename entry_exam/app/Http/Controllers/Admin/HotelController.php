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
        $prefectures = Prefecture::all();
        return view('admin.hotel.search', compact('prefectures'));
    }

    public function showResult(): View
    {
        return view('admin.hotel.result');
    }

    public function showEdit(Request $request): View
    {
        $hotelId = $request->input('hotel_id');
        $hotel = Hotel::with('prefecture')->findOrFail($hotelId);
        $prefectures = Prefecture::all();

        $hotelName = $request->old('hotel_name', $request->input('hotel_name', $hotel->hotel_name));
        $prefectureId = $request->old('prefecture_id', $request->input('prefecture_id', $hotel->prefecture_id));
        $newImageTemp = $request->old('new_image_temp', $request->input('new_image_temp'));

        $searchHotelName = $request->old('search_hotel_name', $request->input('search_hotel_name', ''));
        $searchPrefectureId = $request->old('search_prefecture_id', $request->input('search_prefecture_id', ''));

        return view('admin.hotel.edit', compact(
            'hotel',
            'prefectures',
            'hotelName',
            'prefectureId',
            'newImageTemp',
            'searchHotelName',
            'searchPrefectureId'
        ));
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
        $prefectureIdToFilter = $request->input('prefecture_id');
        $hotelList = Hotel::getHotelList($hotelNameToSearch, $prefectureIdToFilter);

        $var['hotelList'] = $hotelList;
        $var['prefectures'] = Prefecture::all();

        return view('admin.hotel.result', $var);
    }

    public function confirmEdit(Request $request): View
    {
        $request->validate(
            [
                'hotel_id' => ['required', 'integer', 'exists:hotels,hotel_id'],
                'hotel_name' => ['required', 'string', 'max:255'],
                'prefecture_id' => ['required', 'integer', 'exists:prefectures,prefecture_id'],
                'file_path' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            ],
            [
                'hotel_name.required' => 'ホテル名を入力してください。(Hotel name is required.)',
                'hotel_name.max' => 'ホテル名は255文字以内で入力してください。(Hotel name must not exceed 255 characters.)',
                'prefecture_id.required' => '都道府県を選択してください。(Prefecture is required.)',
                'prefecture_id.exists' => '選択された都道府県が存在しません。(Selected prefecture does not exist.)',
                'file_path.image' => '画像ファイルを指定してください。(Uploaded file must be an image.)',
                'file_path.mimes' => '画像はjpg, jpeg, png, webp形式でアップロードしてください。(Image must be jpg, jpeg, png, or webp.)',
                'file_path.max' => '画像サイズは2MB以下にしてください。(Image size must be less than 2MB.)',
            ]
        );

        $hotelId = $request->input('hotel_id');
        $hotel = Hotel::findOrFail($hotelId);
        $prefecture = Prefecture::findOrFail($request->input('prefecture_id'));

        $newImageTemp = $request->input('new_image_temp');
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $tempDir = public_path('assets/img/hotel/temp');

            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $file->move($tempDir, $fileName);
            $newImageTemp = $fileName;
        }

        $editData = [
            'hotel_id' => $hotelId,
            'hotel_name' => $request->input('hotel_name'),
            'prefecture_id' => $request->input('prefecture_id'),
            'prefecture_name' => $prefecture->prefecture_name,
            'current_file_path' => $hotel->file_path,
            'new_image_temp' => $newImageTemp,
            'search_hotel_name' => $request->input('search_hotel_name', ''),
            'search_prefecture_id' => $request->input('search_prefecture_id', ''),
        ];

        return view('admin.hotel.edit_confirm', compact('hotel', 'editData'));
    }

    public function completeEdit(Request $request): View|RedirectResponse
    {
        // Handle "Back" action
        if ($request->has('action') && $request->input('action') === 'back') {
            return redirect()
                ->route('adminHotelEditPage', [
                    'hotel_id' => $request->input('hotel_id'),
                    'hotel_name' => $request->input('hotel_name'),
                    'prefecture_id' => $request->input('prefecture_id'),
                    'new_image_temp' => $request->input('new_image_temp'),
                    'search_hotel_name' => $request->input('search_hotel_name', ''),
                    'search_prefecture_id' => $request->input('search_prefecture_id', ''),
                ])
                ->withInput();
        }

        $request->validate([
            'hotel_id' => ['required', 'integer', 'exists:hotels,hotel_id'],
            'hotel_name' => ['required', 'string', 'max:255'],
            'prefecture_id' => ['required', 'integer', 'exists:prefectures,prefecture_id'],
        ]);

        $hotel = Hotel::findOrFail($request->input('hotel_id'));
        $filePath = $hotel->file_path;

        // If new temp image was uploaded during confirm step, move to final directory
        $newImageTemp = $request->input('new_image_temp');
        if (!empty($newImageTemp)) {
            $tempFile = public_path('assets/img/hotel/temp/' . $newImageTemp);
            $targetDir = public_path('assets/img/hotel');

            if (file_exists($tempFile)) {
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                rename($tempFile, $targetDir . '/' . $newImageTemp);
                $filePath = 'hotel/' . $newImageTemp;
            }
        }

        $hotel->update([
            'hotel_name' => $request->input('hotel_name'),
            'prefecture_id' => $request->input('prefecture_id'),
            'file_path' => $filePath,
        ]);

        $searchHotelName = $request->input('search_hotel_name', '');
        $searchPrefectureId = $request->input('search_prefecture_id', '');

        return view('admin.hotel.edit_complete', compact('hotel', 'searchHotelName', 'searchPrefectureId'));
    }

    public function edit(Request $request): RedirectResponse
    {
        return redirect()->route('adminHotelEditPage', ['hotel_id' => $request->input('hotel_id')]);
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

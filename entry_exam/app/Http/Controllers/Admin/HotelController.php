<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateHotelRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Hotel;
use App\Models\Prefecture;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $prefectures = Prefecture::orderBy('prefecture_id')->get();
        return view('admin.hotel.create', compact('prefectures'));
    }

    /** post methods */

    public function searchResult(Request $request): View
    {
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

    public function create(CreateHotelRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $uploadedFullPath = null;

        try {
            $filePath = null;

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $targetDir = public_path('assets/img/hotel');

                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                $file->move($targetDir, $fileName);
                $uploadedFullPath = $targetDir . '/' . $fileName;
                $filePath = 'hotel/' . $fileName;
            }

            $hotel = DB::transaction(function () use ($validated, $filePath) {
                return Hotel::create([
                    'hotel_name' => $validated['hotel_name'],
                    'prefecture_id' => $validated['prefecture_id'],
                    'file_path' => $filePath,
                ]);
            });

            return redirect()
                ->route('adminHotelCreatePage')
                ->with('success', "Hotel \"{$hotel->hotel_name}\" has been registered successfully.");
        } catch (\Throwable $e) {
            // Clean up uploaded file if DB transaction or subsequent step fails
            if ($uploadedFullPath && file_exists($uploadedFullPath)) {
                @unlink($uploadedFullPath);
            }

            Log::error('Failed to create hotel: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while saving the hotel. Please try again.']);
        }
    }

    public function delete(Request $request): void
    {
        //
    }
}

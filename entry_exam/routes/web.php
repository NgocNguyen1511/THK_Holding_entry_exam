<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\HotelController as AdminHotelController;
use App\Http\Controllers\Admin\TopController as AdminTopController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\TopController;
use Illuminate\Support\Facades\Route;

/** user screen */
Route::get('/', [TopController::class, 'index'])->name('top');
Route::get('/{prefecture_name_alpha}/hotellist', [HotelController::class, 'showList'])->name('hotelList');
Route::get('/hotel/{hotel_id}', [HotelController::class, 'showDetail'])->name('hotelDetail');

/** admin screen */
Route::get('/admin', [AdminTopController::class, 'index'])->name('adminTop');
Route::get('/admin/hotel/search', [AdminHotelController::class, 'showSearch'])->name('adminHotelSearchPage');
Route::match(['get', 'post'], '/admin/hotel/edit', [AdminHotelController::class, 'showEdit'])->name('adminHotelEditPage');
Route::get('/admin/hotel/create', [AdminHotelController::class, 'showCreate'])->name('adminHotelCreatePage');
Route::get('/admin/hotel/search/result', [AdminHotelController::class, 'searchResult'])->name('adminHotelSearchResult');
Route::post('/admin/hotel/create', [AdminHotelController::class, 'create'])->name('adminHotelCreateProcess');
Route::post('/admin/hotel/delete', [AdminHotelController::class, 'delete'])->name('adminHotelDeleteProcess');
Route::post('/admin/hotel/edit-confirm', [AdminHotelController::class, 'editConfirm'])->name('adminHotelEditConfirm');
Route::post('/admin/hotel/edit-complete', [AdminHotelController::class, 'editComplete'])->name('adminHotelEditComplete');
Route::get('/admin/booking/search', [AdminBookingController::class, 'showSearch'])->name('adminBookingSearchPage');
Route::get('/admin/booking/search/result', [AdminBookingController::class, 'searchResult'])->name('adminBookingSearchResult');

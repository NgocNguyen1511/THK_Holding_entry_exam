<!-- base view -->
@extends('common.admin.base')

<!-- CSS per page -->
@section('custom_css')
    @vite('resources/scss/admin/search.scss')
    @vite('resources/scss/admin/result.scss')
@endsection

<!-- main contents -->
@section('main_contents')
    <div class="page-wrapper search-page-wrapper">
        <h2 class="title">予約情報検索画面</h2>
        <hr>
        <div class="search-hotel-name">
            <form action="{{ route('adminBookingSearchResult') }}" method="get" class="booking-search-form">
                @csrf
                <div class="form-group">
                    <label for="customer_name">顧客名</label>
                    <input type="text" id="customer_name" name="customer_name" value="{{ request('customer_name') }}"
                        placeholder="顧客名">
                </div>
                <div class="form-group">
                    <label for="customer_contact">顧客連絡先</label>
                    <input type="text" id="customer_contact" name="customer_contact"
                        value="{{ request('customer_contact') }}" placeholder="顧客連絡先">
                </div>
                <div class="form-group">
                    <label for="checkin_time">チェックイン日時</label>
                    <input type="datetime-local" id="checkin_time" name="checkin_time"
                        value="{{ request('checkin_time') }}">
                </div>
                <div class="form-group">
                    <label for="checkout_time">チェックアウト日時</label>
                    <input type="datetime-local" id="checkout_time" name="checkout_time"
                        value="{{ request('checkout_time') }}">
                </div>
                <div class="form-group form-action">
                    <button type="submit">検索</button>
                </div>
            </form>
        </div>
        <hr>
    </div>
    @yield('search_results')
@endsection

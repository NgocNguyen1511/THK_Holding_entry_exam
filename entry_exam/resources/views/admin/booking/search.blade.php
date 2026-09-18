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
            <form action="{{ route('adminBookingSearchResult') }}" method="get">
                @csrf
                <input type="text" name="customer_name" value="{{ request('customer_name') }}" placeholder="顧客名">
                <input type="text" name="customer_contact" value="{{ request('customer_contact') }}" placeholder="顧客連絡先">
                <input type="datetime-local" name="checkin_time" value="{{ request('checkin_time') }}" placeholder="チェックイン日時">
                <input type="datetime-local" name="checkout_time" value="{{ request('checkout_time') }}" placeholder="チェックアウト日時">
                <button type="submit">検索</button>
            </form>
        </div>
        <hr>
    </div>
    @yield('search_results')
@endsection

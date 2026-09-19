<!-- base view -->
@extends('common.admin.base')

<!-- CSS per page -->
@section('custom_css')
    @vite('resources/scss/admin/search.scss')
    @vite('resources/scss/admin/result.scss')
@endsection

<!-- main containts -->
@section('main_contents')
    <div class="page-wrapper search-page-wrapper">
        <h2 class="title">検索画面</h2>
        <hr>
        <div class="search-hotel-name">
            <form action="{{ route('adminHotelSearchResult') }}" method="get">
                @csrf
                <input type="text" name="hotel_name" value="{{ request('hotel_name') }}" placeholder="ホテル名">
                <select name="prefecture_id">
                    <option value="">全て</option>
                    @foreach($prefectures ?? [] as $prefecture)
                        <option value="{{ $prefecture->prefecture_id }}" {{ request('prefecture_id') == $prefecture->prefecture_id ? 'selected' : '' }}>{{ $prefecture->prefecture_name }} ({{ ucwords($prefecture->prefecture_name_alpha) }})</option>
                    @endforeach
                </select>
                <button type="submit">検索</button>
                @error('hotel_name')
                    <p style="color: red; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </form>
        </div>
        <hr>
    </div>
    @yield('search_results')
@endsection
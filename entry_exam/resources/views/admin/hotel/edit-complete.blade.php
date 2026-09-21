@extends('common.admin.base')

@section('custom_css')
    @vite('resources/scss/admin/result.scss')
@endsection

@section('main_contents')
    <div class="page-wrapper search-page-wrapper">
        <div class="search-result">
            <h3 class="search-result-title">ホテル情報の編集完了</h3>
            <p style="color: green; margin-bottom: 12px;">✓ {{ __('hotel.updated_success') }}</p>

            <div style="margin-top: 16px;">
                <button onclick="location.href='{{ route('adminHotelSearchPage') }}'">検索ページに戻る</button>
            </div>
        </div>
    </div>
@endsection

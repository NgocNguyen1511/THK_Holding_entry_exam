@extends('common.admin.base')

@section('custom_css')
    @vite('resources/scss/admin/result.scss')
@endsection

@section('main_contents')
    <div class="page-wrapper search-page-wrapper">
        <div class="search-result">
            <h3 class="search-result-title">ホテル情報の編集完了 (Edit Hotel Information Complete)</h3>
            <p style="color: green; margin-bottom: 12px;">✓ {{ __('hotel.updated_success') }}</p>

            <table class="shopsearchlist_table">
                <tbody>
                    <tr style="background-color:#BDF1FF">
                        <td nowrap="" style="width: 160px;">
                            ホテル名 (Hotel Name)
                        </td>
                        <td>
                            {{ $hotel->hotel_name }}
                        </td>
                    </tr>
                    <tr style="background-color:#BDF1FF">
                        <td nowrap="">
                            都道府県 (Prefecture)
                        </td>
                        <td>
                            {{ $hotel->prefecture->prefecture_name }} ({{ ucwords($hotel->prefecture->prefecture_name_alpha) }})
                        </td>
                    </tr>
                    @if ($hotel->file_path && file_exists(public_path('assets/img/' . $hotel->file_path)))
                        <tr style="background-color:#BDF1FF">
                            <td nowrap="">
                                ホテルイメージ (Hotel Image)
                            </td>
                            <td>
                                <img src="{{ asset('assets/img/' . $hotel->file_path) }}" alt="Hotel Image" style="max-width: 200px; max-height: 150px; display: block;">
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div style="margin-top: 16px; display: flex; gap: 15px;">
                <a href="{{ route('adminHotelSearchResult', ['hotel_name' => $hotel->hotel_name]) }}">検索結果に戻る (Back to Search Results)</a>
                <a href="{{ route('adminHotelEditPage', ['hotel_id' => $hotel->hotel_id]) }}">再度編集する (Edit Again)</a>
            </div>
        </div>
    </div>
@endsection

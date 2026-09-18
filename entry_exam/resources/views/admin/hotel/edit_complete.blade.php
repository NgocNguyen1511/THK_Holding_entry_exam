@extends('common.admin.base')

@section('custom_css')
    @vite('resources/scss/admin/create.scss')
@endsection

@section('main_contents')
    <div>
        <h1 class="title">ホテル情報 編集完了 (Edit Completed)</h1>

        <hr>
        <div style="padding: 16px 0;">
            <p style="font-size: 16px; color: #15803d; font-weight: bold; margin-bottom: 16px;">
                ホテル情報の更新が完了しました。<br>
                <span style="font-size: 14px; font-weight: normal; color: #555;">(Hotel information has been updated successfully.)</span>
            </p>

            <div style="margin-top: 20px;">
                @if (!empty($searchHotelName) || !empty($searchPrefectureId))
                    <a href="{{ route('adminHotelSearchResult', ['hotel_name' => $searchHotelName, 'prefecture_id' => $searchPrefectureId]) }}"
                        style="display: inline-block; padding: 6px 16px; text-decoration: none; color: #fff; background: #2563eb; border-radius: 4px; font-size: 14px;">
                        ホテル検索画面へ戻る (Back to Search Results)
                    </a>
                @else
                    <a href="{{ route('adminHotelSearchResult', ['hotel_name' => $hotel->hotel_name]) }}"
                        style="display: inline-block; padding: 6px 16px; text-decoration: none; color: #fff; background: #2563eb; border-radius: 4px; font-size: 14px;">
                        ホテル検索画面へ戻る (Back to Search Results)
                    </a>
                @endif
            </div>
        </div>
        <hr>
    </div>
@endsection

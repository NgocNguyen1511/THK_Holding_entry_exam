@extends('common.admin.base')

@section('custom_css')
    @vite('resources/scss/admin/result.scss')
@endsection

@section('main_contents')
    <div class="page-wrapper search-page-wrapper">
        <div class="search-result">
            <h3 class="search-result-title">ホテル情報の編集確認</h3>

            <p style="margin-bottom: 12px;">
                以下の内容で更新します。よろしければ「更新する」ボタンを押してください。
            </p>

            <table class="shopsearchlist_table">
                <tbody>
                    <tr style="background-color:#BDF1FF">
                        <td nowrap style="width: 160px;">
                            ホテル名
                        </td>
                        <td id="confirm_hotel_name"></td>
                    </tr>

                    <tr style="background-color:#BDF1FF">
                        <td nowrap>
                            都道府県
                        </td>
                        <td id="confirm_prefecture_name"></td>
                    </tr>

                    <tr style="background-color:#BDF1FF">
                        <td nowrap>
                            ホテルイメージ
                        </td>
                        <td id="confirm_image_container"></td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: 16px; display: flex; gap: 10px;">
                <form action="{{ route('adminHotelEditPage', ['hotel_id' => $hotelId, 'from_confirm' => 1]) }}" method="get">
                    <button type="submit">戻る</button>
                </form>

                <form id="update-form" action="{{ route('adminHotelEditProcess') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="hotel_id" value="{{ $hotelId }}">
                    <input type="hidden" name="hotel_name">
                    <input type="hidden" name="prefecture_id">
                    <input type="file" name="file_path" style="display: none;">

                    <button type="submit">更新する</button>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('page_js')
    @vite('resources/js/admin/edit-confirm.js')
@endsection

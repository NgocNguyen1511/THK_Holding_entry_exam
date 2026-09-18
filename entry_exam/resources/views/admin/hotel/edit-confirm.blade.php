@extends('common.admin.base')

@section('custom_css')
    @vite('resources/scss/admin/result.scss')
@endsection

@section('main_contents')
    <div class="page-wrapper search-page-wrapper">
        <div class="search-result">
            <h3 class="search-result-title">ホテル情報の編集確認 (Confirm Edit Hotel Information)</h3>
            <p style="margin-bottom: 12px;">以下の内容で更新します。よろしければ「更新する」ボタンを押してください。</p>

            <table class="shopsearchlist_table">
                <tbody>
                    <tr style="background-color:#BDF1FF">
                        <td nowrap="" style="width: 160px;">
                            ホテル名 (Hotel Name)
                        </td>
                        <td>
                            {{ $hotelName }}
                        </td>
                    </tr>
                    <tr style="background-color:#BDF1FF">
                        <td nowrap="">
                            都道府県 (Prefecture)
                        </td>
                        <td>
                            {{ $prefecture->prefecture_name }} ({{ ucwords($prefecture->prefecture_name_alpha) }})
                        </td>
                    </tr>
                    <tr style="background-color:#BDF1FF">
                        <td nowrap="">
                            ホテルイメージ (Hotel Image)
                        </td>
                        <td>
                            @if ($tempFilePath && file_exists(public_path('assets/img/' . $tempFilePath)))
                                <div>
                                    <img src="{{ asset('assets/img/' . $tempFilePath) }}" alt="New Hotel Image" style="max-width: 200px; max-height: 150px; display: block; margin-bottom: 4px;">
                                    <span>※ 新しい画像 (New Image)</span>
                                </div>
                            @elseif ($hotel->file_path && file_exists(public_path('assets/img/' . $hotel->file_path)))
                                <div>
                                    <img src="{{ asset('assets/img/' . $hotel->file_path) }}" alt="Current Hotel Image" style="max-width: 200px; max-height: 150px; display: block; margin-bottom: 4px;">
                                    <span>※ 変更なし (Current image unchanged)</span>
                                </div>
                            @else
                                <span>画像なし (No Image)</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: 16px; display: flex; gap: 10px;">
                <form action="{{ route('adminHotelEditPage') }}" method="post">
                    @csrf
                    <input type="hidden" name="hotel_id" value="{{ $hotel->hotel_id }}">
                    <input type="hidden" name="hotel_name" value="{{ $hotelName }}">
                    <input type="hidden" name="prefecture_id" value="{{ $prefectureId }}">
                    <input type="hidden" name="temp_file_path" value="{{ $tempFilePath }}">
                    <button type="submit">戻る (Back)</button>
                </form>

                <form action="{{ route('adminHotelEditComplete') }}" method="post">
                    @csrf
                    <input type="hidden" name="hotel_id" value="{{ $hotel->hotel_id }}">
                    <input type="hidden" name="hotel_name" value="{{ $hotelName }}">
                    <input type="hidden" name="prefecture_id" value="{{ $prefectureId }}">
                    <input type="hidden" name="temp_file_path" value="{{ $tempFilePath }}">
                    <button type="submit">更新する (Update)</button>
                </form>
            </div>
        </div>
    </div>
@endsection

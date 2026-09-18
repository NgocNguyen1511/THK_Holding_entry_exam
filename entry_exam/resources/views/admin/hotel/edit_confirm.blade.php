@extends('common.admin.base')

@section('custom_css')
    @vite('resources/scss/admin/create.scss')
@endsection

@section('main_contents')
    <div>
        <h1 class="title">ホテル情報 編集確認 (Confirm Hotel Edit)</h1>

        <p style="color: #444; margin: 8px 0;">以下の内容で更新します。よろしければ「更新する」ボタンを押してください。</p>

        <hr>
        <form action="{{ route('adminHotelEditComplete') }}" method="post">
            @csrf
            <input type="hidden" name="hotel_id" value="{{ $editData['hotel_id'] }}">
            <input type="hidden" name="hotel_name" value="{{ $editData['hotel_name'] }}">
            <input type="hidden" name="prefecture_id" value="{{ $editData['prefecture_id'] }}">
            <input type="hidden" name="new_image_temp" value="{{ $editData['new_image_temp'] }}">
            <input type="hidden" name="search_hotel_name" value="{{ $editData['search_hotel_name'] ?? '' }}">
            <input type="hidden" name="search_prefecture_id" value="{{ $editData['search_prefecture_id'] ?? '' }}">

            {{-- Hotel Name Display --}}
            <div>
                <label>ホテル名 (Hotel Name)</label>
                <div style="padding: 6px 10px; background: #f9f9f9; border: 1px solid #ccc; border-radius: 4px; min-width: 250px;">
                    {{ $editData['hotel_name'] }}
                </div>
            </div>

            {{-- Prefecture Display --}}
            <div style="margin-top: 12px;">
                <label>都道府県 (Prefecture)</label>
                <div style="padding: 6px 10px; background: #f9f9f9; border: 1px solid #ccc; border-radius: 4px; min-width: 250px;">
                    {{ $editData['prefecture_name'] }}
                </div>
            </div>

            {{-- Hotel Image Display --}}
            <div style="margin-top: 12px;">
                <label>ホテルイメージ (Hotel Image)</label>
                <div style="margin-top: 4px;">
                    @if (!empty($editData['new_image_temp']))
                        <img src="/assets/img/hotel/temp/{{ $editData['new_image_temp'] }}" alt="New Image Preview"
                            style="max-width: 200px; max-height: 150px; object-fit: cover; border: 1px solid #ccc; border-radius: 4px;">
                        <div style="font-size: 13px; color: #2563eb; margin-top: 4px;">※ 新しい画像が選択されています。(New image selected)</div>
                    @elseif (!empty($editData['current_file_path']))
                        <img src="/assets/img/{{ $editData['current_file_path'] }}" alt="Current Image"
                            style="max-width: 200px; max-height: 150px; object-fit: cover; border: 1px solid #ccc; border-radius: 4px;">
                        <div style="font-size: 13px; color: #666; margin-top: 4px;">※ 現在の画像を維持します。(Retaining current image)</div>
                    @else
                        <div style="font-size: 13px; color: #888;">画像なし (No image)</div>
                    @endif
                </div>
            </div>

            <div style="margin-top: 20px; display: flex; gap: 8px;">
                <button type="submit" name="action" value="update">更新する</button>
                <button type="submit" name="action" value="back"
                    style="background: #eee; color: #333; border: 1px solid #767676;">
                    修正する (戻る)
                </button>
            </div>
        </form>
        <hr>
    </div>
@endsection

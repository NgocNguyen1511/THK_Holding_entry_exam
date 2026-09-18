@extends('common.admin.base')

@section('custom_css')
    @vite('resources/scss/admin/create.scss')
@endsection

@section('main_contents')
    <div>
        <h1 class="title">ホテル情報を編集する (Edit Hotel)</h1>

        <hr>
        <form action="{{ route('adminHotelEditConfirm') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="hotel_id" value="{{ $hotel->hotel_id }}">
            <input type="hidden" name="new_image_temp" value="{{ old('new_image_temp', $newImageTemp ?? '') }}">
            <input type="hidden" name="search_hotel_name" value="{{ old('search_hotel_name', $searchHotelName ?? '') }}">
            <input type="hidden" name="search_prefecture_id" value="{{ old('search_prefecture_id', $searchPrefectureId ?? '') }}">

            {{-- Hotel name input --}}
            <div>
                <label for="hotel_name">ホテル名 (Hotel Name)<span class="required-star">*</span></label>
                <input type="text" id="hotel_name" name="hotel_name"
                    value="{{ old('hotel_name', $hotelName) }}" placeholder="Aa">
                @error('hotel_name')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            {{-- Prefecture selection --}}
            <div style="margin-top: 12px;">
                <label for="prefecture_id">都道府県 (Prefecture)<span class="required-star">*</span></label>
                <select id="prefecture_id" name="prefecture_id">
                    <option value="">-- Select a prefecture --</option>
                    @foreach ($prefectures ?? [] as $prefecture)
                        <option value="{{ $prefecture->prefecture_id }}"
                            {{ (string) old('prefecture_id', $prefectureId) === (string) $prefecture->prefecture_id ? 'selected' : '' }}>
                            {{ $prefecture->prefecture_name }} ({{ ucwords($prefecture->prefecture_name_alpha) }})
                        </option>
                    @endforeach
                </select>
                @error('prefecture_id')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            {{-- Temp image preview (if returning from confirm) --}}
            @if (!empty($newImageTemp))
                <div style="margin-top: 12px;">
                    <label>選択中の新しい画像 (Selected New Image Preview)</label>
                    <div style="margin-top: 4px;">
                        <img src="/assets/img/hotel/temp/{{ $newImageTemp }}" alt="New Image Preview"
                            style="max-width: 200px; max-height: 150px; object-fit: cover; border: 2px solid #2563eb; border-radius: 4px;">
                        <div style="font-size: 13px; color: #2563eb; margin-top: 4px;">※
                            すでに新しい画像が選択されています。変更したい場合は下で別のファイルを選んでください。</div>
                    </div>
                </div>
            @elseif (!empty($hotel->file_path))
                {{-- Current image display (if any) --}}
                <div style="margin-top: 12px;">
                    <label>現在の画像 (Current Image)</label>
                    <div style="margin-top: 4px;">
                        <img src="/assets/img/{{ $hotel->file_path }}" alt="{{ $hotel->hotel_name }}"
                            style="max-width: 200px; max-height: 150px; object-fit: cover; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                </div>
            @endif

            {{-- New file input --}}
            <div style="margin-top: 12px;">
                <div class="label-with-optional">
                    <label for="file_path">新しい画像 (New Image)</label>
                    <span class="optional-badge">Optional</span>
                </div>

                <div class="file-input-wrapper">
                    <input type="file" id="file_path" name="file_path"
                        class="form-control file-control @error('file_path') is-invalid @enderror"
                        accept="image/jpeg,image/png,image/jpg,image/webp">
                    <div class="file-hint">Supported formats: JPEG, PNG, JPG, WEBP (Max 2MB)</div>
                </div>
                @error('file_path')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-top: 16px; display: flex; gap: 8px;">
                <button type="submit">確認画面へ</button>
                @if (!empty($searchHotelName) || !empty($searchPrefectureId))
                    <a href="{{ route('adminHotelSearchResult', ['hotel_name' => $searchHotelName, 'prefecture_id' => $searchPrefectureId]) }}"
                        style="display: inline-block; padding: 2px 8px; text-decoration: none; color: #333; background: #eee; border: 1px solid #767676; border-radius: 2px; font-size: 13px; line-height: normal;">
                        戻る
                    </a>
                @else
                    <a href="javascript:history.back();"
                        style="display: inline-block; padding: 2px 8px; text-decoration: none; color: #333; background: #eee; border: 1px solid #767676; border-radius: 2px; font-size: 13px; line-height: normal;">
                        戻る
                    </a>
                @endif
            </div>
        </form>
        <hr>
    </div>
@endsection

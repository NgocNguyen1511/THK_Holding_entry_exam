@extends('common.admin.base')

@section('custom_css')
    @vite('resources/scss/admin/create.scss')
@endsection

@section('main_contents')
    <div>
        <h1 class="title">ホテル情報の編集 (Edit Hotel Information)</h1>
        <hr>

        <form action="{{ route('adminHotelEditConfirm') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="hotel_id" value="{{ $hotel->hotel_id }}">
            <input type="hidden" name="temp_file_path" value="{{ old('temp_file_path', request('temp_file_path')) }}">

            {{-- Hotel name input --}}
            <div>
                <label for="hotel_name">ホテル名 (Hotel Name)<span class="required-star">*</span></label>
                <input type="text" id="hotel_name" name="hotel_name" value="{{ old('hotel_name', request('hotel_name', $hotel->hotel_name)) }}" placeholder="ホテル名を入力">
                @error('hotel_name')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            {{-- Prefecture selection --}}
            <div style="margin-top: 12px;">
                <label for="prefecture_id">都道府県 (Prefecture)<span class="required-star">*</span></label>
                <select id="prefecture_id" name="prefecture_id">
                    <option value="">-- 都道府県を選択してください --</option>
                    @foreach ($prefectures ?? [] as $prefecture)
                        <option value="{{ $prefecture->prefecture_id }}"
                            {{ (string) old('prefecture_id', request('prefecture_id', $hotel->prefecture_id)) === (string) $prefecture->prefecture_id ? 'selected' : '' }}>
                            {{ $prefecture->prefecture_name }} ({{ ucwords($prefecture->prefecture_name_alpha) }})
                        </option>
                    @endforeach
                </select>
                @error('prefecture_id')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            {{-- Hotel image section --}}
            <div style="margin-top: 12px;">
                <div class="label-with-optional">
                    <label for="file_path">ホテルイメージ (Hotel Image)</label>
                    <span class="optional-badge">Optional</span>
                </div>

                @if (request('temp_file_path') && file_exists(public_path('assets/img/' . request('temp_file_path'))))
                    <div style="margin: 8px 0;">
                        <img src="{{ asset('assets/img/' . request('temp_file_path')) }}" alt="Temporary Uploaded Image" style="max-width: 200px; max-height: 150px; display: block; border: 1px solid #ccc; border-radius: 4px;">
                        <div style="font-size: 12px; color: #555; margin-top: 4px;">※ アップロード済みの新しい画像 (Uploaded new image)</div>
                    </div>
                @elseif ($hotel->file_path && file_exists(public_path('assets/img/' . $hotel->file_path)))
                    <div style="margin: 8px 0;">
                        <img src="{{ asset('assets/img/' . $hotel->file_path) }}" alt="Current Hotel Image" style="max-width: 200px; max-height: 150px; display: block; border: 1px solid #ccc; border-radius: 4px;">
                        <div style="font-size: 12px; color: #555; margin-top: 4px;">※ 現在の画像 (Current Image)</div>
                    </div>
                @endif

                <div class="file-input-wrapper">
                    <input type="file" id="file_path" name="file_path"
                        class="form-control file-control @error('file_path') is-invalid @enderror"
                        accept="image/jpeg,image/png,image/jpg,image/webp">
                    <div class="file-hint">Supported formats: JPEG, PNG, JPG, WEBP (Max 2MB)</div>
                </div>
                @error('file_path')
                    <p style="color: red; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-top: 16px; display: flex; gap: 12px;">
                <a href="{{ route('adminHotelSearchResult', ['hotel_name' => $hotel->hotel_name]) }}">キャンセル (Cancel)</a>
                <button type="submit">確認画面へ (Go to Confirmation)</button>
            </div>
        </form>
        <hr>
    </div>
@endsection

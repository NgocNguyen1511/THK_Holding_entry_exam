@extends('common.admin.base')

@section('custom_css')
    @vite('resources/scss/admin/create.scss')
@endsection

@section('main_contents')
    <div>
        <h1 class="title">ホテル情報の編集</h1>
        <hr>

        <div class="search-hotel-name">
            @php
                $newFilePath = old('new_file_path', request('new_file_path'));
            @endphp

            <form action="{{ route('adminHotelEditConfirm') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="hotel_id" value="{{ $hotel->hotel_id }}">
                <input type="hidden" name="new_file_path" value="{{ $newFilePath }}">

                {{-- Hotel name input --}}
                <div>
                    <label for="hotel_name">ホテル名<span class="required-star">*</span></label>
                    <input type="text" id="hotel_name" name="hotel_name"
                        value="{{ old('hotel_name', request('hotel_name', $hotel->hotel_name)) }}" placeholder="ホテル名を入力">
                    @error('hotel_name')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Prefecture selection --}}
                <div style="margin-top: 12px;">
                    <label for="prefecture_id">都道府県<span class="required-star">*</span></label>
                    <select id="prefecture_id" name="prefecture_id">
                        <option value="">-- 都道府県 --</option>
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
                        <label for="file_path">ホテルイメージ</label>
                        <span class="optional-badge">Optional</span>
                    </div>

                    {{-- Image preview --}}
                    @if ($newFilePath && file_exists(public_path('assets/img/' . $newFilePath)))
                        <div style="margin: 8px 0;">
                            <img src="{{ asset('assets/img/' . $newFilePath) }}" alt="Selected Hotel Image"
                                style="max-width: 200px; max-height: 150px; display: block; border: 1px solid #ccc; border-radius: 4px;">
                        </div>
                    @elseif ($hotel->file_path && file_exists(public_path('assets/img/' . $hotel->file_path)))
                        <div style="margin: 8px 0;">
                            <img src="{{ asset('assets/img/' . $hotel->file_path) }}" alt="Current Hotel Image"
                                style="max-width: 200px; max-height: 150px; display: block; border: 1px solid #ccc; border-radius: 4px;">
                            <div style="font-size: 12px; color: #555; margin-top: 4px;">※ 現在の画像</div>
                        </div>
                    @endif

                    <div class="file-input-wrapper">
                        <input type="file" id="file_path" name="file_path"
                            class="form-control file-control @error('file_path') is-invalid @enderror"
                            accept="image/jpeg,image/png,image/jpg,image/webp">
                        @if ($newFilePath)
                            <div style="font-size: 12px; color: #555; margin-top: 4px;">選択済み: {{ basename($newFilePath) }}</div>
                        @endif
                        <div class="file-hint">対応フォーマット：JPEG、PNG、JPG、WEBP（最大2MB）</div>
                    </div>
                    @error('file_path')
                        <p style="color: red; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-top: 16px; display: flex; gap: 12px;">
                    <button onclick="location.href='{{ route('adminHotelSearchResult', ['hotel_name' => $hotel->hotel_name]) }}'">キャンセル</button>
                    <button type="submit">確認画面へ</button>
                </div>
            </form>
        </div>
        <hr>
    </div>
@endsection

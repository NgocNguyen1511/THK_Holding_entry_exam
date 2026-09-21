@extends('common.admin.base')

@section('custom_css')
    @vite('resources/scss/admin/create.scss')
@endsection

@section('main_contents')
    <div>
        <h1 class="title">ホテル情報の編集</h1>
        <hr>

        <div class="search-hotel-name">
            <form id="hotel-form" action="{{ route('adminHotelEditConfirm', ['hotel_id' => $hotel->hotel_id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="hotel_id" name="hotel_id" value="{{ $hotel->hotel_id }}">

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
                        @foreach ($prefectures ?? [] as $pref)
                            @if (is_object($pref))
                                <option value="{{ $pref->prefecture_id }}"
                                    {{ old('prefecture_id', request('prefecture_id', $hotel->prefecture_id)) == $pref->prefecture_id ? 'selected' : '' }}>
                                    {{ $pref->prefecture_name }}
                                    ({{ ucwords($pref->prefecture_name_alpha ?? '') }})
                                </option>
                            @endif
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

                    <div class="file-input-wrapper">
                        <img id="img-preview" src="{{ $hotel->file_path ? asset('assets/img/' . $hotel->file_path) : '' }}"
                            style=" max-width: 150px; max-height: 150px; margin-bottom: 8px; display: {{ $hotel->file_path ? 'block' : 'none' }}; ">
                        <div style="display: flex; gap: 8px;">
                            <button type="button" id="btn_upload_img" style="cursor: pointer;">
                                画像をアップロード
                            </button>

                            <button type="button" id="btn_delete_img"
                                style="cursor: pointer; color: red; display: {{ $hotel->file_path ? 'inline-block' : 'none' }}; ">
                                画像を削除
                            </button>
                        </div>
                        <input type="file" id="file_path" name="file_path"
                            class="form-control file-control @error('file_path') is-invalid @enderror"
                            accept="image/jpeg,image/png,image/jpg,image/webp" style="display: none;">
                        <div class="file-hint">対応フォーマット：JPEG、PNG、JPG、WEBP（最大2MB）</div>
                    </div>
                    @error('file_path')
                        <p style="color: red; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-top: 16px; display: flex; gap: 12px;">
                    <button type="button"
                        onclick="location.href='{{ session('admin_hotel_search_url', route('adminHotelSearchPage')) }}'">キャンセル</button>
                    <button type="submit">確認画面へ</button>
                </div>
            </form>
        </div>
        <hr>
    </div>
@endsection

@section('page_js')
    @vite('resources/js/admin/edit-hotel.js')
@endsection

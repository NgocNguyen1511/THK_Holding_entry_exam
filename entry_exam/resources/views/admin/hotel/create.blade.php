@extends('common.admin.base')

@section('custom_css')
    @vite('resources/scss/admin/create.scss')
@endsection

@section('main_contents')
    <div>
        <h1 class="title">新しいホテルを作成する (Create new hotel)</h1>

        <hr>
        <form action="{{ route('adminHotelCreateProcess') }}" method="post" enctype="multipart/form-data">
            @csrf

            {{-- Hotel name input --}}
            <div>
                <label for="hotel_name">ホテル名 (Hotel Name)<span class="required-star">*</span></label>
                <input type="text" id="hotel_name" name="hotel_name" value="{{ old('hotel_name') }}" placeholder="Aa">
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
                            {{ old('prefecture_id') == $prefecture->prefecture_id ? 'selected' : '' }}>
                            {{ $prefecture->prefecture_name }} ({{ ucwords($prefecture->prefecture_name_alpha) }})
                        </option>
                    @endforeach
                </select>
                @error('prefecture_id')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            {{-- File path input --}}
            <div style="margin-top: 12px;">
                <div class="label-with-optional">
                    <label for="file_path">ホテルイメージ (Hotel Image)</label>
                    <span class="optional-badge">Optional</span>
                </div>

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

            <div style="margin-top: 16px;">
                <button type="submit">提出する</button>
            </div>
        </form>
        <hr>
    </div>
@endsection

@section('page_js')
    @if (session('success'))
        <script>
            alert(@json(session('success')));
        </script>
    @endif
@endsection

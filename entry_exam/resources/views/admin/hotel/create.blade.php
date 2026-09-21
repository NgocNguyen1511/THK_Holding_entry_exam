@extends('common.admin.base')

@section('custom_css')
    @vite('resources/scss/admin/create.scss')
@endsection

@section('main_contents')
    <div>
        <h1 class="title">新しいホテルを作成する</h1>

        <hr>
        <div class="search-hotel-name">
            <form action="{{ route('adminHotelCreateProcess') }}" method="post" enctype="multipart/form-data">
                @csrf

                {{-- Hotel name input --}}
                <div>
                    <label for="hotel_name">ホテル名<span class="required-star">*</span></label>
                    <input type="text" id="hotel_name" name="hotel_name" value="{{ old('hotel_name') }}" placeholder="Aa">
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
                            @if (is_object($prefecture))
                                <option value="{{ $prefecture->prefecture_id }}"
                                    {{ old('prefecture_id') == $prefecture->prefecture_id ? 'selected' : '' }}>
                                    {{ $prefecture->prefecture_name }}
                                    ({{ ucwords($prefecture->prefecture_name_alpha ?? '') }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('prefecture_id')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                {{-- File path input --}}
                <div style="margin-top: 12px;">
                    <div class="label-with-optional">
                        <label for="file_path">ホテルイメージ</label>
                        <span class="optional-badge">オプション</span>
                    </div>

                    <div class="file-input-wrapper">
                        <img id="img-preview"
                            style="width: 100px; height: 100px; object-fit: cover; display: none; margin-bottom: 8px;" />
                        <div style="display: flex; gap: 8px;">
                            <button type="button" style="cursor: pointer;"
                                onclick="document.getElementById('file_path').click()">
                                画像をアップロード
                            </button>

                            <button type="button" id="btn_delete_img" style="cursor: pointer; color: red; display: none;"
                                onclick="deleteFile()">
                                画像を削除
                            </button>
                        </div>
                        <input type="file" id="file_path" name="file_path" onchange="loadFile(event)"
                            class="form-control file-control @error('file_path') is-invalid @enderror"
                            accept="image/jpeg,image/png,image/jpg,image/webp" style="display: none;">
                        <div class="file-hint">対応フォーマット：JPEG、PNG、JPG、WEBP（最大2MB）</div>
                    </div>
                    @error('file_path')
                        <p style="color: red; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-top: 16px;">
                    <button type="submit">提出する</button>
                </div>
            </form>
        </div>
        <hr>
    </div>
@endsection

@section('page_js')
    @if (session('success'))
        <script>
            alert(@json(session('success')));
        </script>
    @endif

    <script>
        const fileInput = document.getElementById('file_path');
        const output = document.getElementById('img-preview');
        const btnDelete = document.getElementById('btn_delete_img');

        const loadFile = (event) => {
            const output = document.getElementById('img-preview');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src)
            }
            output.style.display = 'block';
            btnDelete.style.display = 'inline-block';
        }

        const deleteFile = () => {
            fileInput.value = '';
            output.removeAttribute('src');
            output.style.display = 'none';
            btnDelete.style.display = 'none';
        }
    </script>
@endsection

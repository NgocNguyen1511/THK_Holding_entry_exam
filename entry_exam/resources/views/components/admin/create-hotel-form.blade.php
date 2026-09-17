{{-- Hotel Name --}}
<div class="form-group">
    <div class="form-label-wrap">
        <label for="hotel_name">Hotel Name<span class="required-star">*</span></label>
    </div>
    <div class="form-hint">Please enter the official name of the hotel (maximum 255 characters).</div>
    <input
        type="text"
        id="hotel_name"
        name="hotel_name"
        class="form-control @error('hotel_name') is-invalid @enderror"
        placeholder="e.g. Grand Hyatt Tokyo"
        value="{{ old('hotel_name', $hotel->hotel_name ?? '') }}"
        autocomplete="off"
    >
    <div class="input-bottom-meta">
        <div class="error-container">
            @error('hotel_name')
                <div class="invalid-feedback">
                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ $message }}</span>
                </div>
            @enderror
        </div>
        <span id="hotelNameCounter" class="char-counter">0/255</span>
    </div>
</div>

{{-- Prefecture --}}
<div class="form-group">
    <div class="form-label-wrap">
        <label for="prefecture_id">Prefecture<span class="required-star">*</span></label>
    </div>
    <div class="form-hint">Select the prefecture where the hotel is located.</div>
    <div class="select-wrapper">
        <select
            id="prefecture_id"
            name="prefecture_id"
            class="form-control @error('prefecture_id') is-invalid @enderror"
        >
            <option value="">-- Select a prefecture --</option>
            @foreach($prefectures as $pref)
                <option
                    value="{{ $pref->prefecture_id }}"
                    {{ (string) old('prefecture_id', $hotel->prefecture_id ?? '') === (string) $pref->prefecture_id ? 'selected' : '' }}
                >
                    {{ ucfirst($pref->prefecture_name_alpha) }} ({{ $pref->prefecture_name }})
                </option>
            @endforeach
        </select>
        <div class="select-arrow">
            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </div>
    </div>
    @error('prefecture_id')
        <div class="invalid-feedback">
            <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>{{ $message }}</span>
        </div>
    @enderror
</div>

{{-- Hotel Image --}}
<div class="form-group">
    <div class="form-label-wrap">
        <label for="file">Hotel Image</label>
        <span class="badge-optional">Optional</span>
    </div>
    <div class="form-hint">Main cover image displayed on hotel listing and detail pages.</div>

    <div class="file-input-wrapper">
        <input
            type="file"
            id="file"
            name="file"
            class="form-control file-control @error('file') is-invalid @enderror"
            accept="image/jpeg,image/png,image/jpg,image/webp"
        >
        <div class="file-hint">Supported formats: JPEG, PNG, JPG, WEBP (Max 2MB)</div>
    </div>

    {{-- Live Image Preview Card --}}
    <div id="imagePreviewContainer" class="image-preview-card" style="display: none;">
        <img id="imagePreview" src="" alt="Preview" class="preview-thumb">
        <div class="preview-details">
            <div id="imageFileName" class="file-name">filename.jpg</div>
            <div id="imageFileSize" class="file-size">0 KB</div>
        </div>
        <button type="button" id="btnRemoveImage" class="btn-remove-image">Remove</button>
    </div>

    @error('file')
        <div class="invalid-feedback">
            <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>{{ $message }}</span>
        </div>
    @enderror
</div>

<script>
(function () {
    function initHotelImagePreview() {
        const fileInput = document.getElementById('file');
        const previewContainer = document.getElementById('imagePreviewContainer');
        const previewImg = document.getElementById('imagePreview');
        const fileNameEl = document.getElementById('imageFileName');
        const fileSizeEl = document.getElementById('imageFileSize');
        const btnRemove = document.getElementById('btnRemoveImage');

        if (!fileInput || fileInput.dataset.previewInitialized) return;
        fileInput.dataset.previewInitialized = "true";

        fileInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            if (!file.type.match('image.*')) {
                alert('Please select a valid image file.');
                fileInput.value = '';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Image file size must not exceed 2MB.');
                fileInput.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (evt) {
                if (previewImg) previewImg.src = evt.target.result;
                if (fileNameEl) fileNameEl.textContent = file.name;
                if (fileSizeEl) fileSizeEl.textContent = (file.size / 1024).toFixed(1) + ' KB';
                if (previewContainer) previewContainer.style.display = 'flex';
            };
            reader.readAsDataURL(file);
        });

        if (btnRemove) {
            btnRemove.addEventListener('click', function () {
                fileInput.value = '';
                if (previewImg) previewImg.src = '';
                if (previewContainer) previewContainer.style.display = 'none';
            });
        }
    }

    function initHotelNameCounter() {
        const hotelNameInput = document.getElementById('hotel_name');
        const counterEl = document.getElementById('hotelNameCounter');

        if (!hotelNameInput || !counterEl) return;

        function updateCounter() {
            const len = hotelNameInput.value.length;
            counterEl.textContent = `${len}/255`;
            if (len > 255) {
                counterEl.classList.add('exceeded');
            } else {
                counterEl.classList.remove('exceeded');
            }
        }

        hotelNameInput.addEventListener('input', updateCounter);
        updateCounter();
    }

    function initFormComponents() {
        initHotelImagePreview();
        initHotelNameCounter();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFormComponents);
    } else {
        initFormComponents();
    }
})();
</script>


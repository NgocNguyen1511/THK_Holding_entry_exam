import { storage } from "../storage";
import { toFileBase64 } from "../common";

const STORAGE_KEY = "edit";
const form = document.querySelector("#hotel-form");

if (form) {
    const urlParams = new URLSearchParams(window.location.search);
    if (!urlParams.has('from_confirm')) {
        storage.remove(STORAGE_KEY);
    }

    const fileInput = form.querySelector("#file_path");
    const uploadBtn = form.querySelector("#btn_upload_img");
    const deleteBtn = form.querySelector("#btn_delete_img");
    const imgPreview = form.querySelector("#img-preview");
    const prefSelect = form.elements.prefecture_id;
    const hotelId = form.elements.hotel_id.value;

    const saveDraft = (base64) => {
        storage.set(STORAGE_KEY, {
            ...(storage.get(STORAGE_KEY) ?? {}),
            hotel_id: hotelId,
            file_path: base64,
        });
    };

    uploadBtn?.addEventListener("click", () => fileInput.click());

    fileInput?.addEventListener("change", async () => {
        const file = fileInput.files?.[0];
        if (!file) return;

        const base64 = await toFileBase64(file);
        imgPreview.src = base64;
        imgPreview.style.display = "block";
        deleteBtn.style.display = "inline-block";

        if (file.size <= 2 * 1024 * 1024) {
            saveDraft(base64);
        }
    });

    deleteBtn?.addEventListener("click", () => {
        fileInput.value = "";
        imgPreview.removeAttribute("src");
        imgPreview.style.display = "none";
        deleteBtn.style.display = "none";
        saveDraft(null);
    });

    form.addEventListener("submit", () => {
        const draft = storage.get(STORAGE_KEY) ?? {};
        let finalPath =
            draft.hotel_id && String(draft.hotel_id) === String(hotelId)
                ? draft.file_path
                : undefined;

        if (finalPath === undefined) {
            finalPath = imgPreview.getAttribute("src") || null;
        }

        storage.set(STORAGE_KEY, {
            hotel_id: hotelId,
            hotel_name: form.elements.hotel_name.value,
            prefecture_id: prefSelect.value,
            prefecture_name:
                prefSelect.options[prefSelect.selectedIndex]?.text.trim() ?? "",
            file_path: finalPath,
        });
    });

    // restore draft
    const draft = storage.get(STORAGE_KEY);
    if (draft && String(draft.hotel_id) === String(hotelId)) {
        form.elements.hotel_name.value = draft.hotel_name ?? "";
        prefSelect.value = draft.prefecture_id ?? "";

        if (draft.file_path) {
            imgPreview.src = draft.file_path;
            imgPreview.style.display = "block";
            deleteBtn.style.display = "inline-block";
        }
    }
}

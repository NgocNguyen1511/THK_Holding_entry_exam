import { storage } from "../storage";
import { decodeBase64 } from "../common";

const STORAGE_KEY = "edit";
const draft = storage.get(STORAGE_KEY);

if (draft) {
    document.querySelector("#confirm_hotel_name").textContent = draft.hotel_name ?? "";
    document.querySelector("#confirm_prefecture_name").textContent = draft.prefecture_name ?? "";

    const imageContainer = document.querySelector("#confirm_image_container");
    if (draft.file_path) {
        imageContainer.innerHTML = `
            <img src="${draft.file_path}" alt="Hotel Image" style="max-width: 200px; max-height: 150px;">
        `;
    }

    const updateForm = document.querySelector("#update-form");
    updateForm.elements.hotel_name.value = draft.hotel_name ?? "";
    updateForm.elements.prefecture_id.value = draft.prefecture_id ?? "";

    const filePathInput = updateForm.elements.file_path;

    if (draft.file_path && draft.file_path.startsWith('data:image')) {
        const file = decodeBase64(draft.file_path, 'upload.jpg');
        const dt = new DataTransfer();
        dt.items.add(file);
        filePathInput.files = dt.files;
    } else if (draft.file_path === null) {
        const hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.name = "file_path";
        hiddenInput.value = "";
        filePathInput.replaceWith(hiddenInput);
    } else {
        filePathInput.remove();
    }

    updateForm.addEventListener("submit", () => {
        storage.remove(STORAGE_KEY);
    });
}

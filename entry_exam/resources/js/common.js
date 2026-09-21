export function toFileBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result);
        reader.onerror = () => reject(reader.error);
        reader.readAsDataURL(file);
    });
}

export function decodeBase64(dataurl, filename = 'upload.jpg') {
    let arr = dataurl.split(','), 
        mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]), 
        n = bstr.length, 
        u8arr = new Uint8Array(n);
    while(n--) { 
        u8arr[n] = bstr.charCodeAt(n); 
    }
    return new File([u8arr], filename, {type: mime});
}

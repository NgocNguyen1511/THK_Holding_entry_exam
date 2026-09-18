<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class FileService
{
    public function handleUploadedImage(?UploadedFile $file, string $directory = 'hotel'): ?string
    {
        if (! $file) {
            return null;
        }

        $fileName = time().'_'.$file->getClientOriginalName();
        $targetDir = public_path('assets/img/'.$directory);

        if (! file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $file->move($targetDir, $fileName);

        return $directory.'/'.$fileName;
    }

    public function handleTempUploadedImage(?UploadedFile $file): ?string
    {
        return $this->handleUploadedImage($file, 'hotel/temp');
    }

    public function promoteTempImage(string $tempFilePath, string $destinationDir = 'hotel'): ?string
    {
        $sourceFullPath = public_path('assets/img/'.$tempFilePath);

        if (! file_exists($sourceFullPath)) {
            return null;
        }

        $fileName = basename($tempFilePath);
        $targetDir = public_path('assets/img/'.$destinationDir);

        if (! file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $targetFullPath = $targetDir.'/'.$fileName;
        rename($sourceFullPath, $targetFullPath);

        return $destinationDir.'/'.$fileName;
    }

    public function deleteImage(?string $filePath): void
    {
        if (! $filePath) {
            return;
        }

        $imageFullPath = public_path('assets/img/'.$filePath);

        if (file_exists($imageFullPath)) {
            @unlink($imageFullPath);
        }
    }
}

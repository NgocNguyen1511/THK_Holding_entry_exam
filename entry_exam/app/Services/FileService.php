<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    /**
     * @param UploadedFile|null $file
     * @param string $directory
     * @return string|null
     */
    public function upload(?UploadedFile $file, string $directory): ?string
    {
        if (! $file) {
            return null;
        }

        return $file->store($directory, 'assets');
    }

    /**
     * @param string|null $filePath
     * @return bool
     */
    public function delete(?string $filePath): bool
    {
        if (! $filePath) {
            return false;
        }

        if (Storage::disk('assets')->exists($filePath)) {
            return Storage::disk('assets')->delete($filePath);
        }

        return false;
    }

    public function exists(?string $filePath): bool
    {
        return $filePath ? Storage::disk('assets')->exists($filePath) : false;
    }
}
